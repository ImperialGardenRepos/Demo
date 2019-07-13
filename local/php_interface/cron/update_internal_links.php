<?php
if(empty($_SERVER["DOCUMENT_ROOT"])) {
	$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/php_interface/cron', '', __DIR__);
}

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}

class skInternalLinks {
	function getParamFilter($arI, $intCount = 0, $arProduct = array()) {
		
		$arFilter = array("NOT_CAT_ID" => array($arI["ID"]));
		foreach($arProduct as $intID => $arFoo) {
			$arFilter["NOT_CAT_ID"][] = $intID;
		}
		
		if($intCount<5) {
			if(!empty($arI["PROPERTIES"]["USAGE"]["VALUE"])) {
				$arFilter["PROPERTY_USAGE"] = array();
				foreach($arI["PROPERTIES"]["USAGE"]["VALUE"] as $strValue) {
					$arFilter["PROPERTY_USAGE"][] = crc32($strValue);
				}
			}
		}
		
		if($intCount<4) {
			if(!empty($arI["PROPERTIES"]["GROUP"]["VALUE"])) {
				$arFilter["PROPERTY_GROUP"] = $arI["PROPERTIES"]["GROUP"]["VALUE"];
			}
		}
		
		if($intCount<3) {
			if(!empty($arI["PROPERTIES"]["CROWN"]["VALUE"]) || !empty($arI["PROPERTIES"]["HAIRCUT_SHAPE"]["VALUE"])) {
				$arTmpFilter = array(
					"LOGIC" => "OR"
				);
				
				if(!empty($arI["PROPERTIES"]["CROWN"]["VALUE"])) {
					$arTmpFilter["PROPERTY_CROWN"] = $arI["PROPERTIES"]["CROWN"]["VALUE"];
				}
				
				if(!empty($arI["PROPERTIES"]["HAIRCUT_SHAPE"]["VALUE"])) {
					$arTmpFilter["PROPERTY_HAIRCUT_SHAPE"] = $arI["PROPERTIES"]["HAIRCUT_SHAPE"]["VALUE"];
				}
				
				$arFilter[] = $arTmpFilter;
				
				unset($arTmpFilter);
			}
		}
		
		if($intCount<2) {
			if(!empty($arI["PROPERTIES"]["COLOR_FLOWER"]["VALUE"])) {
				$arFilter["PROPERTY_COLOR_FLOWER"] = array();
				foreach($arI["PROPERTIES"]["COLOR_FLOWER"]["VALUE"] as $strValue) {
					$arFilter["PROPERTY_COLOR_FLOWER"][] = crc32($strValue);
				}
			}
			
			if(!empty($arI["PROPERTIES"]["COLOR_LEAF"]["VALUE"])) {
				$arFilter["PROPERTY_COLOR_LEAF"] = array();
				foreach($arI["PROPERTIES"]["COLOR_LEAF"]["VALUE"] as $strValue) {
					$arFilter["PROPERTY_COLOR_LEAF"][] = crc32($strValue);
				}
			}
		}
		
		if($intCount<1) {
			if(!empty($arI["PROPERTIES"]["HEIGHT_10_EXT"]["VALUE"])) {
				$arFilter["PROPERTY_HEIGHT_10"] = $arI["PROPERTIES"]["HEIGHT_10"]["VALUE"];
			}
		}
		
		return $arFilter;
	}
	
	function getSimilarProduct($arI, $arParams = array()) {
		$arParams["COUNT"] = intval($arParams["COUNT"]);
		if($arParams["COUNT"]<=0) $arParams["COUNT"] = 4;
		
		$obSearch = new \ig\sphinx\CCatalogOffers();
		
		$arProduct = array();
		$arTmp = array();
		
		$bPriorityFilter = true;
		$intCnt = 0;
		for($i=0; $i<5; $i++) {
			$arFilter = $this -> getParamFilter($arI, $i, $arProduct);
			
			if($bPriorityFilter) {
				$arFilter[">PROPERTY_PRIORITY"] = 0;
			}
			
			$arSearchParams = array(
				"SELECT" => array("CAT_ID", "ID", "NAME", "PROPERTY_PRIORITY"),
				"FILTER" => $arFilter,
				"ORDER" => array("PROPERTY_PRIORITY" => "DESC", "CATALOG_BASE_PRICE" => "ASC"),
				"GROUP" => array("CAT_ID"),
				"LIMIT" => $arParams["COUNT"],
				"MAX_MATCHES" => $arParams["COUNT"]
			);
			
			$rsSearch = $obSearch -> search($arSearchParams);
			
			while($arOffer = $obSearch->fetch($rsSearch)) {
				$arProduct[$arOffer["cat_id"]] = false;
				$arTmp[$i+1][] = $arOffer["name"].' ['.$arOffer["cat_id"].'], приоритет '.$arOffer["p_priority"];
			}
			
			if(count($arProduct)>3) break;
			if($bPriorityFilter && $i==4) {
				$bPriorityFilter = false;
				$i=0;
			}
			
			$intCnt++;
			
			if($intCnt>100) {
				echo 'emerg';
				break;
			}
		}
		
		$arProduct = array_keys($arProduct);
		$arProduct = array_slice($arProduct, 0, $arParams["COUNT"]);
		
		return $arProduct;
	}
	
