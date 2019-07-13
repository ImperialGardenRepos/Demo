<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


class PortfolioList extends \CBitrixComponent {
	public static function getProjectData($obElement) {
		$arItem = $obElement->GetFields();
		$arItem["PROPERTIES"] = $obElement->GetProperties();
		
		if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
			$arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);
		
		if(strlen($arItem["ACTIVE_TO"])>0)
			$arItem["DISPLAY_ACTIVE_TO"] = \CIBlockFormatProperties::DateFormat("Y", MakeTimeStamp($arItem["ACTIVE_TO"], CSite::GetDateFormat()));
		
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
		
		return $arItem;
	}
}