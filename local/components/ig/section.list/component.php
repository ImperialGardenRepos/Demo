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

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock;

/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
$arParams["SECTION_CODE"] = trim($arParams["SECTION_CODE"]);

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);

$arParams["TOP_DEPTH"] = intval($arParams["TOP_DEPTH"]);
if($arParams["TOP_DEPTH"] <= 0)
	$arParams["TOP_DEPTH"] = 2;
$arParams["COUNT_ELEMENTS"] = $arParams["COUNT_ELEMENTS"]!="N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"]!="N"; //Turn on by default

$arResult["SECTIONS"]=array();

/*************************************************************************
			Work with cache
*************************************************************************/
if($this->startResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	$arResult["IBLOCK"] = \Bitrix\Iblock\IblockTable::getList(array(
		'filter' => array('=ID' => $arParams['IBLOCK_ID'], '=ACTIVE' => 'Y'),
		'select' => array("ID", "NAME")
	)) -> fetch();
	
	if (empty($arResult["IBLOCK"]))
	{
		$this->abortResultCache();
		return;
	}

	$arFilter = array(
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"]
	);
	
	$arSelect = array("*", "UF_*");
	
	$arResult["SECTION"] = false;
	$intSectionDepth = 0;
	if($arParams["SECTION_ID"]>0)
	{
		$arFilter["ID"] = $arParams["SECTION_ID"];
		$rsSections = CIBlockSection::GetList(array(), $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
		$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult["SECTION"] = $rsSections->GetNext();
	}
	elseif('' != $arParams["SECTION_CODE"])
	{
		$arFilter["=CODE"] = $arParams["SECTION_CODE"];
		$rsSections = CIBlockSection::GetList(array(), $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
		$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult["SECTION"] = $rsSections->GetNext();
	}

	if(is_array($arResult["SECTION"]))
	{
		unset($arFilter["ID"]);
		unset($arFilter["=CODE"]);
		$arFilter["LEFT_MARGIN"]=$arResult["SECTION"]["LEFT_MARGIN"]+1;
		$arFilter["RIGHT_MARGIN"]=$arResult["SECTION"]["RIGHT_MARGIN"];
		$arFilter["<="."DEPTH_LEVEL"]=$arResult["SECTION"]["DEPTH_LEVEL"] + $arParams["TOP_DEPTH"];

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
		$arResult["SECTION"]["IPROPERTY_VALUES"] = $ipropValues->getValues();

		$arResult["SECTION"]["PATH"] = array();
		$rsPath = CIBlockSection::GetNavChain(
			$arResult["SECTION"]["IBLOCK_ID"],
			$arResult["SECTION"]["ID"],
			array(
				"ID", "CODE", "XML_ID", "EXTERNAL_ID", "IBLOCK_ID",
				"IBLOCK_SECTION_ID", "SORT", "NAME", "ACTIVE",
				"DEPTH_LEVEL", "SECTION_PAGE_URL"
			)
		);
		$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
		while($arPath = $rsPath->GetNext())
		{
			if ($arParams["ADD_SECTIONS_CHAIN"])
			{
				$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $arPath["ID"]);
				$arPath["IPROPERTY_VALUES"] = $ipropValues->getValues();
			}
			$arResult["SECTION"]["PATH"][]=$arPath;
		}
	}
	else
	{
		$arResult["SECTION"] = array("ID"=>0, "DEPTH_LEVEL"=>0);
		$arFilter["<="."DEPTH_LEVEL"] = $arParams["TOP_DEPTH"];
	}
	$intSectionDepth = $arResult["SECTION"]['DEPTH_LEVEL'];

	//ORDER BY
	$arSort = array(
		"SORT"=>"ASC",
		"NAME" => "ASC"
	);
	//EXECUTE
	$rsSections = CIBlockSection::GetList($arSort, $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
	$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
	while($arSection = $rsSections->GetNext())
	{
		\Bitrix\Iblock\InheritedProperty\SectionValues::queue($arSection["IBLOCK_ID"], $arSection["ID"]);

		$arSection['RELATIVE_DEPTH_LEVEL'] = $arSection['DEPTH_LEVEL'] - $intSectionDepth;
		
		$arButtons = CIBlock::GetPanelButtons(
			$arSection["IBLOCK_ID"],
			0,
			$arSection["ID"],
			array("SESSID"=>false, "CATALOG"=>true)
		);
		$arSection["EDIT_LINK"] = $arButtons["edit"]["edit_section"]["ACTION_URL"];
		$arSection["DELETE_LINK"] = $arButtons["edit"]["delete_section"]["ACTION_URL"];

		$arResult["SECTIONS"][$arSection["ID"]]=$arSection;
	}

//	foreach ($arResult["SECTIONS"] as &$arSection)
//	{
//		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection["IBLOCK_ID"], $arSection["ID"]);
//		$arSection["IPROPERTY_VALUES"] = $ipropValues->getValues();
//	}
//	unset($arSection);

	//if(!empty($arResult["SECTIONS"])) {
		$arResult["ITEMS"] = array();
		$rsI = \CIBlockElement::GetList(Array("SORT" => "ASC"), array(
			"ACTIVE" => "Y",
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
//			array(
//				"LOGIC" => "OR",
//				array("IBLOCK_SECTION_ID" => array_keys($arResult["SECTIONS"])),
//				array("!PROPERTY_MAIN" => false)
//			)
		));
		while ($obI = $rsI->GetNextElement()) {
			$arI = $obI -> GetFields();
			$arI["PROPERTIES"] = $obI -> GetProperties();
			
			if($arI["PREVIEW_PICTURE"]>0) {
				$arFileTmp = CFile::ResizeImageGet(
					$arI["PREVIEW_PICTURE"],
					array("width" => 591, 'height' => 244),
					BX_RESIZE_IMAGE_EXACT,
					false
				);
				
				$arI["TILE"] = $arFileTmp;
			}
			
			$rsSections = \CIBlockElement::GetElementGroups($arI["ID"], true, array("ID"));
			while($arSection = $rsSections->Fetch()) {
				$arResult["ITEMS"][$arSection["ID"]][] = $arI;
			}
			
			if(!empty($arI["PROPERTIES"]["MAIN"]["VALUE"])) {
				$arResult["MAIN"][] = $arI;
			}
		}
	//}

	$this->setResultCacheKeys(array(
		"SECTIONS_COUNT",
		"SECTION",
	));

	$this->includeComponentTemplate();
}

if($arResult["SECTIONS_COUNT"] > 0 || isset($arResult["SECTION"]))
{
	if(
		$USER->IsAuthorized()
		&& $APPLICATION->GetShowIncludeAreas()
		&& Loader::includeModule("iblock")
	)
	{
		$UrlDeleteSectionButton = "";
		if(isset($arResult["SECTION"]) && $arResult["SECTION"]['IBLOCK_SECTION_ID'] > 0)
		{
			$rsSection = CIBlockSection::GetList(
				array(),
				array("=ID" => $arResult["SECTION"]['IBLOCK_SECTION_ID']),
				false,
				array("SECTION_PAGE_URL")
			);
			$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
			$arSection = $rsSection->GetNext();
			$UrlDeleteSectionButton = $arSection["SECTION_PAGE_URL"];
		}

		if(empty($UrlDeleteSectionButton))
		{
			$url_template = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "LIST_PAGE_URL");
			$arIBlock = CIBlock::GetArrayByID($arParams["IBLOCK_ID"]);
			$arIBlock["IBLOCK_CODE"] = $arIBlock["CODE"];
			$UrlDeleteSectionButton = CIBlock::ReplaceDetailUrl($url_template, $arIBlock, true, false);
		}

		$arReturnUrl = array(
			"add_section" => (
				'' != $arParams["SECTION_URL"]?
				$arParams["SECTION_URL"]:
				CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_PAGE_URL")
			),
			"add_element" => (
				'' != $arParams["SECTION_URL"]?
				$arParams["SECTION_URL"]:
				CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_PAGE_URL")
			),
			"delete_section" => $UrlDeleteSectionButton,
		);
		$arButtons = CIBlock::GetPanelButtons(
			$arParams["IBLOCK_ID"],
			0,
			$arResult["SECTION"]["ID"],
			array("RETURN_URL" =>  $arReturnUrl, "CATALOG"=>true)
		);

		$this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
	}

	if($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["SECTION"]) && is_array($arResult["SECTION"]["PATH"]))
	{
		foreach($arResult["SECTION"]["PATH"] as $arPath)
		{
			if (isset($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
				$APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
			else
				$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		}
	}
}
