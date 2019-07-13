<?php

namespace ig\Etc;

class CNews {
	function setItemSection(&$arFields) {
		$strActiveFrom = $arFields["ACTIVE_FROM"];
		
		$arDateTime = explode(' ', $strActiveFrom);
		$arDate = explode('.', $arDateTime[0]);
		
		$intSectionID = \ig\Etc\CNews::getSectionID($arDate[2], $arDate[1]);
		
		if($intSectionID>0) {
			$arFields["IBLOCK_SECTION"] = array($intSectionID);
		} else {
			global $APPLICATION;
			$APPLICATION->throwException("Не удалось привязать новость к разделу. Обратитесь к разработчику.");
			
			return false;
		}
	}
	
	private static function getSectionID($intYear, $intMonth) {
		$intIblockID = \ig\CHelper::getIblockIdByCode('news');
		
		$bResort = false;
		
		$intMonthSectionID = 0;
		
		$intYear = intval($intYear);
		$intMonth = intval($intMonth);
		
		$arSecYear = \Bitrix\Iblock\SectionTable::getList(
			array(
				"filter" => array("ACTIVE"=>"Y", "IBLOCK_ID"=>$intIblockID, "=CODE"=>$intYear, "IBLOCK_SECTION_ID"=>false),
				"select" => array("ID", "NAME")
			)
		) -> fetch();
		
		if(empty($arSecYear["ID"])) {
			$rsAdd = \Bitrix\Iblock\SectionTable::add(
				array(
					'IBLOCK_ID' => $intIblockID,
					"DEPTH_LEVEL" => 1,
					"ACTIVE" => 'Y',
					'NAME' => $intYear,
					"CODE" => $intYear,
					'TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime()
				)
			);
			$intYearSectionID = $rsAdd -> getId();
			$bResort = true;
		} else {
			$intYearSectionID = $arSecYear["ID"];
		}
		
		if($intYearSectionID>0) {
			$strMonthCode = sprintf("%02d", $intMonth);
			
			$arSecMonth = \Bitrix\Iblock\SectionTable::getList(
				array(
					"filter" => array("ACTIVE"=>"Y", "IBLOCK_ID"=>$intIblockID, "=CODE"=>$strMonthCode, "IBLOCK_SECTION_ID"=>$intYearSectionID),
					"select" => array("ID")
				)
			) -> fetch();
			if(empty($arSecMonth["ID"])) {
				$intMonthSectionID = \Bitrix\Iblock\SectionTable::add(
					array(
						'IBLOCK_ID' => $intIblockID,
						"DEPTH_LEVEL" => 2,
						"ACTIVE" => 'Y',
						'NAME' => \ig\CLists::getMonth($intMonth),
						"CODE" => $strMonthCode,
						"SORT" => intval($intMonth) * 10,
						"IBLOCK_SECTION_ID" => $intYearSectionID,
						'TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime()
					)
				) -> getId();
				
				$bResort = true;
			} else {
				$intMonthSectionID = $arSecMonth["ID"];
			}
		}
		
		if($bResort) {
			\CIBlockSection::Resort($intIblockID);
		}
		
		return $intMonthSectionID;
	}
}