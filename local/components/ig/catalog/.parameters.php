<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
/** @global CUserTypeManager $USER_FIELD_MANAGER */
use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager,
	Bitrix\Iblock,
	Bitrix\Catalog,
	Bitrix\Currency;

global $USER_FIELD_MANAGER;

if (!Loader::includeModule('iblock'))
	return;
$catalogIncluded = Loader::includeModule('catalog');

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$compatibleMode = !(isset($arCurrentValues['COMPATIBLE_MODE']) && $arCurrentValues['COMPATIBLE_MODE'] === 'N');

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$offersIblock = array();
if ($catalogIncluded)
{
	$iterator = Catalog\CatalogIblockTable::getList(array(
		'select' => array('IBLOCK_ID'),
		'filter' => array('!=PRODUCT_IBLOCK_ID' => 0)
	));
	while ($row = $iterator->fetch())
		$offersIblock[$row['IBLOCK_ID']] = true;
	unset($row, $iterator);
}

$arIBlock = array();
$iblockFilter = (
	!empty($arCurrentValues['IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
{
	$id = (int)$arr['ID'];
	if (isset($offersIblock[$id]))
		continue;
	$arIBlock[$id] = '['.$id.'] '.$arr['NAME'];
}
unset($id, $arr, $rsIBlock, $iblockFilter);
unset($offersIblock);



$arIBlock_LINK = array();
$iblockFilter = (
	!empty($arCurrentValues['LINK_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['LINK_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIblock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIblock->Fetch())
	$arIBlock_LINK[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
unset($iblockFilter);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"VARIABLE_ALIASES" => array(
			"ELEMENT_ID" => array(
				"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_ELEMENT_ID"),
			),
			"SECTION_ID" => array(
				"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_ID"),
			),

		),
		"AJAX_MODE" => array(),
		"SEF_MODE" => array(
			"sections" => array(
				"NAME" => GetMessage("SECTIONS_TOP_PAGE"),
				"DEFAULT" => "",
				"VARIABLES" => array(
				),
			),
			"section" => array(
				"NAME" => GetMessage("SECTION_PAGE"),
				"DEFAULT" => "#SECTION_ID#/",
				"VARIABLES" => array(
					"SECTION_ID",
					"SECTION_CODE",
					"SECTION_CODE_PATH",
				),
			),
			"element" => array(
				"NAME" => GetMessage("DETAIL_PAGE"),
				"DEFAULT" => "#SECTION_ID#/#ELEMENT_ID#/",
				"VARIABLES" => array(
					"ELEMENT_ID",
					"ELEMENT_CODE",
					"SECTION_ID",
					"SECTION_CODE",
					"SECTION_CODE_PATH",
				),
			),
			"compare" => array(
				"NAME" => GetMessage("COMPARE_PAGE"),
				"DEFAULT" => "compare.php?action=#ACTION_CODE#",
				"VARIABLES" => array(
					"action",
				),
			),
		),
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		"CACHE_FILTER" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("IBLOCK_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	),
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);

if($arCurrentValues["SEF_MODE"]=="Y")
{
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"] = array();
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["ELEMENT_ID"] = array(
		"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_ELEMENT_ID"),
		"TEMPLATE" => "#ELEMENT_ID#",
	);
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["ELEMENT_CODE"] = array(
		"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_ELEMENT_CODE"),
		"TEMPLATE" => "#ELEMENT_CODE#",
	);
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SECTION_ID"] = array(
		"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_ID"),
		"TEMPLATE" => "#SECTION_ID#",
	);
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SECTION_CODE"] = array(
		"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_CODE"),
		"TEMPLATE" => "#SECTION_CODE#",
	);
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SECTION_CODE_PATH"] = array(
		"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_CODE_PATH"),
		"TEMPLATE" => "#SECTION_CODE_PATH#",
	);
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SMART_FILTER_PATH"] = array(
		"NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SMART_FILTER_PATH"),
		"TEMPLATE" => "#SMART_FILTER_PATH#",
	);

	$smartBase = ($arCurrentValues["SEF_URL_TEMPLATES"]["section"]? $arCurrentValues["SEF_URL_TEMPLATES"]["section"]: "#SECTION_ID#/");
	$arComponentParameters["PARAMETERS"]["SEF_MODE"]["smart_filter"] = array(
		"NAME" => GetMessage("CP_BC_SEF_MODE_SMART_FILTER"),
		"DEFAULT" => $smartBase."filter/#SMART_FILTER_PATH#/apply/",
		"VARIABLES" => array(
			"SECTION_ID",
			"SECTION_CODE",
			"SECTION_CODE_PATH",
			"SMART_FILTER_PATH",
		),
	);
}

if ($compatibleMode)
{
	if (!ModuleManager::isModuleInstalled('forum'))
	{
		unset($arComponentParameters["PARAMETERS"]["USE_REVIEW"]);
		unset($arComponentParameters["GROUPS"]["REVIEW_SETTINGS"]);
	}
	elseif ($arCurrentValues["USE_REVIEW"] == "Y")
	{
		$arForumList = array();
		if (Loader::includeModule("forum"))
		{
			$rsForum = CForumNew::GetList();
			while ($arForum = $rsForum->Fetch())
				$arForumList[$arForum["ID"]] = $arForum["NAME"];
		}
		$arComponentParameters["PARAMETERS"]["MESSAGES_PER_PAGE"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_MESSAGES_PER_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => (int)COption::GetOptionString("forum", "MESSAGES_PER_PAGE", "10")
		);
		$arComponentParameters["PARAMETERS"]["USE_CAPTCHA"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_USE_CAPTCHA"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		);
		$arComponentParameters["PARAMETERS"]["REVIEW_AJAX_POST"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_REVIEW_AJAX_POST"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		);
		$arComponentParameters["PARAMETERS"]["PATH_TO_SMILE"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_PATH_TO_SMILE"),
			"TYPE" => "STRING",
			"DEFAULT" => "/bitrix/images/forum/smile/",
		);
		$arComponentParameters["PARAMETERS"]["FORUM_ID"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_FORUM_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arForumList,
			"DEFAULT" => "",
		);
		$arComponentParameters["PARAMETERS"]["URL_TEMPLATES_READ"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_READ_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);
		$arComponentParameters["PARAMETERS"]["SHOW_LINK_TO_FORUM"] = array(
			"PARENT" => "REVIEW_SETTINGS",
			"NAME" => GetMessage("F_SHOW_LINK_TO_FORUM"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		);
	}
}
else
{
	unset($arComponentParameters["PARAMETERS"]["USE_REVIEW"]);
	unset($arComponentParameters["GROUPS"]["REVIEW_SETTINGS"]);
	unset($arComponentParameters["PARAMETERS"]["LIST_OFFERS_LIMIT"]);
	if (isset($arComponentParameters["PARAMETERS"]["TOP_OFFERS_LIMIT"]))
		unset($arComponentParameters["PARAMETERS"]["TOP_OFFERS_LIMIT"]);
}