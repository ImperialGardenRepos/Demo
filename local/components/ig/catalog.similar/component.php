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


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["CACHE_FILTER"] = $arParams["CACHE_FILTER"]=="Y";
if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

$arParams["TOP_COUNT"] = intval($arParams["TOP_COUNT"]);
if($arParams["TOP_COUNT"]<=0)
	$arParams["TOP_COUNT"] = 4;

if(strlen($arParams["GLOBAL_VARIABLE_NAME"])>0 && preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["GLOBAL_VARIABLE_NAME"]))
{
	$sourceProduct = $GLOBALS[$arParams["GLOBAL_VARIABLE_NAME"]];
	$arParams["HASH"] = md5(serialize($sourceProduct));
}

if(!empty($arParams["HASH"])) {
	if($this->startResultCache(false)) {
		$arResult["ITEMS"] = array();
		$arResult["SECTIONS"] = array();
		
		if(is_array($sourceProduct)) {
			$arI = $sourceProduct;
		} else {
			$sourceProduct = intval($sourceProduct);
			
			$rsI = CIBlockElement::GetList(Array(), array(
				"ACTIVE" => "Y",
				"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog'),
				"ID" => $sourceProduct
			));
			if ($obI = $rsI->GetNextElement()) {
				$arI = $obI -> GetFields();
				$arI["PROPERTIES"] = $obI -> GetProperties();
			}
		}
		
		$arResult["SORT"] = $arI;
		
		$rsVid = CIBlockSection::GetList(Array(), array("ACTIVE"=>"Y", "IBLOCK_ID"=>\ig\CHelper::getIblockIdByCode('catalog'), "ID" => $arI["IBLOCK_SECTION_ID"]), false, array("NAME", "IBLOCK_SECTION_ID", "UF_*"));
		if($arVid = $rsVid -> GetNext()) {
			$arResult["VID"] = $arVid;
			
			$rsRod = CIBlockSection::GetList(Array(), array("ACTIVE"=>"Y", "IBLOCK_ID"=>\ig\CHelper::getIblockIdByCode('catalog'), "ID" => $arVid["IBLOCK_SECTION_ID"]), false, array("NAME", "UF_*"));
			if($arRod = $rsRod -> GetNext()) {
				$arResult["ROD"] = $arRod;
			}
		}
		
		$arProductID = $this -> getProduct($arI);
		
		if(!empty($arProductID)) {
			\ig\CHelper::preparePriceData(\ig\CHelper::getIblockIdByCode('offers'), $arResult);
			
			$arOffersSelect = array(
				"*", "PROPERTY_*"
			);
			
			\ig\CHelper::addSelectFields($arOffersSelect);
			
			$arSortFilter = array(
				"ACTIVE" => "Y",
				"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog'),
				"ID" => $arProductID
			);
			
			$rsI = CIBlockElement::GetList(Array("CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID") => "ASC", "NAME" => "ASC"), $arSortFilter, false, false, array(
				"*", "PROPERTIES_*"
			));
			while ($obI = $rsI->GetNextElement()) {
				$arI = $obI -> GetFields();
				$arI["PROPERTIES"] = $obI -> GetProperties();
				
				$arI["DETAIL_PAGE_URL"] = \ig\CHelper::prepareCatalogDetailUrl($arI);
				$arResult["SECTIONS"][$arI["IBLOCK_SECTION_ID"]] = array();
				
				$rsO = CIBlockElement::GetList(Array("CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID") => "ASC"), array(
					"ACTIVE"             => "Y",
					"IBLOCK_ID"          => \ig\CHelper::getIblockIdByCode('offers'),
					"PROPERTY_CML2_LINK" => $arI["ID"]
				), false, array("nTopCount"=>1), $arOffersSelect);
				
				while ($obO = $rsO->GetNextElement()) {
					$arO = $obO -> GetFields();
					$arO["PROPERTIES"] = $obO -> GetProperties();
					
					\ig\CHelper::prepareItemPrices($arO);
					
					$arI["OFFER"] = $arO;
				}
				
				$arResult["ITEMS"][$arI["ID"]] = $arI;
			}
			
			if(!empty($arResult["SECTIONS"])) {
				$rsSec = CIBlockSection::GetList(Array(), array("ACTIVE"=>"Y", "IBLOCK_ID"=> \ig\CHelper::getIblockIdByCode('catalog'), "ID" => array_keys($arResult["SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL"));
				while($arSec = $rsSec -> GetNext()) {
					$rsNav = CIBlockSection::GetNavChain(\ig\CHelper::getIblockIdByCode('catalog'), $arSec["ID"], array("NAME", "SECTION_PAGE_URL", "ID"));
					while($arNav = $rsNav -> GetNext()) {
						$arSec["NAV"][] = $arNav;
						$arResult["IBLOCK_SECTIONS"][$arNav["ID"]] = false;
					}
					
					$arResult["SECTIONS"][$arSec["ID"]] = $arSec;
				}
				
				if(!empty($arResult["IBLOCK_SECTIONS"])) {
					$rsSecTmp = \CIBlockSection::GetList(Array(), array("IBLOCK_ID"=> \ig\CHelper::getIblockIdByCode('catalog'), "ID" => array_keys($arResult["IBLOCK_SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL", "UF_NAME_LAT"));
					while($arSecTmp = $rsSecTmp -> GetNext()) {
						$arResult["IBLOCK_SECTIONS"][$arSecTmp["ID"]] = $arSecTmp;
					}
				}
			}
		}
		
		$this->setResultCacheKeys(array(
			"PRODUCT_ID"
		));
		
		$this->includeComponentTemplate();
	}
}

return $arResult["PRODUCT_ID"];