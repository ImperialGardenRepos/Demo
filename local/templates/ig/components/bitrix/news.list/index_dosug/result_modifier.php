<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach($arResult["ITEMS"] as &$arItem) {
	if($arItem["PROPERTIES"]["INDEX_IMAGE"]["VALUE"]>0) {
		if(empty($arItem["PROPERTIES"]["INDEX_ROW"]["VALUE"])) {
			$intWidth = 400;
			$intHeight = 420;
		} else {
			$intWidth = 1200;
			$intHeight = 400;
		}
		
		$arFileTmp = CFile::ResizeImageGet(
			$arItem["PROPERTIES"]["INDEX_IMAGE"]["VALUE"],
			array("width" => $intWidth, 'height' => $intHeight),
			BX_RESIZE_IMAGE_EXACT,
			false
		);
		
		$arItem["TILE"] = $arFileTmp;
	}
}

unset($arItem);