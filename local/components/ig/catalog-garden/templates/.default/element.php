<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);

$arElementParams = Array(
	"ADD_SECTIONS_CHAIN" => "Y",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_ADDITIONAL" => "",
	"AJAX_OPTION_HISTORY" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "N",
	"BROWSER_TITLE" => "",
	"CACHE_GROUPS" => "N",
	"CACHE_TIME" => "36000000",
	"CACHE_TYPE" => "A",
	'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
	'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
	'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
	'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"MESSAGE_404" => "",
	"META_DESCRIPTION" => "",
	"META_KEYWORDS" => "",
	"SET_BROWSER_TITLE" => "Y",
	"SET_CANONICAL_URL" => "Y",
	"SET_LAST_MODIFIED" => "Y",
	"SET_META_DESCRIPTION" => "Y",
	"SET_META_KEYWORDS" => "Y",
	"SET_STATUS_404" => "N",
	"SHOW_404" => "N",
	"STRICT_SECTION_CHECK" => "Y"
);
$APPLICATION->IncludeComponent(
	"ig:catalog-garden.detail",
	"",
	$arElementParams
);