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
$this->setFrameMode(true); ?>
<div class="section section--grey section--article text">
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
		}
		
		if(!empty($arResult["MORE_NEWS"])) { ?>
			<h2>Ещё новости</h2>
			
			<div class="tgbs">
				<div class="tgbs__inner text-align-center"><?
					foreach($arResult["MORE_NEWS"] as $arItem) {?>
						<div class="tgb tgb--img-hover tgb--1-3">
						<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							
							<div class="tgb__image img-to-bg">
								<img data-lazy-src="<?=$arItem["IMAGE"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
							</div>
							
							<div class="tgb__overlay">
								<div class="tgb__overlay-part">
									<div class="tgb__overlay-part-inner">
										<div class="tgb__date"><?=substr($arItem["DATE_ACTIVE_FROM"], 0, 10)?></div>
										<div class="tgb__title h4"><?=$arItem["NAME"]?></div>
									</div>
								</div>
								<div class="tgb__overlay-part mobile-hide">
									<div class="tgb__overlay-part-inner">
										<div class="tgb__summary">
											<p><?=$arItem["PREVIEW_TEXT"]?></p>
										</div>
									</div>
								</div>
							</div>
						
						</a>
						</div><?
					}?>
				</div>
			</div><?
		}
		?>
	</div>
</div>