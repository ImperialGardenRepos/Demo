<?php
namespace ig\Events;

use Bitrix\Main\Context;
use CIBlockElement;
use CIBlockSection;
use CModule;
use ig\CHelper;
use ig\Highload\Redirect;

class System {
	function onPrologHandler()
	{
		if(Context::getCurrent()->getRequest()->isAdminSection()) {
			CModule::IncludeModule("iblock");
		} else {
			if(!$GLOBALS["SKIP_REDIRECT_CHECK"]) {
                Redirect::checkRedirect();
            }

			if(
				$GLOBALS["APPLICATION"] -> GetCurPage() == '/local/templates/.default/components/bitrix/main.lookup.input/iblockedit/ajax.php'
					&&
				$_REQUEST["MODE"] == "SEARCH" && $_REQUEST["IBLOCK_ID"] == 1
					&&
				$_REQUEST["TYPE"] == 'ELEMENT' && strlen($_REQUEST["search"])>0
					&&
				strpos($_SERVER["HTTP_REFERER"], '/bitrix/admin/') > 0
			) {
				CModule::IncludeModule("iblock");
				
				$arResult = array();
				
				$strSearch = trim($_REQUEST["search"]);
				$intCount = intval($_REQUEST["RESULT_COUNT"]);
				if($intCount<=0 || $intCount>50) $intCount = 20;
				
				$arNotID = array();
				$rsI = CIBlockElement::GetList(Array("NAME" => "ASC"), array(
					"IBLOCK_ID" => CHelper::getIblockIdByCode('catalog'),
					array(
						"LOGIC" => "OR",
						array("=NAME" => $strSearch),
						array("=PROPERTY_NAME_LAT" => $strSearch),
						array("=PROPERTY_NAME_LAT" => $strSearch),
						array("=PROPERTY_SYNONYM_RUS" => $strSearch),
						array("=PROPERTY_SYNONYM_LAT" => $strSearch),
					)
				), false, array("nTopCount" => $intCount), array(
					"ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID"
				));
				while ($arI = $rsI->Fetch()) {
					$arNavPath = array();
					$rsNav = CIBlockSection::GetNavChain(CHelper::getIblockIdByCode('catalog'), $arI["IBLOCK_SECTION_ID"]);
					while($arNav = $rsNav -> Fetch()) {
						$arNavPath[] = $arNav["NAME"];
					}
					$arNotID[] = $arI["ID"];
					$arResult[] = array("ID" => $arI["ID"], "NAME" => implode(' / ', $arNavPath).' / '.$arI["NAME"]);
				}
				
				if(count($arResult)< $intCount) {
					$rsI = CIBlockElement::GetList(Array("NAME" => "ASC"), array(
						"IBLOCK_ID" => CHelper::getIblockIdByCode('catalog'),
						"!ID" => $arNotID,
						array(
							"LOGIC" => "OR",
							array("NAME" => '%'.$strSearch.'%'),
							array("PROPERTY_NAME_LAT" => '%'.$strSearch.'%'),
							array("PROPERTY_NAME_LAT" => '%'.$strSearch.'%'),
							array("PROPERTY_SYNONYM_RUS" => '%'.$strSearch.'%'),
							array("PROPERTY_SYNONYM_LAT" => '%'.$strSearch.'%'),
						)
					), false, array("nTopCount" => $intCount - count($arResult)), array(
						"ID",
						"IBLOCK_ID",
						"NAME",
						"IBLOCK_SECTION_ID"
					));
					while ($arI = $rsI->Fetch()) {
						$arNavPath = array();
						$rsNav = CIBlockSection::GetNavChain(CHelper::getIblockIdByCode('catalog'), $arI["IBLOCK_SECTION_ID"]);
						while ($arNav = $rsNav->Fetch()) {
							$arNavPath[] = $arNav["NAME"];
						}
						
						$arResult[] = array("ID"   => $arI["ID"],
						                    "NAME" => implode(' / ', $arNavPath).' / '.$arI["NAME"]
						);
					}
				}
				echo json_encode($arResult);
				die();
			}
		}
	}
}