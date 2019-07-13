<?php
namespace ig;

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("\\ig\\CIblockEvents", "OnBeforeIBlockElementAddHandler"), 100);
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("\\ig\\CIblockEvents", "OnBeforeIBlockElementAddOrUpdateHandler"), 1000);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("\\ig\\CIblockEvents", "OnBeforeIBlockElementUpdateHandler"), 100);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("\\ig\\CIblockEvents", "OnBeforeIBlockElementAddOrUpdateHandler"), 1000);
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("\\ig\\CIblockEvents", "onBeforeIBlockElementDeleteHandler"));

AddEventHandler('main','OnAdminTabControlBegin',array('\\ig\\CIblockEvents', 'RemoveYandexDirectTab'));

AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("\\ig\\CIblockEvents", "onBeforeIBlockSectionAddOrUpdateHandler"));
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", Array("\\ig\\CIblockEvents", "onBeforeIBlockSectionUpdateHandler"), 100);
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", Array("\\ig\\CIblockEvents", "onBeforeIBlockSectionAddOrUpdateHandler"), 1000);

AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("\\ig\\CIblockEvents", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("\\ig\\CIblockEvents", "OnAfterIBlockElementUpdateHandler"));


// sphinx events
\ig\sphinx\CCatalogOffers::initEvents();
\ig\sphinx\CCatalogGardenOffers::initEvents();

// image resize
\ig\CImageResize::initEvents();


class CIblockEvents {
	function OnAfterIBlockElementUpdateHandler(&$arFields) {
		if(
			$arFields["RESULT"] && $arFields["ID"]>0
		) {
			if(
				$arFields["IBLOCK_ID"]==\ig\CHelper::getIblockIdByCode("offers")
					||
				$arFields["IBLOCK_ID"]==\ig\CHelper::getIblockIdByCode("catalog")
			) {
				\ig\CHelper::setHeightFilterPropertyValue($arFields["ID"]);
			}
		}
	}
	
	function OnAfterIBlockElementAddHandler(&$arFields) {
		if(
			$arFields["ID"]>0
		) {
			if(
				$arFields["IBLOCK_ID"]==\ig\CHelper::getIblockIdByCode("offers")
					||
				$arFields["IBLOCK_ID"]==\ig\CHelper::getIblockIdByCode("catalog")
			) {
				\ig\CHelper::setHeightFilterPropertyValue($arFields["ID"]);
			}
		}
	}
	
	function OnBeforeIBlockElementDeleteHandler($ID)
	{
		$rsI = \CIBlockElement::GetList(false, array(
			"ID" => $ID
		), false, false, array(
			"ID", "IBLOCK_ID"
		));
		if($arI = $rsI->Fetch()) {
			if(
				$arI["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers")
					||
				$arI["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")
			) {
				$arGroups = $GLOBALS["USER"] -> GetUserGroupArray();
				if(in_array(12, $arGroups)) {
					global $APPLICATION;
					$APPLICATION->throwException("Запрещено удалять товары и товарные предложения");
					return false;
				}
			} elseif(
				$arI["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers-garden")
				||
				$arI["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog-garden")
			) {
				$arGroups = $GLOBALS["USER"] -> GetUserGroupArray();
				if(in_array(13, $arGroups)) {
					global $APPLICATION;
					$APPLICATION->throwException("Запрещено удалять товары и товарные предложения");
					return false;
				}
			}
		}
	}
	
	function onBeforeIBlockSectionUpdateHandler(&$arFields) {
		if($arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
			if(!$GLOBALS["USER"] -> IsAdmin()) {
				$intID = $arFields["ID"];
				$rsSec = \CIBlockSection::GetList(false, array("ID"=>$intID), false, array("IBLOCK_SECTION_ID"));
				if($arSec = $rsSec -> Fetch()) {
					if($arFields["IBLOCK_SECTION_ID"] != $arSec["IBLOCK_SECTION_ID"]) {
						global $APPLICATION;
						$APPLICATION->throwException("Вы не можете переносить разделы каталога. Обратитесь к администратору.");
						
						return false;
					}
				}
			}
		}
	}
	
	function onBeforeIBlockSectionAddOrUpdateHandler(&$arFields) {
		if($arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
			if(strlen($arFields["NAME"])>0) {
				$arFilter = array(
					"IBLOCK_ID" => $arFields["IBLOCK_ID"],
					"NAME"      => $arFields["NAME"],
				);
				
				if(isset($arFields["IBLOCK_SECTION_ID"])) {
					$arFilter["SECTION_ID"] = $arFields["IBLOCK_SECTION_ID"];
				}
				
				$rsSec = \CIBlockSection::GetList(false, $arFilter, false, array("ID"));
				if($arSec = $rsSec->Fetch()) {
					if($arSec["ID"] != $arFields["ID"]) {
						global $APPLICATION;
						echo __FILE__.': '.__LINE__.'<pre>'.print_r($arFilter, true).'</pre>';
						$APPLICATION->throwException("Нельзя сохранять разделы с одинаковым названием");
						
						return false;
					}
				}
			}
		}
	}
	
	function RemoveYandexDirectTab(&$TabControl){
		if ($GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/iblock_element_edit.php') {
			foreach($TabControl->tabs as $Key => $arTab){
				if($arTab['DIV']=='seo_adv_seo_adv') {
					unset($TabControl->tabs[$Key]);
				}
			}
		}
	}
	
