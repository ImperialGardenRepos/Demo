<?php
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

$APPLICATION->SetPageProperty('NOT_SHOW_NAV_CHAIN', 'Y');
$APPLICATION->IncludeComponent(
	'ig:section.list',
	'',
	[
		'ADD_SECTIONS_CHAIN' => 'N',
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_FILTER' => $arParams['CACHE_FILTER'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		'COUNT_ELEMENTS' => 'N',
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'SECTION_CODE' => '',
		'SECTION_FIELDS' => ['DESCRIPTION', 'PICTURE', ''],
		'SECTION_ID' => '',
		'SECTION_URL' => '',
		'SECTION_USER_FIELDS' => ['', ''],
		'TOP_DEPTH' => 1,
		'ADD_ELEMENTS' => 'Y',
    ]
);