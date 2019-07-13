<?php

$arResult["MORE_OBJECT"] = array();
$rsI = CIBlockElement::GetList(Array("SORT" => "ASC"), array("ACTIVE" => "Y", "IBLOCK_ID" =>$arParams["IBLOCK_ID"], "!ID"=> $arResult["ID"], "!PROPERTY_MORE_PROJECTS"=>false), false, array("nTopCount"=>2));
while ($obElement = $rsI->GetNextElement()) {
	$arItem = $obElement->GetFields();
	$arItem["PROPERTIES"] = $obElement->GetProperties();
	
	if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
		$arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);
	
	if(strlen($arItem["ACTIVE_TO"])>0)
		$arItem["DISPLAY_ACTIVE_TO"] = \CIBlockFormatProperties::DateFormat("Y", MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()));
	
	\Bitrix\Iblock\InheritedProperty\ElementValues::queue($arItem["IBLOCK_ID"], $arItem["ID"]);
	
	$arItem["FIELDS"] = array();
	
	if ($arParams["SET_LAST_MODIFIED"])
	{
		$time = DateTime::createFromUserTime($arItem["TIMESTAMP_X"]);
		if (
			!isset($arResult["ITEMS_TIMESTAMP_X"])
			|| $time->getTimestamp() > $arResult["ITEMS_TIMESTAMP_X"]->getTimestamp()
		)
			$arResult["ITEMS_TIMESTAMP_X"] = $time;
	}
	
	$arResult["MORE_OBJECT"][] = $arItem;
}