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

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Main\Application,
	Bitrix\Iblock;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$request = Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest());

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
	$arParams["IBLOCK_TYPE"] = "news";

$arParams["ELEMENT_ID"] = intval($arParams["~ELEMENT_ID"]);
if($arParams["ELEMENT_ID"] > 0 && $arParams["ELEMENT_ID"]."" != $arParams["~ELEMENT_ID"])
{
	if (Loader::includeModule("iblock"))
	{
		Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_DETAIL_NF")
			,true
			,$arParams["SET_STATUS_404"] === "Y"
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
	}
	return;
}

$arParams["CHECK_DATES"] = $arParams["CHECK_DATES"]!="N";
if(!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
foreach($arParams["FIELD_CODE"] as $key=>$val)
	if(!$val)
		unset($arParams["FIELD_CODE"][$key]);
if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach($arParams["PROPERTY_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["PROPERTY_CODE"][$k]);

$arParams["IBLOCK_URL"]=trim($arParams["IBLOCK_URL"]);

$arParams["META_KEYWORDS"]=trim($arParams["META_KEYWORDS"]);
if(strlen($arParams["META_KEYWORDS"])<=0)
	$arParams["META_KEYWORDS"] = "-";
$arParams["META_DESCRIPTION"]=trim($arParams["META_DESCRIPTION"]);
if(strlen($arParams["META_DESCRIPTION"])<=0)
	$arParams["META_DESCRIPTION"] = "-";
$arParams["BROWSER_TITLE"]=trim($arParams["BROWSER_TITLE"]);
if(strlen($arParams["BROWSER_TITLE"])<=0)
	$arParams["BROWSER_TITLE"] = "-";

$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"]!="N"; //Turn on by default
$arParams["ADD_ELEMENT_CHAIN"] = (isset($arParams["ADD_ELEMENT_CHAIN"]) && $arParams["ADD_ELEMENT_CHAIN"] == "Y");
$arParams["SET_TITLE"] = $arParams["SET_TITLE"]!="N";
$arParams["SET_LAST_MODIFIED"] = $arParams["SET_LAST_MODIFIED"]==="Y";
$arParams["SET_BROWSER_TITLE"] = (isset($arParams["SET_BROWSER_TITLE"]) && $arParams["SET_BROWSER_TITLE"] === 'N' ? 'N' : 'Y');
$arParams["SET_META_KEYWORDS"] = (isset($arParams["SET_META_KEYWORDS"]) && $arParams["SET_META_KEYWORDS"] === 'N' ? 'N' : 'Y');
$arParams["SET_META_DESCRIPTION"] = (isset($arParams["SET_META_DESCRIPTION"]) && $arParams["SET_META_DESCRIPTION"] === 'N' ? 'N' : 'Y');
$arParams["STRICT_SECTION_CHECK"] = (isset($arParams["STRICT_SECTION_CHECK"]) && $arParams["STRICT_SECTION_CHECK"] === "Y");

ob_start();
if($this->startResultCache(false))
{

	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arFilter = array(
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE" => "Y",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	);
	if(intval($arParams["IBLOCK_ID"]) > 0)
		$arFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];

	//Handle case when ELEMENT_CODE used
	if($arParams["ELEMENT_ID"] <= 0)
		$arParams["ELEMENT_ID"] = CIBlockFindTools::GetElementID(
			$arParams["ELEMENT_ID"],
			$arParams["~ELEMENT_CODE"],
			$arParams["STRICT_SECTION_CHECK"]? $arParams["SECTION_ID"]: false,
			$arParams["STRICT_SECTION_CHECK"]? $arParams["~SECTION_CODE"]: false,
			$arFilter
		);

	if ($arParams["STRICT_SECTION_CHECK"])
	{
		if ($arParams["SECTION_ID"] > 0)
		{
			$arFilter["SECTION_ID"] = $arParams["SECTION_ID"];
		}
		elseif (strlen($arParams["~SECTION_CODE"]) > 0)
		{
			$arFilter["SECTION_CODE"] = $arParams["~SECTION_CODE"];
		}
		elseif ($this->getParent() && strpos($arParams["DETAIL_URL"], "#SECTION_CODE_PATH#") !== false)
		{
			$this->abortResultCache();
			Iblock\Component\Tools::process404(
				trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_DETAIL_NF")
				,true
				,$arParams["SET_STATUS_404"] === "Y"
				,$arParams["SHOW_404"] === "Y"
				,$arParams["FILE_404"]
			);
			return 0;
		}
	}
	
	
	\ig\CHelper::prepareCartData($arResult);
	\ig\CHelper::preparePriceData(\ig\CHelper::getIblockIdByCode('offers-garden'), $arResult);
	
	$arSelect = array("*", "PROPERTY_*");
	if ($arParams['SET_CANONICAL_URL'] === 'Y')
		$arSelect[] = 'CANONICAL_PAGE_URL';

	$arFilter["ID"] = $arParams["ELEMENT_ID"];
	
	$arResult["IBLOCK_SECTIONS"] = array();
	
	$rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	if($obElement = $rsElement->GetNextElement())
	{
		$arResult = $obElement->GetFields();
		$arResult["PROPERTIES"] = $obElement -> GetProperties();
		
		if(($arResult["DETAIL_TEXT_TYPE"]=="html") && (strstr($arResult["DETAIL_TEXT"], "<BREAK />")!==false))
			$arPages=explode("<BREAK />", $arResult["DETAIL_TEXT"]);
		elseif(($arResult["DETAIL_TEXT_TYPE"]!="html") && (strstr($arResult["DETAIL_TEXT"], "&lt;BREAK /&gt;")!==false))
			$arPages=explode("&lt;BREAK /&gt;", $arResult["DETAIL_TEXT"]);
		else
			$arPages=array();

		$ipropValues = new Iblock\InheritedProperty\ElementValues($arResult["IBLOCK_ID"], $arResult["ID"]);
		$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

		$arResult["IBLOCK"] = GetIBlock($arResult["IBLOCK_ID"], $arResult["IBLOCK_TYPE"]);

		$arResult["SECTION"] = array("PATH" => array());
		$arResult["SECTION_URL"] = "";
		if($arParams["ADD_SECTIONS_CHAIN"] && $arResult["IBLOCK_SECTION_ID"] > 0)
		{
			$rsPath = CIBlockSection::GetNavChain(
				$arResult["IBLOCK_ID"],
				$arResult["IBLOCK_SECTION_ID"],
				array(
					"ID", "CODE", "XML_ID", "EXTERNAL_ID", "IBLOCK_ID",
					"IBLOCK_SECTION_ID", "SORT", "NAME", "ACTIVE",
					"DEPTH_LEVEL", "SECTION_PAGE_URL", "UF_NAME_LAT"
				)
			);
			$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
			while($arPath = $rsPath->GetNext())
			{
				$arResult["IBLOCK_SECTIONS"][$arPath["ID"]] = false;
				
				$ipropValues = new Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $arPath["ID"]);
				$arPath["IPROPERTY_VALUES"] = $ipropValues->getValues();
				$arResult["SECTION"]["PATH"][] = $arPath;
				$arResult["SECTION_URL"] = $arPath["~SECTION_PAGE_URL"];
			}
		}
		
		if(!empty($arResult["IBLOCK_SECTIONS"])) {
			$rsSecTmp = \CIBlockSection::GetList(Array(), array("IBLOCK_ID"=> \ig\CHelper::getIblockIdByCode('catalog-garden'), "ID" => array_keys($arResult["IBLOCK_SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL", "UF_NAME_LAT"));
			while($arSecTmp = $rsSecTmp -> GetNext()) {
				$arResult["IBLOCK_SECTIONS"][$arSecTmp["ID"]] = $arSecTmp;
			}
		}
		
		// get offers
		$arOffersSelect = array(
			"*", "PROPERTY_*"
		);
		
		\ig\CHelper::addSelectFields($arOffersSelect);
		
		// get offers
		$rsO = CIBlockElement::GetList(Array("CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID") => "ASC"), array(
			"ACTIVE"             => "Y",
			"IBLOCK_ID"          => \ig\CHelper::getIblockIdByCode('offers-garden'),
			"PROPERTY_CML2_LINK" => $arResult["ID"]
		), false, false, $arOffersSelect);
		
		while ($obO = $rsO->GetNextElement()) {
			$arO = $obO -> GetFields();
			$arO["PROPERTIES"] = $obO -> GetProperties();
			
			if(empty($arO["PROPERTIES"]["AVAILABLE"]["VALUE"])) {
				$arO["PROPERTIES"]["AVAILABLE"]["VALUE"] = 'Под заказ';
			}
			
			\ig\CHelper::prepareItemPrices($arO);
			
			if($arParams["PRODUCT_ID"]>0) {
				if($intCnt>0) { // по ajax подгружаем 2+
					$arI["OFFER"][] = $arO;
				}
			} else {
				$arResult["OFFERS"][] = $arO;
			}
		}
		
		$resultCacheKeys = array(
			"ID",
			"IBLOCK_ID",
			"NAV_CACHED_DATA",
			"NAME",
			"CODE",
			"IBLOCK_SECTION_ID",
			"IBLOCK",
			"LIST_PAGE_URL", "~LIST_PAGE_URL",
			"SECTION_URL",
			"CANONICAL_PAGE_URL",
			"SECTION",
			"IPROPERTY_VALUES",
			"TIMESTAMP_X",
		);

		if (
			$arParams["SET_TITLE"]
			|| $arParams["ADD_ELEMENT_CHAIN"]
			|| $arParams["SET_BROWSER_TITLE"] === 'Y'
			|| $arParams["SET_META_KEYWORDS"] === 'Y'
			|| $arParams["SET_META_DESCRIPTION"] === 'Y'
		)
		{
			$arResult["META_TAGS"] = array();
			$resultCacheKeys[] = "META_TAGS";

			if ($arParams["SET_TITLE"])
			{
				$arResult["META_TAGS"]["TITLE"] = (
					$arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ""
					? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
					: $arResult["NAME"]
				);
			}

			if ($arParams["ADD_ELEMENT_CHAIN"])
			{
				$arResult["META_TAGS"]["ELEMENT_CHAIN"] = (
					$arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ""
					? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
					: $arResult["NAME"]
				);
			}

			if ($arParams["SET_BROWSER_TITLE"] === 'Y')
			{
				$browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
					$arResult["PROPERTIES"], array($arParams["BROWSER_TITLE"], "VALUE")
					,$arResult, $arParams["BROWSER_TITLE"]
					,$arResult["IPROPERTY_VALUES"], "ELEMENT_META_TITLE"
				);
				$arResult["META_TAGS"]["BROWSER_TITLE"] = (
					is_array($browserTitle)
					? implode(" ", $browserTitle)
					: $browserTitle
				);
				unset($browserTitle);
			}
			if ($arParams["SET_META_KEYWORDS"] === 'Y')
			{
				$metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
					$arResult["PROPERTIES"], array($arParams["META_KEYWORDS"], "VALUE")
					,$arResult["IPROPERTY_VALUES"], "ELEMENT_META_KEYWORDS"
				);
				$arResult["META_TAGS"]["KEYWORDS"] = (
					is_array($metaKeywords)
					? implode(" ", $metaKeywords)
					: $metaKeywords
				);
				unset($metaKeywords);
			}
			if ($arParams["SET_META_DESCRIPTION"] === 'Y')
			{
				$metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
					$arResult["PROPERTIES"], array($arParams["META_DESCRIPTION"], "VALUE")
					,$arResult["IPROPERTY_VALUES"], "ELEMENT_META_DESCRIPTION"
				);
				$arResult["META_TAGS"]["DESCRIPTION"] = (
					is_array($metaDescription)
					? implode(" ", $metaDescription)
					: $metaDescription
				);
				unset($metaDescription);
			}
		}

		$this->setResultCacheKeys($resultCacheKeys);

		$strTemplate = ($arParams["IS_AJAX"]?'preview':'');
		$this->includeComponentTemplate($strTemplate);
	}
	else
	{
		$this->abortResultCache();
		Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_DETAIL_NF")
			,true
			,$arParams["SET_STATUS_404"] === "Y"
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
	}
}

