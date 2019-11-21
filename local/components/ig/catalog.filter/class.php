<?php

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Foolz\SphinxQL\Exception\ConnectionException;
use Foolz\SphinxQL\Exception\DatabaseException;
use Foolz\SphinxQL\Exception\SphinxQLException;
use ig\Base\Registry;
use ig\Datasource\Custom\Month;
use ig\Datasource\FilterProperty;
use ig\Datasource\Highload\FilterAliasTable;
use ig\Datasource\Highload\GroupTable;
use ig\Datasource\Highload\PeriodHeightNowTable;
use ig\Datasource\Highload\PeriodHeightTable;
use ig\Datasource\Highload\PeriodPriceTable;
use ig\Datasource\Highload\PropertyValueTable;
use ig\Datasource\Highload\VirtualPageTable;
use ig\Datasource\Property\Enum;
use ig\Datasource\Sphinx\Query;
use ig\Helpers\ArrayHelper;
use ig\Helpers\IBlockHelper;
use ig\Helpers\SphinxHelper;
use ig\Helpers\UrlHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @noinspection AutoloadingIssuesInspection */

class CatalogFilter extends CBitrixComponent
{
    /** @var Query */
    private $index;

    /** @var string */
    private $alias;

    /**
     * @return mixed|void
     * @throws ArgumentException
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SphinxQLException
     * @throws SystemException
     */
    public function executeComponent(): void
    {
//        try {
        $this->startResultCache();
        $this->setResult();

        /** Store filter result in registry to be used in catalog section and ajax response later */
        Registry::getInstance()->set('CATALOG_FILTER_VALUES', $this->arResult['CURRENT_VALUES']);
        Registry::getInstance()->set('CATALOG_FILTER_RESULT_LINK', $this->arResult['RESULT_LINK']);
        Registry::getInstance()->set('CATALOG_FILTER_PRODUCT_COUNT', $this->arResult['COUNT_PRODUCTS']);
        Registry::getInstance()->set('CATALOG_FILTER_BASE_URL', $this->arResult['BASE_URL']);
        Registry::getInstance()->set('CATALOG_FILTER_ALIAS', $this->alias);

        $this->cleanUpResult();
        $this->includeComponentTemplate();
        $this->endResultCache();
//        } catch (Exception $e) {
        //ToDo: catch HTTPNotFound separately
        $this->abortResultCache();
//        }
    }

    /**
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        // TODO: Change
        /**
         * IBLOCK_ID
         * BASE_URL
         * SPHINX_INDEX
         * FILTER_SRC
         */
        $expandFilter = $_COOKIE['filter_active'] ?? '0';
        $arParams['EXPAND_FILTER'] = (int)$expandFilter > 0 ? 'Y' : 'N';
        $skuIBlock = IBlockHelper::getSKUIBlock($arParams['IBLOCK_ID']);
        $arParams['SKU_IBLOCK_ID'] = $skuIBlock === null ? null : $skuIBlock['IBLOCK_ID'];

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * @throws ArgumentException
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws ObjectPropertyException
     * @throws SphinxQLException
     * @throws SystemException
     * @throws LoaderException
     */
    protected function setResult(): void
    {
        $this->setFilterBase();
        $this->setFilterProperties();
        $this->setSphinxPropertyValues();
        $this->setCurrentValues();
        $this->setFilterResultLink();
        $this->setCounts();
    }

    /**
     * Actual exceptions depend on FilterProperty::getValues implementation for each data source provided.
     *
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws LoaderException
     */
    protected function setFilterProperties(): void
    {
        $properties = [];
        foreach ($this->arParams['FILTER_SRC'] as $dataClass => $propertyCodes) {
            if (!class_exists($dataClass)) {
                throw new ArgumentException("Invalid data class {$dataClass} provided as FILTER_SRC param");
            }
            $dataModel = new $dataClass;
            if (!$dataModel instanceof FilterProperty) {
                throw new ArgumentException("Class {$dataClass} provided as FILTER_SRC param doesn't implement FilterProperty");
            }
            /**
             * @var FilterProperty|Month|PeriodPriceTable|GroupTable|PeriodHeightNowTable|PropertyValueTable|PeriodHeightTable|Enum $dataModel
             */
            $properties[] = $dataModel->getValues($propertyCodes, ['IBLOCK_ID' => [$this->arParams['IBLOCK_ID'], $this->arParams['SKU_IBLOCK_ID']]]);
        }
        $this->arResult['PROPERTIES'] = array_merge(...$properties);
        $validProperties = array_merge(...array_values($this->arParams['FILTER_SRC']));
        /**
         * There was problem with SHIRINA_KRONY_OLD property, as it is valid, active and filled.
         * This property is not set as Sphinx field and facet query was broken.
         */
        foreach ($this->arResult['PROPERTIES'] as $key => $property) {
            if (!in_array($key, $validProperties, true)) {
                unset($this->arResult['PROPERTIES'][$key]);
            }
        }
    }

