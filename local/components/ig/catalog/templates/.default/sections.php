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

$this->SetViewTarget('before_breadcrumb');
$APPLICATION->IncludeComponent(
	'bitrix:main.include',
	'',
	Array(
		'AREA_FILE_RECURSIVE' => 'Y',
		'AREA_FILE_SHOW' => 'sect',
		'AREA_FILE_SUFFIX' => 'before_breadcrumb',
		'EDIT_TEMPLATE' => ''
	)
);
$this->EndViewTarget();

$APPLICATION->SetPageProperty('NOT_SHOW_NAV_CHAIN', 'Y');

$APPLICATION->IncludeComponent(
	'ig:catalog.section',
	'',
	Array(
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
		'PAGER_TITLE' => 'Новости',
		'SET_STATUS_404' => 'N',
		'SHOW_404' => 'N'
	)
);