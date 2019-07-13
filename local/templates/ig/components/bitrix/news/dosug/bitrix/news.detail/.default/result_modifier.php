<?php

if(!empty($arResult["PROPERTIES"]["SLIDER_IMAGES"]["VALUE"])) {
	foreach($arResult["PROPERTIES"]["SLIDER_IMAGES"]["VALUE"] as $intID) {
		$arResult["SLIDER_IMAGES"][] = \ig\CHelper::getSliderImageData($intID, array("RESIZE" => array("WIDTH"=>600, "HEIGHT"=>495)));
	}
} elseif($arResult["DETAIL_PICTURE"]["ID"]>0) {
	$obImageProcessor = new \ig\CImageResize;
	
	$arResult["TILE"]["src"] = $obImageProcessor->getResizedImgOrPlaceholder($arResult["DETAIL_PICTURE"]["ID"], 600, 495, array("WATERMARK" => 'N'));
	$arResult["DETAIL_PICTURE"]["SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($arResult["DETAIL_PICTURE"]["ID"], $arResult["DETAIL_PICTURE"]["WIDTH"]);
}