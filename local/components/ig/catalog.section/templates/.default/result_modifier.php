<?php

use ig\Helpers\Section;
use ig\Helpers\Url;
use ig\Highload\VirtualPage;
use ig\Seo\Meta;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if ((int)$arResult['SECTION']['DEPTH_LEVEL'] === 1) {
    Meta::getInstance()->setBaseTitle($arResult['NAME']);
}

if ((int)$arResult['SECTION']['DEPTH_LEVEL'] === 2) {
    $parentTitle = Section::getSectionName((int)$arResult['SECTION']['IBLOCK_SECTION_ID']);
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

Meta::getInstance()->setMinPrice(Section::getMinPrice($iBlockId, $sectionId));

if ($arResult['DESCRIPTION'] === null) {
    $virtualPage = VirtualPage::getByUrl(Url::getUrlWithoutParams());
    if (count($virtualPage) === 1) {
        $virtualPage = array_shift($virtualPage);
        $arResult['DESCRIPTION'] = $virtualPage['UF_TEXT'];
    }
}