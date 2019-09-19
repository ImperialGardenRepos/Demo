<?php

use ig\Helpers\ArrayTools;
use ig\Seo\Meta;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$basePrice = ArrayTools::getMinValue(array_column($arResult['OFFERS'], 'CATALOG_PRICE_2'));
$discountPrice = ArrayTools::getMinValue(array_column($arResult['OFFERS'], 'CATALOG_PRICE_3'));

$minPrice = $discountPrice !== null && $discountPrice < $basePrice ? $discountPrice : $basePrice;


Meta::getInstance()->setBaseTitle($arResult['NAME']);
Meta::getInstance()->setMinPrice($minPrice);
