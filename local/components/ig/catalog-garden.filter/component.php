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

use Bitrix\Main\Application,
	Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Main\Data\Cache;

$request = Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest()); // && $_REQUEST["frmCatalogFilterSent"] == 'Y'?'Y':'N'
$arParams["EXPAND_FILTER"] = intval($_COOKIE["filter_active"])>0 ? 'Y':'N';

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;
$arParams["CACHE_TIME"] = 0;
$arResult = array();

CModule::IncludeModule("iblock");


$intSectionIDFromUrl = intval($arParams["COMPLEX_RESULT"]["VARIABLES"]["SECTION_ID"]);

// get sections
$arResult["SECTIONS"] = array();
$rsSections = \CIBlockSection::GetList(Array("left_margin"=>"asc"), array(
	"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog-garden'),
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y"
), false, array("DEPTH_LEVEL", "NAME", "ID", "IBLOCK_SECTION_ID"));
while($arSection = $rsSections -> Fetch()) {
	if($arSection["DEPTH_LEVEL"] == 1) {
		$arResult["SECTIONS"][$arSection["ID"]] = $arSection;
	} else if($arSection["DEPTH_LEVEL"] == 2) {
		$arResult["SECTIONS"][$arSection["IBLOCK_SECTION_ID"]]["SUBSECTIONS"][] = $arSection;
	}
	
	if($intSectionIDFromUrl == $arSection["ID"]) {
		$_REQUEST["F"]["SECTION"] = ($arSection["DEPTH_LEVEL"]>1?'S':'F').$intSectionIDFromUrl;
	}
}

// get lists
$obCache = Cache::createInstance();
$strCacheID = md5(serialize($arParams, $_REQUEST["F"]["PROPERTY_GROUP"]));

