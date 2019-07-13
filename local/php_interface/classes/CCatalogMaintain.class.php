<?php
namespace ig;

class CCatalogMaintain {
	private $intIblockID = 0,
		$obIblockElement = false,
		$obIblockSection = false,
		$bResortSections = false;
	
	function __construct($intIblockID)
	{
		$intIblockID = intval($intIblockID);
		if(!in_array($intIblockID, array(\ig\CHelper::getIblockIdByCode('catalog'), \ig\CHelper::getIblockIdByCode('catalog-garden')))) {
			die('Указан некорректны инфоблок');
		} else {
			$this -> intIblockID = $intIblockID;
			$this -> obIblockElement = new \CIBlockElement;
			$this -> obIblockSection = new \CIBlockSection;
		}
	}
	
	public function process() {
		$this -> activateCatalog();
		$this -> deactivateCatalog();
		
		if($this -> bResortSections) {
			\CIBlockSection::ReSort($this-> intIblockID);
		}
	}
	
	public function activateCatalog() {
		$this -> activateProduct();
		$this -> activateSections();
	}
	
	public function deactivateCatalog() {
		$this -> deactivateOffers();
		$this -> deactivateProduct();
		$this -> deactivateSections();
	}
	
	private function activateProduct() {
		$intLastID = 0;
		$intCnt = 0;
		$arFilter = array("=ACTIVE"=>'N', "IBLOCK_ID"=>$this->intIblockID);
		$arSelect = array("IBLOCK_ID", "NAME", "ID");
		
		do {
			$arFilter[">ID"] = $intLastID;
			$rsI = \CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false, array("nTopCount" => 100), $arSelect);
			$arProductCheck = array();
			while ($arI = $rsI->Fetch()) {
				$arProductCheck[] = $arI["ID"];
				$intLastID = $arI["ID"];
			}
			
			if(!empty($arProductCheck)) {
				$arCheckResult = \CCatalogSKU::getOffersList($arProductCheck, $arFilter["IBLOCK_ID"], array("=ACTIVE" => 'Y', ">CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID")=>1), array("ID"));
				$arDiff = array_intersect(array_keys($arCheckResult), $arProductCheck);
				if(!empty($arDiff)) {
					foreach($arDiff as $intID) {
						echo 'activate product <a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arFilter["IBLOCK_ID"].'&type=catalog&ID='.$intID.'&lang=ru&find_section_section=2990&WF=Y"> ['.$intID.']</a> (has active offers with price > 1)<br>';
						$this->obIblockElement->Update($intID, array("ACTIVE" => "Y"));
					}
				}
			}
			