$strContents = ob_get_contents();
ob_end_clean();

if($arParams["IS_AJAX"]) {
	$APPLICATION->RestartBuffer();
	echo $strContents;
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
} else {
	echo $strContents;
	
	if(isset($arResult["ID"])) {
		$arTitleOptions = null;
		if(Loader::includeModule("iblock")) {
			CIBlockElement::CounterInc($arResult["ID"]);
			
			if($USER->IsAuthorized()) {
				if($APPLICATION->GetShowIncludeAreas() || $arParams["SET_TITLE"] || isset($arResult[$arParams["BROWSER_TITLE"]])) {
					$arReturnUrl = array(
						"add_element"    => CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "DETAIL_PAGE_URL"),
						"delete_element" => (empty($arResult["SECTION_URL"]) ? $arResult["LIST_PAGE_URL"] : $arResult["SECTION_URL"]),
					);
					
					$arButtons = CIBlock::GetPanelButtons($arResult["IBLOCK_ID"], $arResult["ID"], $arResult["IBLOCK_SECTION_ID"], Array(
							"RETURN_URL"      => $arReturnUrl,
							"SECTION_BUTTONS" => false,
						));
					
					if($APPLICATION->GetShowIncludeAreas()) {
						$this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
					}
					
					if($arParams["SET_TITLE"] || isset($arResult[$arParams["BROWSER_TITLE"]])) {
						$arTitleOptions = array(
							'ADMIN_EDIT_LINK'  => $arButtons["submenu"]["edit_element"]["ACTION"],
							'PUBLIC_EDIT_LINK' => $arButtons["edit"]["edit_element"]["ACTION"],
							'COMPONENT_NAME'   => $this->getName(),
						);
					}
				}
			}
		}
		
		$this->setTemplateCachedData($arResult["NAV_CACHED_DATA"]);
		
		if($arParams['SET_CANONICAL_URL'] === 'Y' && $arResult["CANONICAL_PAGE_URL"]) {
			$APPLICATION->SetPageProperty('canonical', $arResult["CANONICAL_PAGE_URL"]);
		}
		
		if($arParams["SET_TITLE"]) {
			$APPLICATION->SetTitle($arResult["META_TAGS"]["TITLE"], $arTitleOptions);
		}
		
		if($arParams["SET_BROWSER_TITLE"] === 'Y') {
			if($arResult["META_TAGS"]["BROWSER_TITLE"] !== '') {
				$APPLICATION->SetPageProperty("title", $arResult["META_TAGS"]["BROWSER_TITLE"], $arTitleOptions);
			}
		}
		
		if($arParams["SET_META_KEYWORDS"] === 'Y') {
			if($arResult["META_TAGS"]["KEYWORDS"] !== '') {
				$APPLICATION->SetPageProperty("keywords", $arResult["META_TAGS"]["KEYWORDS"], $arTitleOptions);
			}
		}
		
		if($arParams["SET_META_DESCRIPTION"] === 'Y') {
			if($arResult["META_TAGS"]["DESCRIPTION"] !== '') {
				$APPLICATION->SetPageProperty("description", $arResult["META_TAGS"]["DESCRIPTION"], $arTitleOptions);
			}
		}
		
		$strLastSectionCode = '';
		if($arParams["ADD_SECTIONS_CHAIN"] && is_array($arResult["SECTION"])) {
			foreach ($arResult["SECTION"]["PATH"] as $arPath) {
				$strLastSectionCode = $arPath["CODE"];
				if($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "") {
					$APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
				} else {
					$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
				}
			}
		}
		if($arParams["ADD_ELEMENT_CHAIN"] && $arResult["CODE"]!=$strLastSectionCode) {
			$APPLICATION->AddChainItem($arResult["META_TAGS"]["ELEMENT_CHAIN"]);
		}
		
		if($arParams["SET_LAST_MODIFIED"] && $arResult["TIMESTAMP_X"]) {
			Context::getCurrent()->getResponse()->setLastModified(DateTime::createFromUserTime($arResult["TIMESTAMP_X"]));
		}
	}
}