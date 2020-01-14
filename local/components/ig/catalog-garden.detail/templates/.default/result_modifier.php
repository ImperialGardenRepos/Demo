<?php

use ig\Helpers\ArrayHelper;
use ig\Seo\Meta;
use ig\Seo\PageSchema;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$basePrice = ArrayHelper::getMinValue(array_column($arResult['OFFERS'], 'CATALOG_PRICE_2'));
$discountPrice = ArrayHelper::getMinValue(array_column($arResult['OFFERS'], 'CATALOG_PRICE_3'));

$minPrice = $discountPrice !== null && $discountPrice < $basePrice ? $discountPrice : $basePrice;


Meta::getInstance()->setBaseTitle($arResult['NAME']);
Meta::getInstance()->setMinPrice($minPrice);

PageSchema::setProductSchema($arResult, $APPLICATION, $this);