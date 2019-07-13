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

$arParams["SET_LAST_MODIFIED"] = $arParams["SET_LAST_MODIFIED"]==="Y";
$arParams["CATALOG_PRICE_ID"] = \ig\CRegistry::get("CATALOG_BASE_PRICE_ID");
$arParams["CATALOG_OLD_PRICE_ID"] = \ig\CRegistry::get("CATALOG_OLD_PRICE_ID");

Bitrix\Main\Loader::includeModule('iblock');
Bitrix\Main\Loader::includeModule('sale');

$this -> prepareCartData();


if(!empty($arResult["CART"])) {
	$this -> prepareGroupData();
	$arResult["FAVORITE"] = \ig\CFavorite::getInstance() -> getFavorite();
	
	// get offers
	$arSortID = array();
	$arOffersSelect = array(
		"*", "PROPERTY_*", "CATALOG_GROUP_".$arParams["CATALOG_PRICE_ID"], "CATALOG_GROUP_".$arParams["CATALOG_OLD_PRICE_ID"]
	);
	$arTmpItems = array();
	$rsO = CIBlockElement::GetList(Array("CATALOG_PRICE_".$arParams["CATALOG_PRICE_ID"] => "ASC"), array(
		"ACTIVE"             => "Y",
		"IBLOCK_ID"          => \ig\CHelper::getIblockIdByCode('offers'),
		"ID" => array_keys($arResult["CART"])
	), false, false, $arOffersSelect);
	
	while ($obO = $rsO->GetNextElement()) {
		$arO = $obO -> GetFields();
		$arO["PROPERTIES"] = $obO -> GetProperties();
		
		if($arO["PROPERTIES"]["CML2_LINK"]["VALUE"]>0) {
			$arSortID[] = $arO["PROPERTIES"]["CML2_LINK"]["VALUE"];
		}
		
		$arTmpItems[$arO["ID"]] = $arO;
	}
	
	foreach($arResult["CART"] as $intID => $arCartData) {
		if(isset($arTmpItems[$intID])) {
			$arResult["ITEMS"][] = $arTmpItems[$intID];
		}
	}
	unset($arTmpItems);
	
	if(!empty($arSortID)) {
		$rsI = CIBlockElement::GetList(Array("NAME" => "ASC", "ID" => "DESC"), array(
			"ACTIVE" => "Y",
			"ID" => $arSortID,
			"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog')), false, $arNavParams, array(
			"*", "PROPERTIES_*"
		));
		while ($obI = $rsI->GetNextElement()) {
			$arI = $obI->GetFields();
			$arI["PROPERTIES"] = $obI->GetProperties();
			
			$arResult["SORT"][$arI["ID"]] = $arI;
			
			$arResult["SECTIONS"][$arI["IBLOCK_SECTION_ID"]] = array();
		}
	}
}

if(!empty($arResult["SECTIONS"])) {
	$rsSec = CIBlockSection::GetList(Array(), array("ACTIVE"=>"Y", "IBLOCK_ID"=> \ig\CHelper::getIblockIdByCode('catalog'), "ID" => array_keys($arResult["SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL"));
	while($arSec = $rsSec -> GetNext()) {
		$rsNav = CIBlockSection::GetNavChain(\ig\CHelper::getIblockIdByCode('catalog'), $arSec["ID"]);
		while($arNav = $rsNav -> GetNext()) {
			$arSec["NAV"][] = $arNav;
		}
		
		$arResult["SECTIONS"][$arSec["ID"]] = $arSec;
	}
}

if($arParams["IS_AJAX"]) {
	$APPLICATION->RestartBuffer();
}

if($_REQUEST["action"] == 'getOrderFloatBar') {
	$strTemplate = 'floatBar.php';
} else {
	$strTemplate = '';
}
echo __FILE__.': '.__LINE__.'<pre>'.print_r($strTemplate, true).'</pre>';
$this->includeComponentTemplate($strTemplate);

if($arParams["IS_AJAX"]) {
	die();
}