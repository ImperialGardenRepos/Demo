<?php

if($arResult["DETAIL_PICTURE"]["ID"]>0) {
	$obImageProcessor = new \ig\CImageResize;
	
	$arResult["TILE"]["src"] = $obImageProcessor->getResizedImgOrPlaceholder($arResult["DETAIL_PICTURE"]["ID"], 600, 365);
	$arResult["DETAIL_PICTURE"]["SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($arResult["DETAIL_PICTURE"]["ID"], $arResult["DETAIL_PICTURE"]["WIDTH"]);
}

if(!empty($arResult["PROPERTIES"]["SLIDER_IMAGES"]["VALUE"])) {
	foreach($arResult["PROPERTIES"]["SLIDER_IMAGES"]["VALUE"] as $intID) {
		$arResult["SLIDER_IMAGES"][] = \ig\CHelper::getSliderImageData($intID, array("RESIZE" => array("WIDTH"=>1200, "HEIGHT"=>505)));
	}
}