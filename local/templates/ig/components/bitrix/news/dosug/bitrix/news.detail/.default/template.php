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

$strImageAlt = $arResult["NAME"];

ob_start();
?>
<div class="section section--grey section--article section--fullheight text">
	<div class="container">
		<h1><?=$arResult["NAME"]?></h1>
		<div class="fcard fcard--project<?=(empty($arResult["PROPERTIES"]["VIDEO_URL"]["VALUE"])?' fcard--project-w-wrapping':'')?>">
			<div class="fcard__grid"><?
				if($arResult["DETAIL_PICTURE"]["ID"]>0 || !empty($arResult["SLIDER_IMAGES"])) { ?>
					<div class="fcard__image"><?
					if(!empty($arResult["SLIDER_IMAGES"])) {
						$strSliderHtml = \ig\CHelper::getSlider("#SLIDER#", array("IMAGES" => $arResult["SLIDER_IMAGES"]));
						
						echo $strSliderHtml;
					} elseif($arResult["DETAIL_PICTURE"]["ID"]>0) {
						$strImageAlt = $arResult["NAME"]; ?>
						<div class="image-slider image-slider--autosize image-slider--autosize-h js-images-hover-slider js-images-popup-slider cursor-pointer">
						<img class="active" data-lazy-src="<?=$arResult["TILE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" data-src-full="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$strImageAlt?>" data-ihs-content="<?=$arResult["DETAIL_PICTURE"]["DESCRIPTION"]?>">
						<div class="image-slider__size">
							<img src="<?=$arResult["TILE"]["src"]?>" alt="<?=$strImageAlt?>">
						</div>
						</div><?
					}?>
					</div><?
				}?>
				<div class="fcard__main"><?
					if(!empty($arResult["PROPERTIES"]["VIDEO_URL"]["VALUE"])) { ?>
						<div class="video-iframe">
						<?=$arResult["PROPERTIES"]["VIDEO_URL"]["~VALUE"]?>
						</div><?
					} else {?>
						<div class="p text">
						<?=$arResult["DETAIL_TEXT"]?>
						</div><?
					}?>
				</div>
			</div><?
			if(!empty($arResult["PROPERTIES"]["VIDEO_URL"]["VALUE"])) { ?>
				<div class="p text">
				<?=$arResult["DETAIL_TEXT"]?>
				</div><?
			}?>
		</div><?
		if(!empty($arResult["DISPLAY_PROPERTIES"]["FREE_TEXT"]["DISPLAY_VALUE"])) {
			echo $arResult["DISPLAY_PROPERTIES"]["FREE_TEXT"]["DISPLAY_VALUE"];
		}?>
	</div>
</div><?

$strContents = ob_get_contents();
ob_end_clean();

$strSliderHtml = '';
if(!empty($arResult["SLIDER_IMAGES"])) {
	$strSliderHtml = \ig\CHelper::getSlider("#SLIDER#", array("IMAGES" => $arResult["SLIDER_IMAGES"]));
}
$strContents = str_replace("#SLIDER#", $strSliderHtml, $strContents);
echo $strContents;