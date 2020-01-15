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
	$arResult["SECTION"]["PATH"][0]["UF_NAME_LAT"],
	$arResult["SECTION"]["PATH"][1]["UF_NAME_LAT"]
);

ob_start();

$GLOBALS["SIMILAR_ID"] = $arResult["ID"];
$APPLICATION->IncludeComponent(
	"ig:catalog.similar",
	"",
	Array(
		"GLOBAL_VARIABLE_NAME" => "SIMILAR_ID"
	)
);

$strSimilarProducts = ob_get_contents();
$strSimilarProducts = trim($strSimilarProducts);
ob_end_clean();

?>
<div class="section section--grey section--plant text">
	<div class="container"><?
require ('main_info.php');

?>
		<div id="specs" data-goto-offset-element=".header, .fcard__tabs" data-goto-offset-end="#specs-end"></div>
		
		<div class="h2">Особенности</div>
		<table class="ttable ttable--adaptive">
			<tbody><?
			if($arSortProp["IS_VIEW"]["VALUE"]) {
				$arTmp = array();
				if($arResult["SECTION"]["UF_SYNONYM_RUS"]) {
					$arTmp[] = $arResult["SECTION"]["UF_SYNONYM_RUS"];
				}
				
				if($arResult["SECTION"]["UF_SYNONYM_LAT"]) {
					$arTmp[] = $arResult["SECTION"]["UF_SYNONYM_LAT"];
				}
				
				if(!empty($arTmp)) {
					echo $this -> __component -> getParamRow('Синоним вида', implode(", ", $arTmp));
				}
			} else {
				$arTmp = array();
				if($arSortProp["SYNONYM_RUS"]["VALUE"]) {
					$arTmp[] = $arSortProp["SYNONYM_RUS"]["VALUE"];
				}
				
				if($arSortProp["SYNONYM_LAT"]["VALUE"]) {
					$arTmp[] = $arSortProp["SYNONYM_LAT"]["VALUE"];
				}
				
				if(!empty($arTmp)) {
					echo $this -> __component -> getParamRow('Синоним сорта', implode(', ', $arTmp));
				}
			}
			
			foreach($arResult["PROPERTIES"] as $strCode => $arProperty) {
				if(
					$arProperty["SORT"]<20000
						&&
					(
						$arProperty["DISPLAYED"] != 'Y'
							||
						(
							$arProperty["DISPLAYED"] == 'Y'
								&&
							$arProperty["CODE"] == 'GROUND'
						)
					)
				) {
					$strDisplayValue = \ig\CFormat::formatPropertyValue($arProperty["CODE"], $arProperty["VALUE"], $arProperty, array("IS_TABLE" => 'Y'));
					
					$strDisplayValue = trim($strDisplayValue);
					$strComment = $arResult["PROPERTIES"][$arProperty["CODE"].'_COMMENT']["VALUE"];
					if(
						mb_strlen($strDisplayValue)>0
					) {
						echo $this -> __component -> getParamRow($arProperty["NAME"], $strDisplayValue.(empty($strComment)?'':'<p>'.$strComment.'</p>'));
					}
				}
			}?>
			</tbody>
		</table>
		
		<div id="specs-end"></div><?
		if(!empty($arSortProp["IMPORTANT_INFO"]["VALUE"]["TEXT"])) { ?>
			<div class="ittgb">
				<div class="ittgb__grid">
					<div class="ittgb__content text"><?
						if($arSortProp["IMPORTANT_INFO"]["VALUE"]["TYPE"] == 'TEXT') {
							echo $arSortProp["IMPORTANT_INFO"]["VALUE"]["TEXT"];
						} else {
							echo htmlspecialcharsback($arSortProp["IMPORTANT_INFO"]["VALUE"]["TEXT"]);
						}?>
					</div>
				</div>
			</div><?
		}
		
		echo $strSimilarProducts;?>
	</div>
</div>