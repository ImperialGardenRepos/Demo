<?php

use ig\Helpers\Section;
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

$minPrice = Section::getMinPrice((int)$arResult['SECTION']['IBLOCK_ID'], (int)$arResult['SECTION']['ID']);
Meta::getInstance()->setMinPrice($minPrice);