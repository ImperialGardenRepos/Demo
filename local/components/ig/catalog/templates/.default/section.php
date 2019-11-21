<?php

use Bitrix\Main\Application;
use ig\Base\Registry;
use ig\Helpers\UrlHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
$request = Application::getInstance()->getContext()->getRequest();
$requestArray = $request->toArray();

$isAjax = $requestArray['IS_AJAX'] === 'Y';
$this->SetViewTarget('before_breadcrumb');

$baseUrl = UrlHelper::buildFromParts($arResult['FOLDER'], (string)$arResult['VARIABLES']['SECTION_CODE_PATH']);

if (isset($arResult['VARIABLES']['IS_VIRTUAL_FILTER']) && $arResult['VARIABLES']['IS_VIRTUAL_FILTER'] === true) {
    $baseUrl = $arResult['VARIABLES']['VIRTUAL_URL'];
}
//ToDo: section and detail templates as params
$filterParams = [
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'BASE_URL' => $baseUrl,
    'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'] ?? null,
    'SECTION_DEPTH' => $arResult['VARIABLES']['SECTION_DEPTH'] ?? null,
    'IS_AJAX_REQUEST' => $isAjax,
    'IS_FILTER' => $arResult['VARIABLES']['IS_FILTER'] ?? false,
    'IS_VIRTUAL_FILTER' => $arResult['VARIABLES']['IS_VIRTUAL_FILTER'] ?? false,
    'FILTER_ALIAS' => $requestArray['filterAlias'],

    'SPHINX_INDEX' => $arParams['SPHINX_INDEX'] ?? null,
    'FILTER_SRC' => $arParams['FILTER_SRC'] ?? null,
    'FILTER_DISPLAY_PARAMS' => $arParams['FILTER_DISPLAY_PARAMS'] ?? null,
];

$sectionParams = [
    'IS_AJAX_REQUEST' => $isAjax,
    'AJAX_MODE' => 'N',
    'AJAX_OPTION_ADDITIONAL' => '',
    'AJAX_OPTION_HISTORY' => 'N',
    'AJAX_OPTION_JUMP' => 'N',
    'AJAX_OPTION_STYLE' => 'Y',
    'DISPLAY_BOTTOM_PAGER' => 'Y',
    'DISPLAY_DATE' => 'Y',
    'DISPLAY_NAME' => 'Y',
    'DISPLAY_PICTURE' => 'Y',
    'DISPLAY_PREVIEW_TEXT' => 'Y',
    'DISPLAY_TOP_PAGER' => 'N',
    'MESSAGE_404' => '',
    'PAGER_BASE_LINK_ENABLE' => 'N',
    'PAGER_DESC_NUMBERING' => 'N',
    'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
    'PAGER_SHOW_ALL' => 'N',
    'PAGER_SHOW_ALWAYS' => 'N',
    'PAGER_TEMPLATE' => '.default',
    'PAGER_TITLE' => 'Растения',
    'SET_STATUS_404' => 'N',
    'SHOW_404' => 'N',
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'SEF_FOLDER' => $arParams['SEF_FOLDER'],
    'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES'],
    'PRICE_CODE' => $arParams['PRICE_CODE'],
    'SPHINX_INDEX' => $arParams['SPHINX_INDEX'] ?? null,
    'VARIABLES' => $arResult['VARIABLES'],
    'BASE_URL' => $baseUrl,
];

if ($isAjax === true) {
    $APPLICATION->RestartBuffer();
    $APPLICATION->IncludeComponent(
        'ig:catalog.filter',
        '',
        $filterParams,
        $this
    );

    $filterHTML = ob_get_clean();
    ob_start();
    $APPLICATION->IncludeComponent(
        'ig:catalog.section',
        '',
        $sectionParams,
        $this
    );
    $sectionHTML = ob_get_clean();
    try {
        $resultLink = Registry::getInstance()->get('CATALOG_FILTER_RESULT_LINK');
    } catch (InvalidArgumentException $exception) {
        $resultLink = UrlHelper::buildFromParts($arResult['FOLDER'], (string)$arResult['VARIABLES']['SECTION_CODE_PATH']);
    }
    header('Content-Type: application/json');
    $APPLICATION->RestartBuffer();
    echo json_encode([
        'filter_html' => $filterHTML,
        'page_url' => $resultLink,
        'results_html' => $sectionHTML,
    ]);
    exit();
}

$APPLICATION->IncludeComponent(
    'ig:catalog.filter',
    '',
    $filterParams,
    $this
);

$this->EndViewTarget();

$APPLICATION->IncludeComponent(
    'ig:catalog.section',
    '',
    $sectionParams,
    $this
);