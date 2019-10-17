<?php

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\PageNavigation;
use Foolz\SphinxQL\Exception\ConnectionException;
use Foolz\SphinxQL\Exception\DatabaseException;
use Foolz\SphinxQL\Exception\SphinxQLException;
use ig\Base\Registry;
use ig\CFormat;
use ig\CHelper;
use ig\Datasource\Highload\GroupTable;
use ig\Datasource\Sphinx\Query;
use ig\Exceptions\NotFoundHttpException;
use ig\Helpers\CartHelper;
use ig\Helpers\IBlockHelper;
use ig\Helpers\IBlockSectionHelper;
use ig\Helpers\SphinxHelper;
use ig\Helpers\UrlHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @noinspection AutoloadingIssuesInspection */

class CatalogSection extends CBitrixComponent
{
    /** @var Query */
    private $index;

    public function executeComponent()
    {
        try {
            $this->startResultCache();
            $this->setResult();
            $this->includeComponentTemplate();
            $this->endResultCache();
        } catch (Exception $e) {
            //ToDo: catch HTTPNotFound separately
            $this->abortResultCache();
        }
    }

    public function onPrepareComponentParams($arParams): array
    {
        if (isset($arParams['SPHINX_INDEX'])) {
            $this->index = new $arParams['SPHINX_INDEX'];
        }
        $this->setOrderParam($arParams);
        return parent::onPrepareComponentParams($arParams);
    }

    protected function setOrderParam(&$arParams): void
    {
        $orderCookie = $_COOKIE['catalog_order'] ?? null;
        switch ($orderCookie) {
            case 'name-asc':
                $arParams['ORDER_FIELD'] = 'FULL_NAME';
                $arParams['ORDER_DIRECTION'] = 'ASC';
                break;
            case 'name-desc':
                $arParams['ORDER_FIELD'] = 'FULL_NAME';
                $arParams['ORDER_DIRECTION'] = 'DESC';
                break;
            case 'popular-asc':
                $arParams['ORDER_FIELD'] = 'SORT';
                $arParams['ORDER_DIRECTION'] = 'ASC';
                break;
            case 'popular-desc':
                $arParams['ORDER_FIELD'] = 'SORT';
                $arParams['ORDER_DIRECTION'] = 'DESC';
                break;
            case 'price-asc':
                $arParams['ORDER_FIELD'] = 'MIN_PRICE';
                $arParams['ORDER_DIRECTION'] = 'ASC';
                break;
            case 'price-desc':
                $arParams['ORDER_FIELD'] = 'MIN_PRICE';
                $arParams['ORDER_DIRECTION'] = 'DESC';
                break;
            default:
                $arParams['ORDER_FIELD'] = 'FULL_NAME';
                $arParams['ORDER_DIRECTION'] = 'ASC';
                break;
        }

    }

    /**
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws LoaderException
     * @throws NotFoundHttpException
     * @throws NotImplementedException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SphinxQLException
     * @throws SystemException
     */
    protected function setResult(): void
    {
        $this->setBaseUrl();
        $this->setCart();
        $this->setPriceTypes();
        $this->setSection();
        $this->setBreadcrumbs();
        $this->setPagination();
        $this->setIPropertyValues();
        $this->setText();
        $this->setItems();

        $this->setOffers();
        $this->setItemSections();
        $this->setItemGroups();
        $this->setItemNames();
    }

    /**
     * @throws NotFoundHttpException
     * @throws SystemException
     */
    protected function setPagination(): void
    {
        //ToDo:: move to param
        $paginationId = 'catalog-page';
        $pageNum = UrlHelper::getPageNum($paginationId);
        $pageSize = $this->arParams['PAGE_ELEMENT_COUNT'] ?? 12;
        try {
            $recordCount = Registry::getInstance()->get('CATALOG_FILTER_PRODUCT_COUNT');
        } catch (InvalidArgumentException $e) {
            $recordCount = $pageSize;
        }
        $maxPage = ceil($recordCount / $pageSize);

        if ($recordCount === 0 || $pageNum > $maxPage) {
            throw new NotFoundHttpException();
        }
        $pagination = new PageNavigation($paginationId);
        $pagination->setCurrentPage($pageNum)
            ->setPageSize($pageSize)
            ->setRecordCount($recordCount)
            ->initFromUri();


        $this->arResult['NAV_PARAMS'] = [
            'NAV_OBJECT' => $pagination,
            'FILTER_ALIAS' => '',
            'SEF_MODE' => 'Y',
        ];
        try {
            $this->arResult['NAV_PARAMS']['BASE_URL'] = Registry::getInstance()->get('CATALOG_FILTER_BASE_URL');
            $this->arResult['NAV_PARAMS']['FILTER_ALIAS'] = Registry::getInstance()->get('CATALOG_FILTER_ALIAS');

        } catch (InvalidArgumentException $e) {
            //ToDo:: BASE_URL;
            $this->arResult['NAV_PARAMS']['FILTER_ALIAS'] = null;
        }
    }

