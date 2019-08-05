<?php
/**
 * @package LandingConstructor
 * @author Oleg Makeev me@olegmakeev.com
 *
 */

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException as ArgumentExceptionAlias;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;

class CLandingConstructor extends CBitrixComponent
{
    /**
     * To add new block to constructor
     * 1) Name all block properties like {BLOCK_NAME}_{PROPERTY_NAME}_{BLOCK_NUMBER},
     * where BLOCK_NUMBER must be a digit and start from 1 even for single block
     * 2) Add block name in constant below.
     *
     * Property creation can be automated
     * @see /local/migrations/260719200000_create_banner_props_for_constructor.php
     *
     * Important: {BLOCK_NAME}_TEMPLATE property name is reserved
     */
    private const BLOCK_NAMES = [
        'BANNER',
        'TEXT',
        'PRODUCT',

    ];


    /**
     * @param $arParams
     * @return array
     * @throws ArgumentExceptionAlias
     * @throws SystemException
     */
    public function onPrepareComponentParams($arParams)
    {
        $arParams = parent::onPrepareComponentParams($arParams);
        if (!array_key_exists('IBLOCK_ID', $arParams) || !is_int((int)$arParams['IBLOCK_ID'])) {
            throw new ArgumentExceptionAlias('Undefined IBLOCK_ID');
        }

        $requestArray = Application::getInstance()->getContext()->getRequest()->toArray();
        if (!array_key_exists('CODE', $requestArray) || (bool)$requestArray['CODE'] === false) {
//            ToDo:: set 404
        }
        $arParams['CODE'] = $requestArray['CODE'];

        return $arParams;
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        try {
            $this->startResultCache();
            $this->loadModules();
            $this->setResult();
            $this->includeComponentTemplate();
            $this->endResultCache();
        } catch (Exception $e) {
            $this->abortResultCache();
//            ToDo:: throw 500
        }
    }


    /**
     * @throws LoaderException
     */
    private function loadModules(): void
    {
        Loader::includeModule('iblock');
    }

    /**
     * Method-wrapper of CIBlockElement::GetList with Constructor Parameters
     * In case 404 is set inside function null is returned
     * @return _CIBElement|null
     */
    private function getElement(): ?_CIBElement
    {
        $element = CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'CODE' => $this->arParams['CODE']
            ],
            false,
            false,
//ToDo:: optimize prop selection, select only from block names
            [
                '*',
                'PROPERTY_*'
            ]
        );

        if ($element->SelectedRowsCount() === 0) {
//ToDo:: set 404
            return null;
        }

        return $element->GetNextElement();
    }

    /**
     * Method combines all other methods used to fill $arResult
     */
    private function setResult(): void
    {
        $element = $this->getElement();
        $this->arResult['BLOCKS'] = $this->getBlocks($element->GetProperties());
    }

    /**
     * Method forms block structures according to blocks defined in BLOCK_NAMES const.
     * We assume that all landing block props have name like {BLOCK_NAME}_{PROPERTY_NAME}_{BLOCK_NUMBER}
     * So we return structure like
     * [
     *      'BLOCK_NAME' => [
     *          'PROPERTY_NAME',
     *          'PROPERTY_NAME 2',
     *          ...
     *      ]
     * ]
     * Properties without number in tail are ignored
     *
     * @param array $properties
     * @return array
     */
    private function getBlocksStructure(array $properties): array
    {
        $landingBlocks = [];
        $propertyKeys = array_filter(
            array_keys($properties),
            static function ($value) {
                if (preg_match('/(.*)(_1)$/', $value)) {
                    return true;
                }
                return false;
            }
        );
        foreach (static::BLOCK_NAMES as $blockType) {

            foreach ($propertyKeys as $propertyKey) {
                if (preg_match("/^{$blockType}_(.*)$/", $propertyKey) !== 0) {
                    $landingBlocks[$blockType][] = preg_replace("/^{$blockType}_(.*)_1$/", '$1', $propertyKey);
                }
            }
        }

        return $landingBlocks;
    }

    /**
     * Method returns array with active block counters for each block type.
     * Blocks with zero counters aren't included into result.
     * In case 404 is set inside function null is returned
     *
     * @param array $structure
     * @param array $properties
     * @return array|null
     */
    private function getBlocksCounters(array $structure, array $properties): ?array
    {
        $counters = [];
        $blockNames = array_keys($structure);
        foreach ($blockNames as $blockName) {
            $activeCounter = 0;
            $i = 1;
            while ((int)$properties["{$blockName}_ACTIVE_{$i}"]['VALUE'] === 1) {
                $activeCounter++;
                $i++;
            }
            if ($activeCounter !== 0) {
                $counters[$blockName] = $activeCounter;
            }
        }
        if ($counters === []) {
//            ToDo:: set 404 as no active blocks
            return null;
        }
        return $counters;
    }

    /**
     * Method returns active blocks in sorting order according to {BLOCK_NAME}_SORT_{BLOCK_NUMBER}
     * Properties are also set here.
     * For all properties except L[IST] only value is set, for L prop VALUE_XML_ID is set too
     * @param array $properties
     * @return array
     */
    private function getBlocks(array $properties): array
    {
        $blocks = [];
        $structure = $this->getBlocksStructure($properties);
        $counters = $this->getBlocksCounters($structure, $properties);

//        ToDo:: sorting
        foreach ($counters as $blockName => $counter) {
            for ($i = 1; $i <= $counter; $i++) {
                $block = [];
                foreach ($structure[$blockName] as $propertyName) {
                    $property = $properties["{$blockName}_{$propertyName}_{$i}"];
                    /**
                     * If is Enum property, store VALUE_XML_ID too
                     */
                    if (array_key_exists('VALUE_XML_ID', $property)) {
                        $block[$propertyName] = [
                            'VALUE_XML_ID' => $property['VALUE_XML_ID'],
                            'VALUE' => $property['VALUE'],
                        ];
                        continue;
                    }
                    $block[$propertyName] = $properties["{$blockName}_{$propertyName}_{$i}"]["VALUE"];
                }
                $block['TEMPLATE'] = $blockName;
                $blocks[] = $block;
            }
        }
        return $blocks;
    }
}