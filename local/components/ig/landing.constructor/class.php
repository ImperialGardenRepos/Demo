<?php

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException as ArgumentExceptionAlias;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

class CLandingConstructor extends CBitrixComponent
{
    /**
     * To enable new blocks in constructor we need to
     * 1) Name all block properties like {BLOCK_NAME}_{PROPERTY_NAME}_{BLOCK_NUMBER},
     * where BLOCK_NUMBER must be a digit and start from 1 even for single block
     * 2) Add block name in constant below
     */
    private const BLOCK_NAMES = [
        'BANNER',
    ];


    /**
     * @param $arParams
     * @return array
     * @throws ArgumentExceptionAlias
     * @throws \Bitrix\Main\SystemException
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
     * @return _CIBElement
     */
    private function getElement(): _CIBElement
    {
        $element = CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'CODE' => $this->arParams['CODE']
            ],
            false,
            false,
            [
                '*',
                'PROPERTY_*'
            ]
        );

        if ($element->SelectedRowsCount() === 0) {
//            ToDo:: set 404
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
                if (stristr($propertyKey, $blockType) !== false) {
                    $landingBlocks[$blockType][] = str_replace(["{$blockType}_", '_1'], '', $propertyKey);
                }
            }
        }

        return $landingBlocks;
    }

    /**
     * Method returns array with active block counters for each block type.
     * If no active blocks, it throws 404.
     * Blocks with zero counters aren't included into result.
     *
     * @param array $structure
     * @param array $properties
     * @return array
     */
    private function getBlocksCounters(array $structure, array $properties): array
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
        }
        return $counters;
    }

    /**
     * Method returns active blocks in sorting order according to {BLOCK_NAME}_SORT_{BLOCK_NUMBER}
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
                    $block[$propertyName] = $properties["{$blockName}_{$propertyName}_{$i}"]["VALUE"];
                }
                $block['TEMPLATE'] = $blockName;
                $blocks[] = $block;
            }
        }
        return $blocks;
    }
}