    /**
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws NotImplementedException
     * @throws ObjectNotFoundException
     */
    protected function setCart(): void
    {
        $this->arResult['CART'] = CartHelper::get();
    }

    /**
     * @throws ArgumentException
     * @throws LoaderException
     */
    protected function setPriceTypes(): void
    {
        $iBlockId = $this->arParams['IBLOCK_ID'];
        $skuIBlockInfo = CCatalogSku::GetInfoByProductIBlock($iBlockId);
        if ($skuIBlockInfo === false) {
            throw new ArgumentException("IBlock with ID {$iBlockId} has no linked SKUs");
        }
        $skuIBlockId = $skuIBlockInfo['IBLOCK_ID'];
        $priceTypes = CIBlockPriceTools::GetCatalogPrices($skuIBlockId, $this->arParams['PRICE_CODE']);
        $allowedPriceTypes = CIBlockPriceTools::GetAllowCatalogPrices($priceTypes);

        $this->arResult['PRICE_TYPES'] = $priceTypes;
        $this->arResult['ALLOWED_PRICE_TYPES'] = $allowedPriceTypes;
    }

    /**
     * @throws ArgumentException
     * @throws NotFoundHttpException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected function setSection(): void
    {
        if (!isset($this->arParams['VARIABLES']['SECTION_ID']) || (int)$this->arParams['VARIABLES']['SECTION_ID'] < 1) {
            return;
        }
        $section = IBlockSectionHelper::get((int)$this->arParams['IBLOCK_ID'], (int)$this->arParams['VARIABLES']['SECTION_ID']);
        if ($section === null) {
            throw new NotFoundHttpException();
        }
        $section['PATH'] = IBlockSectionHelper::getPath($section, $this->arParams['SEF_URL_TEMPLATES']);
        $this->arResult['SECTION'] = $section;
    }

    protected function setBreadcrumbs(): void
    {
        global $APPLICATION;
        if (isset($this->arResult['SECTION'])) {
            if (isset($this->arResult['SECTION']['PATH'])) {
                foreach ($this->arResult['SECTION']['PATH'] as $parent) {
                    $APPLICATION->AddChainItem($parent['NAME'], $parent['SECTION_PAGE_URL']);
                }
            }
            $APPLICATION->AddChainItem($this->arResult['SECTION']['NAME'], $this->arResult['SECTION']['SECTION_PAGE_URL']);
        }
    }

    /**
     *
     */
    protected function setIPropertyValues(): void
    {
        $this->arResult['IPROPERTY_VALUES'] = IBlockSectionHelper::getInheritedProperties(
            (int)$this->arParams['IBLOCK_ID'],
            (int)$this->arParams['VARIABLES']['SECTION_ID']
        );
    }

    protected function setBaseUrl(): void
    {
        $this->arResult['BASE_URL'] = $this->arParams['BASE_URL'];
    }

    /**
     *
     */
    protected function setText(): void
    {
        /**
         * ToDo: NAME, DESCRIPTION, DESCRIPTION_TYPE
         * Order:
         * 1st from virtual page
         * 2nd from iblock section (if exist)
         * 3rd from iblock
         */
    }

