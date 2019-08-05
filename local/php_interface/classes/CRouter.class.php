<?
namespace ig;

class CRouter {
	private static $strCatalogBaseUrl = '/katalog/rasteniya/';

	
	public static function guessCatalogGardenPath($folder404, $arUrlTemplates, &$arVariables, $requestURL = false) {
		\Bitrix\Main\Loader::includeModule('iblock');
		
		$strComponentPage = 'sections';
		
		if (!isset($arVariables) || !is_array($arVariables))
			$arVariables = array();
		
		if ($requestURL === false)
			$requestURL = \Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPageDirectory();
		
		$folder404 = str_replace("\\", "/", $folder404);
		if ($folder404 != "/")
			$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
		
		//SEF base URL must match curent URL (several components on the same page)
		if(strpos($requestURL.'/', $folder404) !== 0)
			return false;
		
		$currentPageUrl = substr($requestURL, strlen($folder404));
		$arPathParts = parse_url($currentPageUrl);
		$arPath = explode('/', $arPathParts["path"]);
		
		if(!empty($arPath[3])) {
			\CHTTP::setStatus("404 Not Found");
			return false;
		} elseif(strlen($arPath[1])>0 && $arPath[1] === $arPath[2]) {
			$strRedirectUrl = $folder404.implode('/', array_slice($arPath, 0, count($arPath)-2)).'/';
			LocalRedirect($strRedirectUrl);
		}
		
		$intIblockID = \ig\CHelper::getIblockIdByCode('catalog-garden');
		
		if(strlen($arPath[0])>0) {
			if($arSection1 = \Bitrix\Iblock\SectionTable::getList(array(
				"filter" => array(
					"IBLOCK_ID" => $intIblockID,
					"DEPTH_LEVEL" => 1,
					"=CODE" => $arPath[0]
				),
				"select" => array("ID", "CODE")
			)) -> fetch()) {
				$strComponentPage = 'section';
				$arVariables["SECTION_CODE"] = $arSection1["CODE"];
				$arVariables["SECTION_ID"] = $arSection1["ID"];
				$arVariables["SECTION_CODE_PATH"] = $arSection1["CODE"];
				
				if(strlen($arPath[1])>0) {
					if($arSection2 = \Bitrix\Iblock\SectionTable::getList(array(
						"filter" => array(
							"IBLOCK_ID" => $intIblockID,
							"DEPTH_LEVEL" => 2,
							"ACTIVE" => "Y",
							"IBLOCK_SECTION_ID" => $arSection1["ID"],
							"=CODE" => $arPath[1]
						),
						"select" => array("ID", "CODE")
					)) -> fetch()) {
						$strComponentPage = 'section';
						$arVariables["SECTION_CODE"] = $arSection2["CODE"];
						$arVariables["SECTION_ID"] = $arSection2["ID"];
						$arVariables["SECTION_CODE_PATH"] = $arSection1["CODE"].'/'.$arSection2["CODE"];
						
						if(strlen($arPath[2])>0) {
							if($arProduct = \Bitrix\Iblock\ElementTable::getList(array(
								"filter" => array(
									"IBLOCK_ID" => $intIblockID,
									"ACTIVE" => "Y",
									"IBLOCK_SECTION_ID" => $arSection2["ID"],
									"=CODE" => $arPath[2]
								),
								"select" => array("ID", "CODE")
							)) -> fetch()) {
								$strComponentPage = 'element';
								$arVariables["ELEMENT_CODE"] = $arProduct["CODE"];
								$arVariables["ELEMENT_ID"] = $arProduct["ID"];
							} else {
								$arVariables["ELEMENT_ID"] = -1;
							}
						}
					} else {
						$arVariables["SECTION_ID"] = -1;
					}
				}
			}
		}
		
		return $strComponentPage;
	}

