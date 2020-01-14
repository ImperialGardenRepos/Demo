<?php

use ig\CFormat;
use ig\Helpers\ArrayHelper;
use ig\Seo\Meta;
use ig\Seo\PageSchema;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!empty($arResult['PROPERTIES']['IS_RUSSIAN']['VALUE'])) {
    $name = $arResult['NAME'];
    $nameAlt = $arResult['PROPERTIES']['NAME_LAT']['VALUE'];
} else {
    $name = $arResult['PROPERTIES']['NAME_LAT']['VALUE'];
    $nameAlt = $arResult['NAME'];
}
$name = $name ?? $nameAlt;

$nameFull = CFormat::formatPlantTitle(
    !empty($itemProperties['IS_VIEW']['VALUE']) ? '' : $name,
    $arResult['SECTION']['PATH'][0]['NAME'],
    $arResult['SECTION']['PATH'][1]['NAME']
);

$arResult['NAME_FULL'] = $nameFull;

$basePrice = ArrayHelper::getMinValue(array_column($arResult['OFFERS'], 'CATALOG_PRICE_2'));
$discountPrice = ArrayHelper::getMinValue(array_column($arResult['OFFERS'], 'CATALOG_PRICE_3'));

$minPrice = $discountPrice !== null && $discountPrice < $basePrice ? $discountPrice : $basePrice;
$height = CFormat::formatPropertyValue('HEIGHT_10', $arResult['PROPERTIES']['HEIGHT_10']['VALUE'], $arResult['PROPERTIES']['HEIGHT_10']);
$month = CFormat::formatPropertyValue('FLOWERING', $arResult['PROPERTIES']['FLOWERING']['VALUE'], $arResult['PROPERTIES']['FLOWERING']);


Meta::getInstance()->setBaseTitle($nameFull);
Meta::getInstance()->setMonth($month);
Meta::getInstance()->setHeight($height . ' м');
Meta::getInstance()->setMinPrice($minPrice);

PageSchema::setProductSchema($arResult, $APPLICATION, $this);