    /**
     * @throws ArgumentException
     */
    protected function setSphinxPropertyValues(): void
    {
        if (isset($this->arParams['SPHINX_INDEX'])) {
            $indexClass = $this->arParams['SPHINX_INDEX'];
            if (!class_exists($indexClass)) {
                throw new ArgumentException("Invalid index class {$indexClass} provided as SPHINX_INDEX param");
            }
            foreach ($this->arResult['PROPERTIES'] as $propertyCode => &$propertyValues) {
                foreach ($propertyValues['VALUES'] as &$propertyValue) {
                    if (isset($propertyValue['SKIP_SPHINX']) && $propertyValue['SKIP_SPHINX'] === true) {
                        continue;
                    }
                    //ToDo:: Notice
                    $internalValue = $propertyValue['VALUE'];
                    $propertyValue['SPHINX_VALUE'] = SphinxHelper::convertValue($propertyCode, $internalValue, $indexClass);
                }
                unset($propertyValue);
            }
            unset($propertyValues);
        }
    }

    /**
     * Simply takes value from params and sets to arResult.
     */
    protected function setFilterBase(): void
    {
        $this->arResult['BASE_URL'] = $this->arParams['BASE_URL'];
    }

    /**
     * Combines setting product/offer counts and Sphinx property counts.
     *
     * @throws ArgumentException
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    protected function setCounts(): void
    {
        if (isset($this->arParams['SPHINX_INDEX'])) {
            $indexQueryClass = $this->arParams['SPHINX_INDEX'];
            if (!class_exists($this->arParams['SPHINX_INDEX'])) {
                throw new ArgumentException("Invalid index class {$indexQueryClass} provided as SPHINX_INDEX param");
            }
            //ToDo:: on params
            $this->index = new $indexQueryClass;
            $this->setProductCount();
            $this->setSphinxPropertyValueCount();
        }
    }

    /**
     * Sets count of products available for each property value indexed by Sphinx with current filter.
     *
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    protected function setSphinxPropertyValueCount(): void
    {
        $propertyCodes = array_keys($this->arResult['PROPERTIES']);
        unset($propertyCode);
        $values = $this->arResult['CURRENT_VALUES'];
        if (isset($this->arParams['SECTION_ID']) && $this->arParams['SECTION_ID'] > 0) {
            $depthLevel = $this->arParams['SECTION_DEPTH'] ?? 1;
            $key = "cat_section_{$depthLevel}";
            $values[$key] = (int)$this->arParams['SECTION_ID'];
        }
        $facetCount = $this->index->countFacet($propertyCodes, $values);
        foreach ($facetCount as &$propertyCount) {
            $propertyCount['TOTAL'] = array_sum($propertyCount['VALUES']);
        }
        unset($propertyCount);
        $this->arResult['COUNT_DATA'] = $facetCount;
    }

    /**
     * Sets total product and offer count with current filter.
     *
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    protected function setProductCount(): void
    {
        $values = $this->arResult['CURRENT_VALUES'];
        if (isset($this->arParams['SECTION_ID']) && $this->arParams['SECTION_ID'] > 0) {
            $depthLevel = $this->arParams['SECTION_DEPTH'] ?? 1;
            $key = "cat_section_{$depthLevel}";
            $values[$key] = (int)$this->arParams['SECTION_ID'];
        }
        $this->arResult['COUNT_PRODUCTS'] = $this->index->countPropertyValueMatch($values, 'cat_id');
        $this->arResult['COUNT_OFFERS'] = $this->index->countPropertyValueMatch($values);

    }

    /**
     * Sets current filter params to arResult['CURRENT_VALUES'].
     * Params are taken from
     * 3) virtual page
     * 2) filter alias
     * 1) 'F' array from Request
     *
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected function setCurrentValues(): void
    {
        $currentValues = [];
        if ($this->arParams['IS_VIRTUAL_FILTER'] === true) {
            $currentValues = VirtualPageTable::getFilterParams(UrlHelper::getUrlWithoutParams());
        }
        /** We don't apply filter alias from get param if filter isset with virtual page */
        if ($this->arParams['IS_FILTER'] === true) {
            $aliasValue = UrlHelper::getUrlParam('filterAlias');
            $currentValues = array_merge($currentValues, FilterAliasTable::getRequestByAlias($aliasValue));
        }
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        if (isset($request['F'])) {
            $currentValues = $request['F'];
        }
        foreach ($currentValues as $key => $value) {
            if ($value === '' || ($value['FROM'] === '0' && $value['TO'] === '11')) {
                unset($currentValues[$key]);
            }
        }
        $currentValues = ArrayHelper::removeKeyPrefix($currentValues, 'PROPERTY_');
        $this->arResult['CURRENT_VALUES'] = $currentValues;
    }

    /**
     * Adds counts and checked information to arResult['PROPERTIES'].
     * Removes separate COUNT_DATA and CURRENT_VALUES arrays from arResult.
     */
    protected function cleanUpResult(): void
    {
        foreach ($this->arResult['PROPERTIES'] as $propertyCode => &$property) {
            if (isset($property['VALUES']['FROM'], $property['VALUES']['TO'])) {
                $property['RANGE_CURRENT'] = [
                    'FROM' => $this->arResult['CURRENT_VALUES'][$propertyCode]['FROM'],
                    'TO' => $this->arResult['CURRENT_VALUES'][$propertyCode]['TO'],
                ];
                continue;
            }
            /** Count total $value */
            $property['COUNT'] = $this->arResult['COUNT_DATA'][$propertyCode]['TOTAL'];
            foreach ($property['VALUES'] as $valueCode => &$value) {
                /** Count */
                $count = 0;
                $sphinxValue = (string)$value['SPHINX_VALUE'];
                if (isset($this->arResult['COUNT_DATA'][$propertyCode]['VALUES'][$sphinxValue])) {
                    $count = $this->arResult['COUNT_DATA'][$propertyCode]['VALUES'][$sphinxValue];
                }
                $value['COUNT'] = $count;
                /** Checked */
                $checked = false;
                if (isset($this->arResult['CURRENT_VALUES'][$propertyCode])) {

                    if (
                        is_array($this->arResult['CURRENT_VALUES'][$propertyCode])
                        && in_array($valueCode, $this->arResult['CURRENT_VALUES'][$propertyCode], true)
                    ) {
                        $checked = true;
                    }
                    if ($this->arResult['CURRENT_VALUES'][$propertyCode] === $valueCode) {
                        $checked = true;
                    }
                    if ($value['NAME'] === 'Да' && $value['VALUE'] = $this->arResult['CURRENT_VALUES'][$propertyCode]) {
                        $checked = true;
                    }
                    if (
                        is_scalar($this->arResult['CURRENT_VALUES'][$propertyCode])
                        && (string)$this->arResult['CURRENT_VALUES'][$propertyCode] === (string)$valueCode
                    ) {
                        $checked = true;
                    }
                }
                $value['CHECKED'] = $checked;
            }
            unset($value);
        }
        unset($this->arResult['CURRENT_VALUES'], $this->arResult['COUNT_DATA'], $property);
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     * Sets filter alias or virtual page link if isset
     */
    protected function setFilterResultLink(): void
    {
        $currentValues = SphinxHelper::convertArray($this->arResult['CURRENT_VALUES']);
        $virtualPageUrl = VirtualPageTable::getByFilterParams($currentValues);
        if ($virtualPageUrl !== null) {
            $this->arResult['RESULT_LINK'] = UrlHelper::getHost() . UrlHelper::buildFromParts($virtualPageUrl);
            return;
        }
        $filterAlias = FilterAliasTable::getByFilterParams($currentValues);
        if ($filterAlias !== null) {
            $this->alias = $filterAlias;
            $this->arResult['RESULT_LINK'] = $this->arResult['BASE_URL'] . '?filterAlias=' . $filterAlias;
            return;
        }
        $this->arResult['RESULT_LINK'] = $this->arResult['BASE_URL'];
    }
}