			$intCnt++;
		} while($intCnt<2000 && $rsI -> SelectedRowsCount()>0);
	}
	
	private function activateSections() {
		$rsSec = \CIBlockSection::GetList(
			Array("ID"=>"ASC"),
			array(
				"=ACTIVE"=>"N",
			    "IBLOCK_ID"=>$this->intIblockID
			), true, array("NAME", "IBLOCK_ID", "ID"));
		
		while($arSec = $rsSec -> Fetch()) {
			$rsI = \CIBlockElement::GetList(Array(), array(
				"ACTIVE" => "Y",
				"IBLOCK_ID" => $this->intIblockID,
				"INCLUDE_SUBSECTIONS" => "Y",
				"SECTION_ID" => $arSec["ID"]
			), false, array("nTopCount" => 1), array(
				"ID", "IBLOCK_ID"
			));
			if($rsI -> SelectedRowsCount()>0) {
//				echo 'activate section <a target="_blank" href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID='.$this->intIblockID.'&type=catalog&ID='.$arSec["ID"].'&lang=ru&find_section_section=0">'.$arSec["ID"].'</a> (active product)<br>';
			    $this->obIblockSection->Update($arSec["ID"], array("ACTIVE" => "Y"), false);
				$this -> bResortSections = true;
			}
		}
	}
	
	private function deactivateSections() {
		// sections 2 level
		$arActiveSections = array();
		$rsI = \CIBlockElement::GetList(Array(), array(
			"IBLOCK_ID" => $this->intIblockID,
			"=ACTIVE"    => "Y"
		), array("IBLOCK_SECTION_ID"));
		while ($arI = $rsI->Fetch()) {
			$arActiveSections[] = $arI["IBLOCK_SECTION_ID"];
		}
		
		$arFilter = array("IBLOCK_ID"=>$this->intIblockID, "=ACTIVE" => "Y", "DEPTH_LEVEL" => 2);
		if(!empty($arActiveSections)) {
			$arFilter["!ID"] = $arActiveSections;
		}
		
		$rsSec = \CIBlockSection::GetList(Array(), $arFilter, false);
		while($arSec = $rsSec -> Fetch()) {

//			echo 'deactivate section 2L <a target="_blank" href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID='.$this->intIblockID.'&type=catalog&ID='.$arSec["ID"].'&lang=ru&find_section_section=0">'.$arSec["ID"].'</a> (has no active product)<br>';
			$this->obIblockSection->Update($arSec["ID"], array("ACTIVE" => "N"), false);
			$this -> bResortSections = true;
		}
		
		// sections 1 level
		$rsSec = \CIBlockSection::GetList(Array(), array(
			"IBLOCK_ID"   => $this->intIblockID,
            "ACTIVE"      => "Y",
            "DEPTH_LEVEL" => 1
		), false);
		while ($arSec = $rsSec->Fetch()) {
			$rsSubSec = \CIBlockSection::GetList(Array(), array(
				"IBLOCK_ID"   => $this->intIblockID,
				"ACTIVE"      => "Y",
				"DEPTH_LEVEL" => 2,
				"SECTION_ID"  => $arSec["ID"]
			), false);
			if($rsSubSec->SelectedRowsCount() == 0) {
				// deactivate rod
//				echo 'deactivate section 1L <a target="_blank" href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID='.$this->intIblockID.'&type=catalog&ID='.$arSec["ID"].'&lang=ru&find_section_section=0">'.$arSec["ID"].'</a> (has no active subsections)<br>';
				$this -> bResortSections = true;
				$this->obIblockSection->Update($arSec["ID"], array("ACTIVE" => "N"), false);
			}
		}
	}
	
	function deactivateOffers() {
		$arOffersData = \CIBlockPriceTools::GetOffersIBlock($this->intIblockID);
		
		if($arOffersData["OFFERS_IBLOCK_ID"]>0) {
			$arBaseFilter = array("IBLOCK_ID" => $arOffersData["OFFERS_IBLOCK_ID"], "ACTIVE"=>"Y", array(
				"LOGIC" => "OR",
				array("PROPERTY_CML2_LINK.ACTIVE" => "N"),
				array("<=CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID")=>1)
			));
			$rsI = \CIBlockElement::GetList(Array("ID" => "ASC"), $arBaseFilter, false, false, array("ID"));
			while ($arI = $rsI->Fetch()) {
//				echo 'deactivate offer <a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arOffersData["OFFERS_IBLOCK_ID"].'&type=catalog&ID='.$arI["ID"].'&lang=ru&find_section_section=0&WF=Y">'.$arI["ID"].'</a> (inactive product or empty price)<br>';
				$this->obIblockElement->Update($arI["ID"], array("ACTIVE" => "N"));
			}
		}
	}
	
	private function deactivateProduct() {
		$intLastID = 0;
		$intCnt = 0;
		$arFilter = array("=ACTIVE"=>'Y', "IBLOCK_ID"=>$this->intIblockID);
		$arSelect = array("IBLOCK_ID", "NAME", "ID");
		
		do {
			$arFilter[">ID"] = $intLastID;
			$rsI = \CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false, array("nTopCount" => 100), $arSelect);
			$arProductCheck = array();
			while ($arI = $rsI->Fetch()) {
				$arProductCheck[] = $arI["ID"];
				$intLastID = $arI["ID"];
			}
			
			if(!empty($arProductCheck)) {
				$arCheckResult = \CCatalogSKU::getOffersList($arProductCheck, $arFilter["IBLOCK_ID"], array("=ACTIVE" => 'Y', ">CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID")=>1), array("ID"));
				$arDiff = array_diff($arProductCheck, array_keys($arCheckResult));
				if(!empty($arDiff)) {
					foreach($arDiff as $intID) {
						echo 'deactivate product <a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arFilter["IBLOCK_ID"].'&type=catalog&ID='.$intID.'&lang=ru&find_section_section=2990&WF=Y"> ['.$intID.']</a> (has not active offers with price > 1)<br>';
						$this->obIblockElement->Update($intID, array("ACTIVE" => "N"));
					}
				}
			}
			
			$intCnt++;
		} while($intCnt<2000 && $rsI -> SelectedRowsCount()>0);
	}
}