	public static function clearInternalLinksData()
	{
		$intCatalogIblockID = \ig\CHelper::getIblockIdByCode('catalog');
		
		$obSection = new \CIBlockSection;
		$rsSec = \CIBlockSection::GetList(Array(), array(
			"IBLOCK_ID"         => $intCatalogIblockID,
			"!UF_INT_LINKS_CNT" => 0
		), false, array("ID", "IBLOCK_ID"));
		while ($arSec = $rsSec->Fetch()) {
			//echo __FILE__.': '.__LINE__.'<pre>'.print_r($arSec, true).'</pre>';
			$obSection->Update($arSec["ID"], array("UF_INT_LINKS_CNT" => 0));
		}
		
		
		$rsI = \CIBlockElement::GetList(Array(), array(
			"IBLOCK_ID"               => $intCatalogIblockID,
			"!PROPERTY_INT_LINKS_CNT" => 0
		), false, false, array(
			"ID",
			"IBLOCK_ID"
		));
		while ($arI = $rsI->Fetch()) {
			//echo __FILE__.': '.__LINE__.'<pre>'.print_r($arI, true).'</pre>';
			\CIBlockElement::SetPropertyValues($arI["ID"], $arI["IBLOCK_ID"], 0, "INT_LINKS_CNT");
		}
	}
}

echo 'Clearing internal links data ... ';
\skInternalLinks::clearInternalLinksData();
echo 'done<br>';


echo 'Get internal links cnt ... ';
$intCatalogIblockID = \ig\CHelper::getIblockIdByCode('catalog');
$obSimilar = new skInternalLinks();

$arResult = array();

$rsI = CIBlockElement::GetList(Array(), array(
		"ACTIVE"           => "Y",
		"IBLOCK_ID"        => $intCatalogIblockID,
		">CATALOG_PRICE_1" => 0
	)
//	, false
//	, array("nTopCount" => 20)
);

while ($obI = $rsI->GetNextElement()) {
	$arI = $obI -> GetFields();
	$arI["PROPERTIES"] = $obI -> GetProperties();
	
	// sections
	$rsNav = CIBlockSection::GetNavChain($intCatalogIblockID, $arI["IBLOCK_SECTION_ID"]);
	while($arNav = $rsNav -> Fetch()) {
		if($arNav["DEPTH_LEVEL"] == 1) {
			$arResult["ROD"][$arNav["ID"]]++;
		} elseif($arNav["DEPTH_LEVEL"] == 2) {
			if(empty($arI["PROPERTIES"]["IS_VIEW"]["VALUE"])) {
				$arResult["VID"][$arNav["ID"]]++;
			}
		}
	}
	
	// get similar data
	$arSimilarID = $obSimilar -> getSimilarProduct($arI);
	foreach($arSimilarID as $intCnt => $intID) {
		$arResult["SORT"][$intID]++;
	}
}

echo __FILE__.': '.__LINE__.'<pre>'.print_r($arResult, true).'</pre>';

echo 'done<br>';

echo 'Update internal links cnt ... ';
$obSection = new \CIBlockSection;

foreach($arResult["ROD"] as $intID => $intLinksCnt) {
	$obSection->Update($intID, array("UF_INT_LINKS_CNT" => $intLinksCnt));
}

foreach($arResult["VID"] as $intID => $intLinksCnt) {
	$obSection->Update($intID, array("UF_INT_LINKS_CNT" => $intLinksCnt));
}

foreach($arResult["SORT"] as $intID => $intLinksCnt) {
	\CIBlockElement::SetPropertyValues($intID, $intCatalogIblockID, $intLinksCnt, 'INT_LINKS_CNT');
}

echo 'done<br>';

die('eof');