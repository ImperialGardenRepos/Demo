<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach($arResult["ITEMS"] as &$arItem) {
	if($arItem["PREVIEW_PICTURE"]["ID"]>0) {
		if(empty($arItem["PROPERTIES"]["IS_ROW"]["VALUE"])) {
			$intWidth = 300;
		} else {
			$intWidth = 600;
		}
		
		$arFileTmp = CFile::ResizeImageGet(
			$arItem["PREVIEW_PICTURE"]["ID"],
			array("width" => $intWidth, 'height' => 280),
			BX_RESIZE_IMAGE_EXACT,
			false
		);
		
		$arItem["TILE"] = $arFileTmp;
	}
}

unset($arItem);