<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$arSortProp = $arResult["PROPERTIES"];

if(!empty($arSortProp["IS_RUSSIAN"]["VALUE"])) {
	$strName = $arResult["NAME"];
	$strName2 = $arSortProp["NAME_LAT"]["VALUE"];
} else {
	$strName = $arSortProp["NAME_LAT"]["VALUE"];
	$strName2 = $arResult["NAME"];
}

if(empty($strName)) {
	$strName = $strName2;
	$strName2 = '';
}

$strFormattedName = \ig\CFormat::formatPlantTitle(
	(!empty($arSortProp["IS_VIEW"]["VALUE"])?'':$strName),
	$arResult["SECTION"]["PATH"][0]["NAME"],
	$arResult["SECTION"]["PATH"][1]["NAME"]);

$strFormattedNameLat = \ig\CFormat::formatPlantTitle(
	(!empty($arSortProp["IS_VIEW"]["VALUE"])?'':$arSortProp["NAME_LAT"]["VALUE"]),
	$arResult["IBLOCK_SECTIONS"][$arResult["SECTION"]["PATH"][0]["ID"]]["UF_NAME_LAT"],
	$arResult["IBLOCK_SECTIONS"][$arResult["SECTION"]["PATH"][1]["ID"]]["UF_NAME_LAT"]
);

?>
<div class="panel panel--quickview">
	<? require ("main_info.php");?>
</div>