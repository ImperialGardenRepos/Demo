<?
namespace ig;

AddEventHandler("main", "OnBuildGlobalMenu", array("\\ig\\CAdminEvents", "OnBuildGlobalMenuAddon"));

AddEventHandler("main", "OnAdminListDisplay", array("\\ig\\CAdminEvents", "onAdminListDisplay"));

AddEventHandler("main", "OnBeforeProlog", array("\\ig\\CAdminEvents", "processGroupActions"));

AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", array("\\ig\\CAdminEvents", "checkRights"));
AddEventHandler("iblock", "OnBeforeIBlockSectionDelete", array("\\ig\\CAdminEvents", "checkRights"));
AddEventHandler("main", "OnProlog", array('\\ig\\CAdminEvents', "onPrologHandler"), 50);
AddEventHandler("main", "OnAfterUserLogin", Array('\\ig\\CAdminEvents', "OnAfterUserLoginHandler"));

AddEventHandler("main", "OnEndBufferContent", array("\\ig\\CAdminEvents", "OnEndBufferContentHandler"));


class CAdminEvents {
	function OnEndBufferContentHandler(&$strContent) {
		if($GLOBALS["APPLICATION"] -> GetCurPage() == '/bitrix/admin/adv_banner_edit.php') {
			preg_match('#<input[^<>]*name="IMAGE_ALT"[^>]*value="(.*?)">#ium', $strContent, $arM);
			
			$strContent = str_replace($arM[0], '<textarea style="width: 90%; height: 60px;" name="IMAGE_ALT">'.$arM[1].'</textarea>', $strContent);
		}
	}
	
	function OnAfterUserLoginHandler(&$fields) {
		if(strpos($GLOBALS["APPLICATION"] -> GetCurPage(), '/bitrix/admin/') !== false) { // админка закрыта на обслуживание
			if(!$GLOBALS["USER"] -> IsAdmin()) {
				$strIsAdminPartClosed = \Bitrix\Main\Config\Option::get("grain.customsettings", "close_admin_part");
				if($strIsAdminPartClosed == 'Y') {
					$strText = \Bitrix\Main\Config\Option::get("grain.customsettings", "close_admin_part_text");
					if(strlen($strText) == 0) {
						$strText = 'Административная зона закрыта на обслуживание. Свяжитесь с администратором сайта.';
					}
					
					$GLOBALS["USER"]->Logout();
					$fields['RESULT_MESSAGE'] = array("TYPE" => "ERROR", "MESSAGE" => $strText);
				}
			}
		}
	}
	
	function OnBuildGlobalMenuAddon(&$aGlobalMenu, &$aModuleMenu)
	{
		global $USER;
		if($USER->IsAdmin()) {
			foreach ($aModuleMenu as $intKey => $arMenuItem) {
				if($arMenuItem["parent_menu"] == 'global_menu_settings' && $arMenuItem["section"] == 'search') {
					$aModuleMenu[$intKey]["items"][] = array(
						"text"     => "Переиндексация sphinx",
						"url"      => "catalog_sphinx_reindex.php",
						"more_url" => array(
							"catalog_sphinx_reindex.php"
						),
						"title"    => "Переиндексация каталога sphinx"
					);
				}
			}
		}
		
		foreach($aModuleMenu as $intKey => $arItem) {
			if(
				$arItem["parent_menu"] == "global_menu_content"
					&&
				$arItem["title"] == 'Настройка информационных блоков'
			) {
				if(\ig\CHelper::isUserContentManager()) {
					unset($aModuleMenu[$intKey]);
				}
			}
		}
	}
	
