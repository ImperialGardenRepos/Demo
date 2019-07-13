<?php
$arResult["TOTAL_COUNT"] = CIBlockElement::GetList(Array("SORT" => "ASC"), array("ACTIVE" => "Y", "IBLOCK_ID" =>$arParams["IBLOCK_ID"]), array());


// get section
$rsSec = CIBlockSection::GetList(Array("SORT"=>"ASC", "NAME" => "ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]), true);
while($arSec = $rsSec -> GetNext()) {
	$arResult["SECTIONS"][] = $arSec;
}

// format images
foreach($arResult["ITEMS"] as &$arItem) {
	if($arItem["PREVIEW_PICTURE"]["ID"]>0) {
		$arFileTmp = CFile::ResizeImageGet(
			$arItem["PREVIEW_PICTURE"]["ID"],
			array("width" => 245, 'height' => 245),
			BX_RESIZE_IMAGE_EXACT,
			false
		);
		
		$arItem["TILE"]["SRC"] = $arFileTmp["src"];
	}
}
unset($arItem);