if ($obCache->initCache($arParams["CACHE_TIME"], $strCacheID)) {
	$arResult = $obCache->getVars();
} elseif ($obCache->startDataCache()) {
	
	if(strpos($APPLICATION->GetCurDir(), '/katalog/tovary-dlya-sada/novinki/') === 0) {
		$arResult["IS_NEW"] = true;
		$arResult["FILTER_PAGE_URL"] = '/katalog/tovary-dlya-sada/novinki/';
	} elseif(strpos($APPLICATION->GetCurDir(), '/katalog/tovary-dlya-sada/akcii/') === 0) {
		$arResult["IS_ACTION"] = true;
		$arResult["FILTER_PAGE_URL"] = '/katalog/tovary-dlya-sada/akcii/';
	} else {
		$arResult["FILTER_PAGE_URL"] = '/katalog/tovary-dlya-sada/';
	}
	
	$arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"] = array();
	$rsList = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("PeriodPrice"), array("UF_ACTIVE" => 1), array(
		"UF_NAME",
		"ID",
		"UF_XML_ID"
	), array(), true);
	while ($arList = $rsList->Fetch()) {
		$arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["VALUES"][$arList["UF_XML_ID"]] = array(
			"ID"           => $arList["ID"],
			"NAME"         => $arList["UF_NAME"],
			"VALUE"        => $arList["UF_XML_ID"],
			"SPHINX_VALUE" => \ig\sphinx\CatalogGardenOffers::convertValueToSphinx("CATALOG_PRICE_LIST", $arList["UF_XML_ID"]),
			"COUNT"        => 0,
			"DISABLED"     => "N"
		);
	}
	
	$arResult["OFFER_PARAMS"]["AVAILABLE"] = array();
	$rsList = CIBlockPropertyEnum::GetList(Array(
		"DEF"  => "DESC",
		"SORT" => "ASC"
	), Array("IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('offers-garden'), "CODE" => "AVAILABLE"));
	while ($arList = $rsList->Fetch()) {
		$arResult["OFFER_PARAMS"]["AVAILABLE"]["VALUES"][$arList["ID"]] = array(
			"ID"           => $arList["ID"],
			"NAME"         => $arList["VALUE"],
			"VALUE"        => $arList["ID"],
			"SPHINX_VALUE" => \ig\sphinx\CatalogGardenOffers::convertValueToSphinx("PROPERTY_AVAILABLE", $arList["ID"]),
			"COUNT"        => 0,
			"DISABLED"     => "N"
		);
	}
	
	$arResult["OFFER_PARAMS"]["USAGE"] = array();
	$rsList = CIBlockPropertyEnum::GetList(Array(
		"DEF"  => "DESC",
		"SORT" => "ASC"
	), Array("IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog-garden'), "CODE" => "USAGE"));
	while ($arList = $rsList->Fetch()) {
		$arResult["OFFER_PARAMS"]["USAGE"]["VALUES"][$arList["ID"]] = array(
			"ID"           => $arList["ID"],
			"NAME"         => $arList["VALUE"],
			"VALUE"        => $arList["ID"],
			"SPHINX_VALUE" => \ig\sphinx\CatalogGardenOffers::convertValueToSphinx("PROPERTY_USAGE", $arList["ID"]),
			"COUNT"        => 0,
			"DISABLED"     => "N"
		);
	}
	
	$obCache->endDataCache($arResult);
}

if($_REQUEST["search"] == 'Y') {
	$arParams["QUERY"] = trim($_REQUEST["searchQuery"]);
	
	if(empty($arParams["QUERY"])) {
		unset($arParams["QUERY"]);
	}
	
	if(strlen($arParams["QUERY"])) {
		$arResult["SEARCH"]["QUERY"] = $arParams["QUERY"];
		
		$arFound = $this -> searchEntity($arParams["QUERY"]);
	}
	
	if(!empty($arFound)) {
		$arResult["SEARCH"]["DATA"] = $this->getSearchResult($arFound);
	}
	
	ob_start();
	$this->includeComponentTemplate("search");
	$strSearchHtml = ob_get_contents();
	ob_end_clean();
} else {
	
	if(strlen($_REQUEST["filterAlias"])>0) {
		$_REQUEST["F"] = $this->getFilterByAlias($_REQUEST["filterAlias"]);
	}
	
	if(!empty($_REQUEST["F"])) {
		$_REQUEST["frmCatalogFilterSent"] = 'Y';
	}
	
	$arSearchParams = array();
	if($_REQUEST["frmCatalogFilterSent"] == 'Y') {
		$intSelectedGroupID = false;
		foreach ($_REQUEST["F"] as $strCode => $obValue) {
			$strCode = trim($strCode);
			
			if(is_array($obValue)) {
				if(!empty($obValue)) {
					\CatalogFilter::$arRequestFilter[$strCode] = $obValue;
				}
			} else {
				if(!empty(trim($obValue))) {
					if($strCode == "SECTION") {
						$strValue = substr($obValue, 1);
						if(substr($obValue, 0, 1) == "F") {
							\CatalogFilter::$arRequestFilter["CAT_SECTION_1"] = $strValue;
							$arSearchParams["CAT_SECTION_1"] = $strValue;
						} else {
							\CatalogFilter::$arRequestFilter["CAT_SECTION_2"] = $strValue;
							$arSearchParams["CAT_SECTION_2"] = $strValue;
						}
					} else {
						\CatalogFilter::$arRequestFilter[$strCode] = trim($obValue);
					}
				}
			}
			
			if(!empty(\CatalogFilter::$arRequestFilter[$strCode])) {
				$arSearchParams[$strCode] = \ig\sphinx\CatalogGardenOffers::convertValueToSphinx($strCode, \CatalogFilter::$arRequestFilter[$strCode]);
			}
		}
		
		if(empty($_REQUEST["filterAlias"])) {
			$arResult["FILTER_ALIAS"] = $this->processUseFilter($_REQUEST["F"]);
		}
	}
	
	$arFacetExcludeParams = array_keys($arSearchParams);
	$arFacetExcludeParams[] = 'CAT_SECTION_1';
	$arFacetExcludeParams[] = 'CAT_SECTION_2';
	
	$arFacetExcludeParams = array_unique($arFacetExcludeParams);
	
	
	$obSearch = new \ig\sphinx\CatalogGardenOffers();
	
	$arResult["COUNT_TOTAL_OFFERS"] = $obSearch->getCount(array(), 'id');
	$arResult["COUNT_DATA"] = array();
	
	if($arResult["IS_ACTION"]) {
		$arSearchParams[">CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;
	} elseif($arResult["IS_NEW"]) {
		$intNewEnumID = \ig\CHelper::getEnumID(\ig\CHelper::getIblockIdByCode('offers-garden'), "NEW", "Да");
		$arSearchParams["PROPERTY_NEW"] = $intNewEnumID;
	}
	
	$arResult["COUNT_PRODUCTS"] = $obSearch->getCount($arSearchParams, 'cat_id');
	$arResult["COUNT_OFFERS"] = $obSearch->getCount($arSearchParams, 'id');
	
	
	if(!$arResult["IS_ACTION"] && !$arResult["IS_NEW"]) {
// фасетим по нефильтрованным параметрам
		$arFacetData = $obSearch->searchFacet(array("FILTER" => $arSearchParams), \ig\sphinx\CatalogGardenOffers::getFacetFields(array("EXCLUDE" => $arFacetExcludeParams)), true);
		foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
			$strCode = str_replace("PROPERTY_", '', \ig\sphinx\CatalogGardenOffers::convertFieldCode($strSphinxCode, false));
			
			$arTotalFilter = $arSearchParams;
			unset($arTotalFilter[\ig\sphinx\CatalogGardenOffers::convertFieldCode($strSphinxCode, false)]);
			
			$arResult["COUNT_DATA"][$strCode]["TOTAL"] = $obSearch->getCount($arTotalFilter, 'id');
			foreach ($arDataList as $arData) {
				$arResult["COUNT_DATA"][$strCode][$arData[$strSphinxCode]] = $arData["count"];
			}
		}

// фасетим по фильтрованным параметрам
		foreach ($arSearchParams as $strCode => $value) {
			$arTmpFilter = $arSearchParams;
			
			$arParamData = \ig\sphinx\CatalogGardenOffers::getSphinxConfig(\ig\sphinx\CatalogGardenOffers::convertFieldCode($strCode));
			
			if($arParamData["CONTROL"] == 'CHECKBOX' && $arParamData["MULTIPLE"] == 'Y') {
				// сначала берем фасет по неотмеченным вариантам
				$arTmpFilter["NOT_".$strCode] = $arTmpFilter[$strCode];
				unset($arTmpFilter[$strCode]);
				
				$arFacetData = $obSearch->searchFacet(array("FILTER" => $arTmpFilter), \ig\sphinx\CatalogGardenOffers::getFacetFields(array("INCLUDE" => $strCode)), true);
				
				foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
					$strCodeTmp = str_replace("PROPERTY_", '', \ig\sphinx\CatalogGardenOffers::convertFieldCode($strSphinxCode, false));
					foreach ($arDataList as $arData) {
						$arResult["COUNT_DATA"][$strCodeTmp][$arData[$strSphinxCode]] = $arData["count"];
					}
				}
				
				// по отмеченным фасет взять нельзя, т.к. неизвестны пересечения с другими вариантами, тут берем простым count
				foreach ($arSearchParams[$strCode] as $key => $selectedValue) {
					$arTmpFilter = $arSearchParams;
					
					unset($arTmpFilter[$strCode][$key]);
					if(empty($arTmpFilter[$strCode])) {
						unset($arTmpFilter[$strCode]);
					}
					
					
					$arResult["COUNT_DATA"][str_replace("PROPERTY_", '', $strCode)][$selectedValue] = $obSearch->getCount($arTmpFilter, 'id');
				}
				
			} else {
				unset($arTmpFilter[$strCode]);
				
				$arFacetData = $obSearch->searchFacet(array("FILTER" => $arTmpFilter), \ig\sphinx\CatalogGardenOffers::getFacetFields(array("INCLUDE" => $strCode)), true);
				
				foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
					$strCode = str_replace("PROPERTY_", '', \ig\sphinx\CatalogGardenOffers::convertFieldCode($strSphinxCode, false));
					$arResult["COUNT_DATA"][$strCode]["TOTAL"] = $obSearch->getCount($arTmpFilter, 'id');
					foreach ($arDataList as $arData) {
						$arResult["COUNT_DATA"][$strCode][$arData[$strSphinxCode]] = $arData["count"];
					}
				}
			}
		}
	}
	$arFilterTmp = $arSearchParams;
	
	if($_REQUEST["frmCatalogFilterSent"] == 'Y') {
		if(\CatalogFilter::$arRequestFilter["CAT_SECTION_1"]>0) {
			$intSection = \CatalogFilter::$arRequestFilter["CAT_SECTION_1"];
		} elseif(\CatalogFilter::$arRequestFilter["CAT_SECTION_2"]>0) {
			$intSubSection = \CatalogFilter::$arRequestFilter["CAT_SECTION_2"];
		}
		
		if($intSection>0 || $intSubSection>0) {
			foreach ($arResult["SECTIONS"] as $intSecID => &$arSection) {
				if($intSection == $arSection["ID"]) {
					$arSection["CHECKED"] = 'Y';
				}
				
				if($intSubSection>0) {
					foreach($arSection["SUBSECTIONS"] as &$arSubsection) {
						if($intSubSection == $arSubsection["ID"]) {
							$arSubsection["CHECKED"] = 'Y';
							$arSection["HAS_CHECKED_SUBSECTION"] = 'Y';
						}
					}
				}
			}
		}
		
		unset($arSection);
		unset($arSubsection);
	}
	
	$arResult["FILTER_EMPTY"] = (empty($arFilterTmp) ? 'Y' : 'N');
	
	$strBaseUrl = '/katalog/tovary-dlya-sada/';
	// если выбран раздел
	$intSelectedSectionID = 0;
	if(\CatalogFilter::$arRequestFilter["CAT_SECTION_1"]>0) {
		$intSelectedSectionID = \CatalogFilter::$arRequestFilter["CAT_SECTION_1"];
	} elseif(\CatalogFilter::$arRequestFilter["CAT_SECTION_2"]>0) {
		$intSelectedSectionID = \CatalogFilter::$arRequestFilter["CAT_SECTION_2"];
	}
	
	if($intSelectedSectionID>0) {
		$rsSec = CIBlockSection::GetList(Array(), array("ACTIVE"=>"Y", "IBLOCK_ID"=> \ig\CHelper::getIblockIdByCode('catalog-garden'), "ID" => $intSelectedSectionID), false, array("ID", "CODE", "IBLOCK_ID", "SECTION_PAGE_URL"));
		if($arSec = $rsSec -> GetNext()) {
			$strBaseUrl = $arSec["SECTION_PAGE_URL"];
		}
	}
	
	if($intSelectedSectionID>0 && count(\CatalogFilter::$arRequestFilter)===1) {
		$arResult["RESULT_LINK"] = 'https://'.$_SERVER["HTTP_HOST"].$strBaseUrl;
	} elseif(!empty($arResult["FILTER_ALIAS"])) {
		$arResult["RESULT_LINK"] = 'https://'.$_SERVER["HTTP_HOST"].$strBaseUrl.'?filterAlias='.$arResult["FILTER_ALIAS"];
	} else {
		$arResult["RESULT_LINK"] = 'https://'.$_SERVER["HTTP_HOST"].$strBaseUrl.'?'.str_replace('AJAX=Y', 'AJAX=N', $APPLICATION->GetCurParam());
	}
	
	\ig\CRegistry::add('catalogGroupFilter', $arSearchParams);
	
	ob_start();
	$this->includeComponentTemplate($strTemplate);
	
	$strFilterHtml = ob_get_contents();
	ob_end_clean();
}

if($arParams["IS_AJAX"] == 'Y') {
	if(!empty($strSearchHtml)) {
		$APPLICATION->RestartBuffer();
		$arResponse = array("html" => $strSearchHtml);
		echo json_encode($arResponse);
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
		die();
	} else {
		$arResponse = array();
		
		if(strlen($strFilterHtml)>0) {
			\ig\CRegistry::add('catalog-filter_html', $strFilterHtml);
		}
		
		if(strlen($arResult["RESULT_LINK"])>0) {
			\ig\CRegistry::add('catalog-page_url', $arResult["RESULT_LINK"]);
		}
		
		if(strlen($strSearchHtml)>0) {
			\ig\CRegistry::add('catalog-html', $strSearchHtml);
		}
	}
} else {
	echo $strFilterHtml;
//	echo $strListHrml;
}