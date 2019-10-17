<?php

use ig\Catalog\Router;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @global CMain $APPLICATION */

$routeParams = Router::guessCatalogPath($arParams);
$isFilter = $routeParams['IS_FILTER'];
$componentPage = $routeParams['TEMPLATE'];
unset($routeParams['TEMPLATE']);

$arResult = [
    'FOLDER' => $arParams['SEF_FOLDER'],
    'VARIABLES' => $routeParams,
];

$this->IncludeComponentTemplate($componentPage);