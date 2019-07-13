<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

global $INTRANET_TOOLBAR;

use Bitrix\Main\Application,
	Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Main\Data\Cache;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$request = Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest());

if($this->startResultCache()) {
	$this->includeComponentTemplate();
}
