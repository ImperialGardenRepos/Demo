<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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

$strTitle = 'Аналоги ';
$strTitle .= (empty($arResult["ROD"]["UF_NAME_ROD"])?$arResult["ROD"]["NAME"]:$arResult["ROD"]["UF_NAME_ROD"]).' ';
$strTitle .= (empty($arResult["VID"]["UF_NAME_ROD"])?$arResult["VID"]["NAME"]:$arResult["VID"]["UF_NAME_ROD"]).' ';

if(empty($arResult["SORT"]["PROPERTIES"]["IS_VIEW"]["VALUE"])) {
	if(!empty($arResult["SORT"]["PROPERTIES"]["IS_RUSSIAN"]["VALUE"])) {
		$strTitle .= "'".$arResult["SORT"]["NAME"]."'";
	} else {
		$strTitle .= "'".$arResult["SORT"]["PROPERTIES"]["NAME_LAT"]["VALUE"]."'";
	}
}

if($arResult["ITEMS"]) { ?>
	<div id="related" data-goto-offset-element=".header, .fcard__tabs" data-goto-offset-end="#related-end"></div>
	<h2><?=$strTitle?></h2>
	<div class="rcards">
		<div class="rcards__inner">
			<div class="swiper-slider swiper-slider--rcards"
			     data-slider-slides-per-view="4"
			     data-slider-slides-per-group="1"
			     data-slider-speed="600"
			     data-reinitialize-on-resize="true"
			     data-slider-breakpoints='{"640": {"slidesPerView": 3, "loop": true, "centeredSlides": true}, "9999": {"slidesPerView": 4, "loop": false, "centeredSlides": false}}'>
				<div class="swiper-container">
					<div class="swiper-wrapper"><?
						foreach($arResult["ITEMS"] as $arSort) {
							$arOffer = $arSort["OFFER"];
							
							$arSortProp = $arSort["PROPERTIES"];
							$arOfferProp = $arOffer["PROPERTIES"];
							
							$arImage =  \ig\CUtil::getFirst(\ig\CHelper::getImagesArray($arOffer, $arSort, array("CNT" => 1, "RESIZE" => array("WIDTH"=>285, "HEIGHT"=>285))));
							
							if(!empty($arSortProp["IS_RUSSIAN"]["VALUE"])) {
								$strName = $arSort["NAME"];
								$strName2 = $arSortProp["NAME_LAT"]["VALUE"];
							} else {
								$strName = $arSortProp["NAME_LAT"]["VALUE"];
								$strName2 = $arSort["NAME"];
							}
							
							if(empty($strName)) {
								$strName = $strName2;
								$strName2 = '';
							}
							
							$arSectionNav = $arResult["SECTIONS"][$arSort["IBLOCK_SECTION_ID"]]["NAV"];
							$strGroup = $arResult["OFFER_PARAMS"]["GROUP"][$arSortProp["GROUP"]["VALUE"]]["NAME"];
							
							$strFormattedName =\ig\CFormat::formatPlantTitle(
								(empty($arSortProp["IS_VIEW"]["VALUE"])?$strName:''),
								$arSectionNav[0]["NAME"],
								$arSectionNav[1]["NAME"]
							);
							$strFormattedNameLat =\ig\CFormat::formatPlantTitle(
								(empty($arSortProp["IS_VIEW"]["VALUE"])?$arSortProp["NAME_LAT"]["VALUE"]:''),
								$arResult["IBLOCK_SECTIONS"][$arSectionNav[0]["ID"]]["UF_NAME_LAT"],
								$arResult["IBLOCK_SECTIONS"][$arSectionNav[1]["ID"]]["UF_NAME_LAT"]
							); ?>
						<div class="swiper-slide">
							<div class="rcards__item">
								<div class="rcard">
									<a class="rcard__image" href="<?=$arSort["DETAIL_PAGE_URL"]?>">
										<img data-lazy-src="<?=$arImage["RESIZE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt=""> </a>
									<div class="rcard__content">
										<div class="rcard__title text-ellipsis-2 color-active">
											<a target="_blank" href="<?=$arSort["DETAIL_PAGE_URL"]?>" class="link--bordered-pseudo"><?=$strFormattedName?></a>
										</div>
										<div class="rcard__subtitle text-ellipsis"><?=$strFormattedNameLat?>&nbsp;</div>
										<div class="rcard__price"><span class="font-light">от</span>
											<span class="font-bold"><?=\ig\CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], 'RUB', array("RUB_SIGN" => ''))?></span> <span class="font-light">₽</span>
										</div>
										<div class="rcard__quickview">
											<a href="#" class="link--dotted" data-fancybox data-type="ajax" data-src="<?=$arSort["DETAIL_PAGE_URL"]?>">
												<svg class="icon icon--zoom">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
												</svg>
												Быстрый просмотр
											</a>
										</div>
									</div>
								</div>
							</div>
						</div><?
						}?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="related-end"></div><?
}