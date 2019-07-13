<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypes = CIBlockParameters::GetIBlockTypes();

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypes,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BND_ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["ELEMENT_ID"]}',
		),
		"ELEMENT_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BND_ELEMENT_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"SET_CANONICAL_URL" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_SET_CANONICAL_URL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SET_BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_SET_BROWSER_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y"
		),
		"BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_BROWSER_TITLE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(array("-"=>" ", "NAME" => GetMessage("IBLOCK_FIELD_NAME")), $arProperty_LNS),
			"HIDDEN" => (isset($arCurrentValues['SET_BROWSER_TITLE']) && $arCurrentValues['SET_BROWSER_TITLE'] == 'N' ? 'Y' : 'N')
		),
		"SET_META_KEYWORDS" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_SET_META_KEYWORDS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		),
		"META_KEYWORDS" =>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_KEYWORDS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(array("-"=>" "),$arProperty_LNS),
			"HIDDEN" => (isset($arCurrentValues['SET_META_KEYWORDS']) && $arCurrentValues['SET_META_KEYWORDS'] == 'N' ? 'Y' : 'N')
		),
		"SET_META_DESCRIPTION" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_SET_META_DESCRIPTION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y"
		),
		"META_DESCRIPTION" =>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_DESCRIPTION"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(array("-"=>" "),$arProperty_LNS),
			"HIDDEN" => (isset($arCurrentValues['SET_META_DESCRIPTION']) && $arCurrentValues['SET_META_DESCRIPTION'] == 'N' ? 'Y' : 'N')
		),
		"SET_LAST_MODIFIED" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_SET_LAST_MODIFIED"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"ADD_SECTIONS_CHAIN" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_ADD_SECTIONS_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"ADD_ELEMENT_CHAIN" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_ADD_ELEMENT_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		),
		"STRICT_SECTION_CHECK" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BND_STRICT_SECTION_CHECK"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BND_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);