	function processGroupActions() {
		$oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
		
		if ( $oRequest->isAdminSection() )
		{
			\CModule::IncludeModule("iblock");
			
			$intOffersIblockID = \ig\CHelper::getIblockIdByCode('offers');
			if (
				$oRequest->get('IBLOCK_ID')==$intOffersIblockID
					&&
				(
					$oRequest->get('action_button_tbl_iblock_element_'.md5("catalog.".$intOffersIblockID))=='restore_default_name'
						||
					$oRequest->get('action_button_tbl_iblock_list_'.md5("catalog.".$intOffersIblockID))=='restore_default_name'
					
				)
			)
			{
				$arID = $oRequest->get('ID');
				
				if(is_array($arID) && !empty($arID)) {
					$arTmpID = array();
					foreach($arID as $intID) {
						if(strpos($intID, 'E') === 0) { // если совместный просмотр разделов и элементов
							$intID = substr($intID, 1);
						}
						$intID = intval($intID);
						
						if($intID>0) {
							$arTmpID[] = $intID;
						}
					}
					
					if(!empty($arTmpID)) {
						$rsI = \CIBlockElement::GetList(false, array(
							"IBLOCK_ID" =>$intOffersIblockID,
							"ID" => $arTmpID
						), false, false, array(
							"ID", "IBLOCK_ID", "PROPERTY_CML2_LINK"
						));
						while ($arI = $rsI->Fetch()) {
							if($arI["PROPERTY_CML2_LINK_VALUE"]>0) {
								$strDefaultOfferName = \ig\CHelper::getOfferName($arI["PROPERTY_CML2_LINK_VALUE"]);
								if(strlen($strDefaultOfferName) > 0) {
									$obElement = new \CIBlockElement;
									$obElement->Update($arI["ID"], array("NAME" => $strDefaultOfferName));
								}
							}
						}
					}
				}
			}
		}
	}
	
	function onPrologHandler()
	{
		// админка закрыта на обслуживание
		if(strpos($GLOBALS["APPLICATION"] -> GetCurPage(), '/bitrix/admin/') !== false && $GLOBALS["USER"] -> IsAuthorized()) {
			$strIsAdminPartClosed = \Bitrix\Main\Config\Option::get("grain.customsettings", "close_admin_part");
			
			if($strIsAdminPartClosed == 'Y') {
				if(!$GLOBALS["USER"]->IsAdmin()) {
					$GLOBALS["USER"] -> Logout();
					LocalRedirect('/bitrix/admin/');
				}
			}
		}
		
		if($GLOBALS["APPLICATION"] -> GetCurPage() == '/bitrix/admin/iblock_element_admin.php' && $_REQUEST["IBLOCK_ID"]==1) {
			$GLOBALS["APPLICATION"]->SetAdditionalCSS("/local/css/admin/common_admin_styles.css");
		}
	}
	
	function checkRights($sectionData) {
		if($_REQUEST["IBLOCK_ID"] == CHelper::getIblockIdByCode('catalog')) {
			$arGroups = $GLOBALS["USER"]->GetUserGroupArray();
			if(in_array(12, $arGroups)) {
				$GLOBALS["APPLICATION"]->throwException("Запрещено сохранять рода и виды");
				return false;
			}
		}
	}
	
	function onAdminListDisplay(&$list) {
		// скрываем столбцы свойств, которые не привязаны к группе растений -
		
		if(
			$list -> table_id == "tbl_iblock_list_".md5("catalog.1")
				||
			$list -> table_id == "tbl_iblock_element_".md5("catalog.1")
		) {
			$filterOption = new \Bitrix\Main\UI\Filter\Options($list->table_id);
			$filterData = $filterOption->getFilter($filterFields);
			
			$intGroupPropertyID = CHelper::getPropertyIDByCode('GROUP');
			$strGroupPropertyCode = "PROPERTY_".$intGroupPropertyID;
			if($filterData["FILTER_APPLIED"] == 1 && is_array($filterData[$strGroupPropertyCode]) && count($filterData[$strGroupPropertyCode])>0) {
				$arGroupID = array();
				foreach($filterData[$strGroupPropertyCode] as $strValue) {
					$arGroupID[] = CHighload::getIDByXmlID($strValue, CHelper::getHLGroupID());
				}
				
				if(!empty($arGroupID)) {
					$arPropGroups = CHighload::getList(CHelper::getHLPropertyGroupID(), array("UF_ACTIVE" => 1));
					foreach($arPropGroups as $arPropGroup) {
						$bFound = false;
						foreach($arGroupID as $intGroupID) {
							if(in_array($intGroupID, $arPropGroup["UF_POINTER"])) {
								$bFound = true;
								break;
							}
						}
						
						if(!$bFound) {
							$intIblockPropertyID = CHelper::getPropertyIDByCode($arPropGroup["UF_CODE"]);
							if($intIblockPropertyID>0) {
								unset($list->aHeaders["PROPERTY_".$intIblockPropertyID]);
							}
						}
					}
				}
				
			}
		} elseif(
			$list -> table_id == "tbl_iblock_list_".md5("catalog.2")
				||
			$list -> table_id == "tbl_iblock_element_".md5("catalog.2")
		) {
			$arActions = $list->arActions;
			
			$arActions['restore_default_name'] = 'Установить названия на основе каталога растений';
			
			$list->AddGroupActionTable($arActions);
		}
	}
}