    /**
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    public function setItems(): void
    {
        $filter = $this->getFilter();
        $elements = CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            [
                '*',
                'PROPERTY_*',
            ]
        );
        if (isset($filter['ID'])) {
            foreach ($filter['ID'] as $id) {
                $this->arResult['ITEMS'][$id] = null;
            }
        }
        while ($element = $elements->GetNextElement(true, false)) {
            $fields = $element->GetFields();
            $id = $fields['ID'];
            $this->arResult['ITEMS'][$id] = $fields;
            $this->arResult['ITEMS'][$id]['PROPERTIES'] = $element->GetProperties();
        }
    }


    /**
     *
     */
    public function setOffers(): void
    {
        $itemIds = array_column($this->arResult['ITEMS'], 'ID');
        $iBlockIds = array_unique(array_column($this->arResult['ITEMS'], 'IBLOCK_ID'));
        /** Current implementation don't allow multiple iBlocks */
        $offersIBlock = IBlockHelper::getSKUIBlock(end($iBlockIds));
        if ($offersIBlock === null) {
            return;
        }
        $offersIBlockId = $offersIBlock['IBLOCK_ID'];
        $offers = CIBlockElement::GetList(
            [
                'CATALOG_PRICE_' . CATALOG_BASE_PRICE_ID => 'ASC',
            ],
            [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $offersIBlockId,
                'PROPERTY_CML2_LINK' => $itemIds,
            ],

            false,
            false,
            [
                '*',
                'PROPERTY_*',
                'PROPERTY_ACTION',
                'PROPERTY_AVAILABLE',
                'PROPERTY_CML2_LINK',
                //ToDo:: from params
                'CATALOG_GROUP_' . CATALOG_BASE_PRICE_ID,
                'CATALOG_GROUP_' . CATALOG_ACTION_PRICE_ID,
            ]
        );
        while ($offer = $offers->GetNextElement(true, false)) {
            $fields = $offer->GetFields();
            $offerId = $fields['ID'];
            $properties = $offer->GetProperties();
            $itemId = $properties['CML2_LINK']['VALUE'];
            $this->arResult['ITEMS'][$itemId]['OFFERS'][$offerId] = $fields;
            $this->arResult['ITEMS'][$itemId]['OFFERS'][$offerId]['PROPERTIES'] = $properties;
            /** ToDo:: get rid of */
            CHelper::prepareItemPrices($this->arResult['ITEMS'][$itemId]['OFFERS'][$offerId]);
        }
    }

