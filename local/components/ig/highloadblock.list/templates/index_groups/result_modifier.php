<?php
$intBlockID = \ig\Highload\Base::getHighloadBlockIDByName("VirtualPages");

foreach ($arResult["ITEMS"] as &$arItem) {
	$arHLData = \ig\Highload\Base::getFirst($intBlockID, array("=UF_PARAMS" => 'F%5BPROPERTY_GROUP%5D='.$arItem["UF_XML_ID"].'&frmCatalogFilterSent=Y'), array("ID", "UF_URL"));
	
	if($arHLData["ID"]>0) {
		$arItem["URL"] = $arHLData["UF_URL"];
	}
}

unset($arItem);