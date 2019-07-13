<?php
$obImageProcessor = new \ig\CImageResize;
foreach($arResult["ITEMS"] as &$arItem) {
	if($arItem["PREVIEW_PICTURE"]["ID"]>0) {
		$arItem["TILE"]["src"] = $obImageProcessor->getResizedImgOrPlaceholder($arItem["PREVIEW_PICTURE"]["ID"], 590, 295);
	}
}
unset($arItem);