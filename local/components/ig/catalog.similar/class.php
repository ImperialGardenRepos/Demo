<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


class CatalogSimilar extends \CBitrixComponent {
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
	
	function getProduct($arI, $arParams = array()) {
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
}