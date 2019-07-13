<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("highload");

if(!$USER -> IsAuthorized()) {
	$APPLICATION->AuthForm("У вас нет права доступа.");
} else {
	if(!$USER -> IsAdmin() && !in_array(12, CUser::GetUserGroup($USER -> GetID()))) {
		$APPLICATION->AuthForm("У вас нет права доступа.");
	}
}


$arResult = array();

$arOffersPropertyGroups = array(
	"HEIGHT_NOW_EXT" => array(
		"NAME" => "Высота сейчас ext",
		"LINKS" => array(1, 4, 5, 9, 10, 11, 12),
		"REQUIRED" => 1
	),
//	"CONTAINER_SIZE" => array(
//		"NAME" => "Размер контейнера (см.)",
//		"LINKS" => array(2),
//		"REQUIRED" => 1
//	)
);

$rsGroup = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("Group"), array("UF_ACTIVE" => 1), array("UF_NAME", "ID", "UF_XML_ID"), array(), true);
while($arGroup = $rsGroup -> Fetch()) {
	$arResult["GROUP"][$arGroup["UF_XML_ID"]] = $arGroup["ID"];
}

$arSelect = array(
	"ID",
	"IBLOCK_ID",
	"NAME",
	"PROPERTY_CML2_LINK",
	"PROPERTY_CML2_LINK.PROPERTY_GROUP",
//	"PROPERTY_CONTAINER_SIZE",
	"PROPERTY_HEIGHT_NOW_EXT",
	"PROPERTY_PACK",
	"PROPERTY_CONTAINER"
);
$arBaseFilter = array(
	"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('offers'), array(
	//"LOGIC" => "OR",
	"ACTIVE" => "Y"
//	array(
//		"!PROPERTY_PACK" => false
//	),
//	array(
//		"!PROPERTY_CONTAINER_SIZE" => false
//	),
//	array(
//		"!PROPERTY_HEIGHT_NOW_EXT" => false
//	)
));

// offers
$rsI = CIBlockElement::GetList(Array("NAME" => "ASC"), $arBaseFilter, false, false, $arSelect);
while ($arI = $rsI->Fetch()) {
	$intGroupID = $arResult["GROUP"][$arI["PROPERTY_CML2_LINK_PROPERTY_GROUP_VALUE"]];
	
	$arOffersError = array();
//	if($intGroupID==2 && empty($arI["PROPERTY_CONTAINER_SIZE_VALUE"])) {
//		$arOffersError[] = 'CONTAINER_SIZE';
//	}
	
	if($intGroupID>0) {
		if(in_array($intGroupID, $arOffersPropertyGroups["HEIGHT_NOW_EXT"]["LINKS"]) && empty($arI["PROPERTY_HEIGHT_NOW_EXT_VALUE"])) {
			$arOffersError[] = 'HEIGHT_NOW_EXT';
		}
	}
	
	if($arI["PROPERTY_PACK_ENUM_ID"] == 60) {
		if(empty($arI["PROPERTY_CONTAINER_VALUE"])) {
			$arOffersError[] = 'CONTAINER';
		}
	}
	
	if(!empty($arOffersError)) {
		$arResult["OFFERS"][$arI["PROPERTY_CML2_LINK_VALUE"]][] = '<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arI["IBLOCK_ID"].'&type=catalog&ID='.$arI["ID"].'&lang=ru&find_section_section=-1&WF=Y">&mdash;'.$arI["NAME"].' ['.implode(', ', $arOffersError).']</a>';
	}
}

// catalog items
$arBaseFilter = array("ACTIVE" => "Y", "IBLOCK_ID" =>\ig\CHelper::getIblockIdByCode('catalog'));
$arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_GROUP");

$arSubFilter = array("LOGIC" => "OR");
$rsPropertyGroup = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PropertyGroup"), array("UF_ACTIVE" => 1), array("UF_NAME", "ID", "UF_CODE", "UF_POINTER", "UF_REQUIRED"), array(), true);
while($arPropertyGroup = $rsPropertyGroup -> Fetch()) {
	if($arPropertyGroup["UF_REQUIRED"] == 1) {
		//$arSubFilter[] = array("!PROPERTY_".$arPropertyGroup["UF_CODE"] => false);
		$arSelect[] = 'PROPERTY_'.$arPropertyGroup["UF_CODE"];

		foreach($arPropertyGroup["UF_POINTER"] as $intGroupID) {
			$arResult["TREE"][$intGroupID][] = $arPropertyGroup["UF_CODE"];
		}
	}
}
//$arBaseFilter[] = $arSubFilter;

$strResult = '';
$rsI = CIBlockElement::GetList(Array("NAME" => "ASC"), $arBaseFilter, false, false, $arSelect);
while ($arI = $rsI->Fetch()) {
	$intGroupID = $arResult["GROUP"][$arI["PROPERTY_GROUP_VALUE"]];

	$arError = array();
	if($intGroupID) {
		foreach($arResult["TREE"][$intGroupID] as $strPropertyCode) {
			if(empty($arI["PROPERTY_".$strPropertyCode."_VALUE"])) {
				$arError[] = $strPropertyCode;
			}
		}
	} else $arError[] = 'GROUP';
	
	if(!empty($arError) || isset($arResult["OFFERS"][$arI["ID"]])) {
		$strResult .= '<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arI["IBLOCK_ID"].'&type=catalog&ID='.$arI["ID"].'&lang=ru&find_section_section=-1&WF=Y">'.$arI["NAME"].' ['.implode(", ", $arError).']</a><br>'.implode("<br>", $arResult["OFFERS"][$arI["ID"]]).'<br><br>';
	}
}

echo $strResult;


//if(!empty($arResult["ITEMS"])) {
//	$rsP = CIBlockElement::GetList(Array("NAME" => "ASC"), array(
//		"ACTIVE" => "Y",
//		"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("catalog")), false, false, array(
//		"ID", "IBLOCK_ID",
//	));
//	while ($arI = $rsI->GetNext()) {
//		foreach($arResult["TREE"][$intGroupID] as $strPropertyCode) {
//			if(strlen($arI["PROPERTY_".$strPropertyCode."_VALUE"]) == 0) {
//				$arResult["ITEMS"][$arI["PROPERTY_CML2_LINK_VALUE"]][] = '<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arI["IBLOCK_ID"].'&type=catalog&ID='.$arI["ID"].'&lang=ru&find_section_section=-1&WF=Y">'.$arI["NAME"].' ['.$strPropertyCode.']</a>';
//
//				break;
//			}
//		}
//	}
//}
