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
?>
<div class="section section--grey section--plant">
	<div class="container"><?
require ('main_info.php');

$bDisplaySpecs = false;
ob_start();
?>
		<div id="specs" data-goto-offset-element=".header, .fcard__tabs" data-goto-offset-end="#specs-end"></div>
		
		<div class="h2">Особенности</div>
		<table class="ttable ttable--adaptive">
			<tbody><?
			foreach($arResult["PROPERTIES"] as $strCode => $arProperty) {
				if($arProperty["SORT"]<20000 && $arProperty["DISPLAYED"] != 'Y') {
					$strDisplayValue = \ig\CFormat::formatPropertyValue($arProperty["CODE"], $arProperty["VALUE"], $arProperty, array("COLOR_EXT" => "Y"));
					$strComment = $arResult["PROPERTIES"][$arProperty["CODE"].'_COMMENT']["VALUE"];
					if(strlen($strDisplayValue)>0) {
						$bDisplaySpecs = true;?>
					<tr>
						<th class="nowrap"><?=$arProperty["NAME"]?></th>
						<td><?
							echo $strDisplayValue;
							
							if(!empty($strComment)) {
								echo '<p>'.$strComment.'</p>';
							}?></td>
					</tr><?
					}
				}
			}?>
			</tbody>
		</table>
		<div id="specs-end"></div><?
		$strSpecs = ob_get_contents();
		ob_end_clean();
		
		if($bDisplaySpecs) {
			echo $strSpecs;
		}
		unset($strSpecs);
		
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
		}?>
	</div>
</div>
