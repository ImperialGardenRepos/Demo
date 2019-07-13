<?php
namespace ig;

class CVirtualPage extends CHighload {
	static $intHLID = 0;
	
	public static function setHLID() {
		static::$intHLID = \ig\CHighload::getHighloadBlockIDByName("VirtualPages");
	}
	
	public static function setMeta($arData) {
		global $APPLICATION;
		
		if(!empty($arData["UF_TITLE"])) {
			$APPLICATION->SetPageProperty('title', $arData["UF_TITLE"]);
		}
		
		if(!empty($arData["UF_DESCRIPTION"])) {
			$APPLICATION->SetPageProperty('description', $arData["UF_DESCRIPTION"]);
		}
		
		if(!empty($arData["UF_KEYWORDS"])) {
			$APPLICATION->SetPageProperty('keywords', $arData["UF_KEYWORDS"]);
		}
	}
	
	public static function checkVirtualPage() {
		
		if(!\Bitrix\Main\Context::getCurrent()->getRequest()->isAdminSection()) {
			$strUrl = \Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPageDirectory().'/';
			
			$arVirtualPage = self::getFirst(false, array("UF_ACTIVE"=>1, "=UF_URL"=> $strUrl));
			
			if($arVirtualPage["ID"]>0) {
				\ig\CRegistry::add("VIRT_PAGE", $arVirtualPage);
				
				if(!empty($arVirtualPage["UF_PARAMS"])) {
					parse_str($arVirtualPage["UF_PARAMS"], $arGetParams);
					\Bitrix\Main\Context::getCurrent()->getRequest()->set($arGetParams);
					
					$_REQUEST = array_merge($_REQUEST, $arGetParams);
					$_GET = array_merge($_GET, $arGetParams);
					
//					$_REQUEST = $arGetParams;
//					$_GET = $arGetParams;
				
				
				}
				
				if(strlen($arVirtualPage["UF_REAL_PAGE"])>0) {
					$strDocRoot = \Bitrix\Main\Context::getCurrent()->getServer()->getDocumentRoot();
					$strPhysPage = $strDocRoot.$arVirtualPage["UF_REAL_PAGE"];
					if(\Bitrix\Main\IO\File::isFileExists($strPhysPage)) {
						require_once ($strDocRoot."/bitrix/modules/main/include/prolog_after.php");
						\ig\CVirtualPage::setMeta($arVirtualPage);
						
						require($strPhysPage);
						
						die();
					}
				}
				
				\ig\CVirtualPage::setMeta($arVirtualPage);
			}
		}
	}
}