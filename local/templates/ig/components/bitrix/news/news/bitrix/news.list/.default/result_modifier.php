<?php

$strCurrentPage = $APPLICATION->GetCurDir();

$rsSec = CIBlockSection::GetList(
	Array("NAME"=>"DESC", "SORT" => "ASC"),
	array("ACTIVE"=>"Y", "IBLOCK_ID"=> $arParams["IBLOCK_ID"], "DEPTH_LEVEL" => 1),
	false,
	array("ID", "NAME", "SECTION_PAGE_URL", "CODE")
);

$intSelectedYear = 0;
while($arSec = $rsSec -> GetNext()) {
	if(strpos($strCurrentPage, $arSec["SECTION_PAGE_URL"]) === 0) {
		$arSec["SELECTED"] = true;
		$arResult['SELECTED_YEAR_ID'] = $arSec["ID"];
		$arResult['SELECTED_YEAR_NAME'] = $arSec["NAME"];
	}
	$arResult["YEARS"][] = $arSec;
}

if($arResult['SELECTED_YEAR_ID']>0) {
	$rsSec = CIBlockSection::GetList(
		Array("SORT" => "ASC","NAME"=>"ASC"),
		array("ACTIVE"=>"Y", "IBLOCK_ID"=> $arParams["IBLOCK_ID"], "DEPTH_LEVEL" => 2, "SECTION_ID"=>$arResult['SELECTED_YEAR_ID']),
		false,
		array("ID", "NAME", "SECTION_PAGE_URL", "CODE")
	);
	while($arSec = $rsSec -> GetNext()) {
		if($strCurrentPage == $arSec["SECTION_PAGE_URL"]) {
			$arSec["SELECTED"] = true;
			
			$arResult['SELECTED_MONTH_ID'] = $arSec["ID"];
			$arResult['SELECTED_MONTH_NAME'] = $arSec["NAME"];
		}
		$arResult["MONTH"][] = $arSec;
	}
}


foreach($arResult["ITEMS"] as &$arItem) {
	$intImageID = ($arItem["PREVIEW_PICTURE"]["ID"]>0?$arItem["PREVIEW_PICTURE"]["ID"]:$arItem["DETAIL_PICTURE"]["ID"]);
	
	if($intImageID>0) {
		$arFileTmp = CFile::ResizeImageGet($intImageID, array(
			"width"  => 590,
			'height' => 295
		), BX_RESIZE_IMAGE_EXACT, false);
		
		$arItem["PREVIEW_PICTURE"]["SRC"] = $arFileTmp["src"];
	}
}
unset($arItem);

if(!empty($arResult['SELECTED_MONTH_NAME'])) {
	$arResult["PAGE_TITLE"] = 'Новости за '.ToLower($arResult['SELECTED_MONTH_NAME']).' '.$arResult['SELECTED_YEAR_NAME'].' года';
} elseif($arResult['SELECTED_YEAR_NAME']) {
	$arResult["PAGE_TITLE"] = 'Новости за '.$arResult['SELECTED_YEAR_NAME'].' год';
} else {
	$arResult["PAGE_TITLE"] = 'Новости';
}

global $APPLICATION;

$cp = $this->__component; // объект компонента

if (is_object($cp)) {
	// добавим в arResult компонента два поля - MY_TITLE и IS_OBJECT
	$cp->arResult['PAGE_TITLE'] = $arResult["PAGE_TITLE"];
	$cp->SetResultCacheKeys(array('PAGE_TITLE'));
}