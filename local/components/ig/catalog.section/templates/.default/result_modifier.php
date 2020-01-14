<?php

use ig\Datasource\Highload\VirtualPageTable;
use ig\Helpers\IBlockSectionHelper;
use ig\Helpers\UrlHelper;
use ig\Seo\Meta;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** Base title */
if (isset($arParams['SET_META']) && $arParams['SET_META'] !== 'N') {
    if (isset($arResult['SECTION'])) {
        if ((int)$arResult['SECTION']['DEPTH_LEVEL'] === 1) {
            Meta::getInstance()->setBaseTitle($arResult['SECTION']['NAME']);
        }
        if ((int)$arResult['SECTION']['DEPTH_LEVEL'] === 2) {
            $parentTitle = IBlockSectionHelper::getSectionName((int)$arResult['SECTION']['IBLOCK_SECTION_ID']);
            Meta::getInstance()->setBaseTitle($parentTitle . ' ' . $arResult['SECTION']['NAME']);
        }
        if ((int)$arResult['SECTION']['DEPTH_LEVEL'] > 2) {
            Meta::getInstance()->setBaseTitle($arResult['SECTION']['NAME']);
        }
        $arResult['DESCRIPTION'] = $arResult['SECTION']['DESCRIPTION'];
    }

    /** Minimal section price */
    if (!isset($arResult['SECTION'])) {
        Meta::getInstance()->setBaseTitle('Растения');
        $iBlockId = $arParams['IBLOCK_ID'];
        $sectionId = 0;
    } else {
        $iBlockId = (int)$arResult['SECTION']['IBLOCK_ID'];
        $sectionId = (int)$arResult['SECTION']['ID'];
    }
    Meta::getInstance()->setMinPrice(IBlockSectionHelper::getMinPrice($iBlockId, $sectionId));

    if ($arResult['DESCRIPTION'] === null) {
        $virtualPage = VirtualPageTable::getByUrl(UrlHelper::getUrlWithoutParams());
        if (count($virtualPage) === 1) {
            $virtualPage = array_shift($virtualPage);
            $arResult['DESCRIPTION'] = $virtualPage['TEXT'];
        }
    }

    Meta::setFinalMeta();
}