    /**
     * @return array|null
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    protected function getSphinxItemIDs(): ?array
    {
        try {
            $smartFilter = Registry::getInstance()->get('CATALOG_FILTER_VALUES');
            if (isset($this->arResult['SECTION'])) {
                $sectionColumn = 'cat_section_' . $this->arResult['SECTION']['DEPTH_LEVEL'];
                $sectionValue = $this->arResult['SECTION']['ID'];
                $smartFilter[$sectionColumn] = (int)$sectionValue;
            }
            /** @var PageNavigation $pagination */
            $pagination = $this->arResult['NAV_PARAMS']['NAV_OBJECT'];
            $sphinxOrderField = SphinxHelper::convertKey($this->arParams['ORDER_FIELD']);
            $sphinxOrderDirection = strtolower($this->arParams['ORDER_DIRECTION']);
            $this->index->setOrder($sphinxOrderField, $sphinxOrderDirection);
            return $this->index->getItemIDs($smartFilter, $pagination->getCurrentPage(), $pagination->getPageSize());
        } catch (InvalidArgumentException $e) {
            $e->getMessage();
            /** No smart filter is OK, global filter may be used instead */
        }
        return null;
    }

    /**
     * @return array|null
     */
    protected function getGlobalFilter(): ?array
    {
        $globalFilter = $this->arParams['FILTER_NAME'];
        /** It is trash, but it is Bitrix way :-( */
        global $$globalFilter;
        $filter = $$globalFilter;
        if ($filter === null) {
            return null;
        }
        return $filter;
    }

    /**
     * First use smart filter, if exist.
     * Then use global filter.
     * Both can't be used at once - this will brake pagination.
     *
     * @return array|null
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    protected function getFilter(): ?array
    {
        if ($this->getSphinxItemIDs() !== null) {
            return ['ID' => $this->getSphinxItemIDs()];
        }
        if ($this->getGlobalFilter() !== null) {
            return $this->getGlobalFilter();
        }
        return null;
    }

    /**
     * Sets item latin and russian names and full names from section props
     * ToDo: cleanup
     */
    protected function setItemNames(): void
    {
        foreach ($this->arResult['ITEMS'] as &$item) {

            $isRussianItemName = $item['PROPERTIES']['IS_RUSSIAN']['VALUE'] ?? false;
            $latinName = $item['PROPERTIES']['NAME_LAT']['VALUE'] ?? false;
            $name = $item['NAME'];

            $isView = isset($item['PROPERTIES']['IS_VIEW']) && $item['PROPERTIES']['IS_VIEW']['VALUE'] !== '';

            if ($isRussianItemName === false) {
                $item['NAME_MAIN'] = $latinName !== false ? $latinName : $name;
                $item['NAME_ALT'] = $name;
                continue;
            }
            $item['NAME_MAIN'] = $name;
            $item['NAME_ALT'] = $latinName !== false ? $latinName : $name;
            /**
             * Custom name format for plant IBlock
             */
            if ((int)$this->arParams['IBLOCK_ID'] === 1) {

                $item['NAME_MAIN_FULL'] = CFormat::formatPlantTitle(
                    $isView ? '' : $name,
                    $item['PATH'][1]['NAME'],
                    $item['PATH'][2]['NAME']
                );
                $item['NAME_ALT_FULL'] = CFormat::formatPlantTitle(
                    $isView ? '' : $latinName,
                    $item['PATH'][1]['NAME_LATIN'],
                    $item['PATH'][2]['NAME_LATIN']
                );
            }
            if($item['PROPERTIES']['FULL_NAME']['VALUE']) {
                $item['NAME_MAIN_FULL'] = $item['PROPERTIES']['FULL_NAME']['VALUE'];
            }
        }
        unset($item);

    }

    /**
     * As custom section binding was implemented
     */
    protected function setItemSections(): void
    {
        $itemSecondLevelSectionIDs = [];
        foreach ($this->arResult['ITEMS'] as $item) {
            $itemSecondLevelSectionIDs[$item['ID']] = $item['IBLOCK_SECTION_ID'];
        }
        $filter = [];
        if (isset($this->arParams['IBLOCK_ID'])) {
            $filter['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
        }

        $filter['DEPTH_LEVEL'] = 2;
        $secondLevelSections = $this->getItemsSections($itemSecondLevelSectionIDs, $filter);
        $this->setItemsSectionsByLevel($secondLevelSections, 2);

        $itemFirstLevelSectionIDs = [];
        foreach ($secondLevelSections as $itemId => $section) {
            $itemFirstLevelSectionIDs[$itemId] = $section['PARENT'];

        }
        $filter['DEPTH_LEVEL'] = 1;
        $firstLevelSections = $this->getItemsSections($itemFirstLevelSectionIDs, $filter);
        $this->setItemsSectionsByLevel($firstLevelSections, 1);
    }

    /**
     * @param $sections
     * @param $level
     */
    protected function setItemsSectionsByLevel($sections, $level): void
    {
        foreach ($sections as $itemId => $section) {
            $this->arResult['ITEMS'][$itemId]['PATH'][$level] = $section;
        }
    }

    /**
     * @param array $itemSectionIds
     * @param array $filter
     * @return array
     */
    protected function getItemsSections(array $itemSectionIds, array $filter = []): array
    {
        $sectionIds = array_values(array_unique($itemSectionIds));
        $sectionToItem = [];
        foreach ($itemSectionIds as $itemId => $sectionId) {
            $sectionToItem[$sectionId][] = $itemId;
        }
        $filter['ID'] = $sectionIds;
        $sections = CIBlockSection::GetList(
            [],
            $filter,
            false,
            [
                'ID',
                'NAME',
                'CODE',
                'DEPTH_LEVEL',
                'SECTION_PAGE_URL',
                'UF_NAME_LAT',
                'IBLOCK_SECTION_ID',
            ]
        );
        $result = [];
        while ($section = $sections->GetNext(true, false)) {
            foreach ($sectionToItem[$section['ID']] as $itemId) {
                $result[$itemId] = [
                    'ID' => $section['ID'],
                    'NAME' => $section['NAME'],
                    'NAME_LATIN' => $section['UF_NAME_LAT'],
                    'CODE' => $section['CODE'],
                    'URL' => $section['SECTION_PAGE_URL'],
                    'PARENT' => $section['IBLOCK_SECTION_ID'],
                ];
            }
        }
        return $result;
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected function setItemGroups(): void
    {
        $groupNames = GroupTable::getNames();
        foreach ($this->arResult['ITEMS'] as &$item) {
            if (isset($item['PROPERTIES']['GROUP'])) {
                $item['GROUP'] = $groupNames[$item['PROPERTIES']['GROUP']['VALUE']];
            }
        }
        unset($item);
    }
}