	public static function guessCatalogPath($folder404, $arUrlTemplates, &$arVariables, $requestURL = false) {
        \Bitrix\Main\Loader::includeModule('iblock');

        $strComponentPage = 'sections';

        if (!isset($arVariables) || !is_array($arVariables))
            $arVariables = array();

        if ($requestURL === false)
            $requestURL = \Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPageDirectory();

        $folder404 = str_replace("\\", "/", $folder404);
        if ($folder404 != "/")
            $folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";

        //SEF base URL must match curent URL (several components on the same page)
        if(strpos($requestURL.'/', $folder404) !== 0)
            return false;

        $currentPageUrl = substr($requestURL, strlen($folder404));
        $arPathParts = parse_url($currentPageUrl);
        $arPath = explode('/', $arPathParts["path"]);

        if(!empty($arPath[3])) {
            \CHTTP::setStatus("404 Not Found");
            return false;
        } elseif(strlen($arPath[1])>0 && $arPath[1] === $arPath[2]) {
            $strRedirectUrl = $folder404.implode('/', array_slice($arPath, 0, count($arPath)-2)).'/';
            LocalRedirect($strRedirectUrl);
        }

        $intIblockID = \ig\CHelper::getIblockIdByCode('catalog');

        if(strlen($arPath[0])>0) {
            if($arSection1 = \Bitrix\Iblock\SectionTable::getList(array(
                "filter" => array(
                    "IBLOCK_ID" => $intIblockID,
                    "DEPTH_LEVEL" => 1,
                    "=CODE" => $arPath[0]
                ),
                "select" => array("ID", "CODE")
            )) -> fetch()) {
                $strComponentPage = 'section';
                $arVariables["SECTION_CODE"] = $arSection1["CODE"];
                $arVariables["SECTION_ID"] = $arSection1["ID"];
                $arVariables["SECTION_CODE_PATH"] = $arSection1["CODE"];

                if(strlen($arPath[1])>0) {
                    if($arSection2 = \Bitrix\Iblock\SectionTable::getList(array(
                        "filter" => array(
                            "IBLOCK_ID" => $intIblockID,
                            "DEPTH_LEVEL" => 2,
                            "ACTIVE" => "Y",
                            "IBLOCK_SECTION_ID" => $arSection1["ID"],
                            "=CODE" => $arPath[1]
                        ),
                        "select" => array("ID", "CODE")
                    )) -> fetch()) {
                        $strComponentPage = 'section';
                        $arVariables["SECTION_CODE"] = $arSection2["CODE"];
                        $arVariables["SECTION_ID"] = $arSection2["ID"];
                        $arVariables["SECTION_CODE_PATH"] = $arSection1["CODE"].'/'.$arSection2["CODE"];

                        if(strlen($arPath[2])>0) {
                            if($arProduct = \Bitrix\Iblock\ElementTable::getList(array(
                                "filter" => array(
                                    "IBLOCK_ID" => $intIblockID,
                                    "ACTIVE" => "Y",
                                    "IBLOCK_SECTION_ID" => $arSection2["ID"],
                                    "=CODE" => $arPath[2]
                                ),
                                "select" => array("ID", "CODE")
                            )) -> fetch()) {
                                $strComponentPage = 'element';
                                $arVariables["ELEMENT_CODE"] = $arProduct["CODE"];
                                $arVariables["ELEMENT_ID"] = $arProduct["ID"];
                            } else {
                                $arVariables["ELEMENT_ID"] = -1;
                            }
                        }
                    } else {
                        $arVariables["SECTION_ID"] = -1;
                    }
                }
            }
        }
        return $strComponentPage;
	}
	
	public static function getUrlInfo($strUrl = '') {
		$arResult = array();
		
		if(empty($strUrl)) {
			$strUrl = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getRequestedPageDirectory();
		}
		
		// search seo pages here
		if(false) {
			$arResult = array(
				"FOLDER" => self::$strCatalogBaseUrl,
				"VARIABLES" => array(
					"SEO_URL" => '',
					"SEO_ID" => 12
				)
			);
		}
		
		return $arResult;
	}
	
	public static function getCatalogBaseUrl() {
		return self::$strCatalogBaseUrl;
	}
	
	public static function getCatalogGroupPageUrl($arGroup) {
		return self::getCatalogBaseUrl().$arGroup["UF_CODE"].'/';
	}
}