	function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{
		if(
			$arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")
		) {
			if(\ig\CHelper::isUserContentManager() && strpos($_SERVER["REQUEST_URI"], '/bitrix/admin/iblock_list_admin.php') !== false) {
				global $APPLICATION;
				$APPLICATION->throwException("Вы не можете сохранять сорта в списке. Воспользуйтеь страницей редактированя элементеов.");
				return false;
			}
		}
	}
	
	function OnBeforeIBlockElementAddHandler(&$arFields)
	{
		if($arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
			if(!$GLOBALS["USER"] -> IsAdmin()) {
				global $APPLICATION;
				$APPLICATION->throwException("Вы не можете создавать сорта. Обратитесь к администратору.");
				return false;
			}
		}
	}
	
	function OnBeforeIBlockElementAddOrUpdateHandler(&$arFields)
	{
		if($arFields["IBLOCK_ID"]==\ig\CHelper::getIblockIdByCode("news")) {
		      \ig\Etc\CNews::setItemSection($arFields);
		} elseif($arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
			$intSectionID = \ig\CHelper::getFirst($arFields["IBLOCK_SECTION"]);
			if($intSectionID>0) {
				$rsSec = \CIBlockSection::GetList(false, array("IBLOCK_ID"=> $arFields["IBLOCK_ID"], "ID" => $intSectionID), false, array("NAME", "UF_NAME_LAT"));
				if($arSec = $rsSec -> Fetch()) {
					if(strpos($arSec["NAME"], "hybr") !== false || strpos($arSec["UF_NAME_LAT"], "hybr") !== false) {
						$intIsView = \ig\CHelper::getFirst($arFields["PROPERTY_VALUES"][\ig\CHelper::getPropertyIDByCode("IS_VIEW")])["VALUE"];
						if($intIsView>0) {
							global $APPLICATION;
							$APPLICATION->throwException("Гибридный вид не может быть самостоятельным видом");
							return false;
						}
					}
				}
			}
			
			// проверяем на дубль
			$arFilter = array(
				"IBLOCK_ID" => $arFields["IBLOCK_ID"],
				'NAME' => $arFields["NAME"],
				"CODE" => $arFields["CODE"],
				"SECTION_ID" => $intSectionID,
				"PROPERTY_NAME_LAT" => \ig\CHelper::getFirst($arFields["PROPERTY_VALUES"][\ig\CHelper::getPropertyIDByCode("NAME_LAT")])["VALUE"]
			);
			
			$rsI = \CIBlockElement::GetList(false, $arFilter, false, array("nTopCount"=>1), array("ID"));
			if($arI = $rsI -> Fetch()) {
				if($arFields["ID"] != $arI["ID"]) {
					$arError[] = 'Запрещено сохранять дубли элементов';
				}
			}
		} elseif($arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers")) {
			$bSkipEvent = false;
			
			$oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
			
			$intOffersIblockID = \ig\CHelper::getIblockIdByCode('offers');
			if (
				$oRequest->get('IBLOCK_ID')==$intOffersIblockID
				&&
				(
					$oRequest->get('action_button_tbl_iblock_element_'.md5("catalog.".$intOffersIblockID))=='restore_default_name'
					||
					$oRequest->get('action_button_tbl_iblock_list_'.md5("catalog.".$intOffersIblockID))=='restore_default_name'
				
				)
			) {
				$bSkipEvent = true;
			}
			
			if(!$bSkipEvent && false) {
				$arError = array();
				
				$arErrorText = array(
					23 => 'Обхват ствола не может быть 0',
					24 => 'Ширина кроны не может быть 0',
					25 => 'Высота штамба до начала кроны не может быть 0',
					26 => 'Высота штамба до места прививки не может быть 0'
				);
				
				foreach ($arErrorText as $intPropertyID => $strErrorText) {
					foreach ($arFields["PROPERTY_VALUES"][$intPropertyID] as $intCnt => $arValue) {
						$strValue = $arValue["VALUE"];
						
						if(strlen($strValue)>0) {
							$intValue = intval($strValue);
							if($intValue == 0) {
								$arError[] = $arErrorText[$intPropertyID];
							}
						}
					}
				}
				
				// проверяем на дубль
				$arFilter = array(
					"IBLOCK_ID" => $arFields["IBLOCK_ID"],
					'NAME'      => $arFields["NAME"]
				);
				
				foreach ($arFields["PROPERTY_VALUES"] as $intPropertyID => $propValues) {
					foreach ($propValues as $strValue) {
						$arFilter["PROPERTY_".$intPropertyID] = $strValue;
					}
				}
				
				$rsI = \CIBlockElement::GetList(false, $arFilter, false, array("nTopCount" => 1), array("ID"));
				if($arI = $rsI->Fetch()) {
					if($arFields["ID"] != $arI["ID"]) {
						$arError[] = 'Запрещено сохранять товарные позиции с полностью идентичными свойствами';
					}
				}
				
				if(!empty($arError)) {
					global $APPLICATION;
					$APPLICATION->throwException(implode("<br>", $arError));
					
					return false;
				}
			}
		}
	}
}