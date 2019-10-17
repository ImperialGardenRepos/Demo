<?php

use ig\Helpers\IBlockSectionHelper;
use ig\Helpers\UrlHelper;
use ig\Seo\Meta;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if ((int)$arResult['SECTION']['DEPTH_LEVEL'] === 1) {
    Meta::getInstance()->setBaseTitle($arResult['NAME']);
}

if ((int)$arResult['SECTION']['DEPTH_LEVEL'] === 2) {
    $parentTitle = IBlockSectionHelper::getSectionName((int)$arResult['SECTION']['IBLOCK_SECTION_ID']);
    Meta::getInstance()->setBaseTitle($parentTitle . ' ' . $arResult['NAME']);
}

if ((int)$arResult['SECTION']['DEPTH_LEVEL'] > 2) {
    Meta::getInstance()->setBaseTitle($arResult['NAME']);
}

if ($arResult['SECTION'] === null) {
    Meta::getInstance()->setBaseTitle('Растения');
    $iBlockId = (int)current($arResult['SECTIONS'])['IBLOCK_ID'];
    $sectionId = 0;
} else {
    $iBlockId = (int)$arResult['SECTION']['IBLOCK_ID'];
    $sectionId = 0;
}

Meta::getInstance()->setMinPrice(IBlockSectionHelper::getMinPrice($iBlockId, $sectionId));

if ($arResult['DESCRIPTION'] === null) {
    $virtualPage = \ig\Datasource\Highload\VirtualPageTable::getByUrl(UrlHelper::getUrlWithoutParams());
    if (count($virtualPage) === 1) {
        $virtualPage = array_shift($virtualPage);
        $arResult['DESCRIPTION'] = $virtualPage['UF_TEXT'];
    }
}