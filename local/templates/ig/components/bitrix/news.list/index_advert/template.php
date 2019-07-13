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

if(!empty($arResult["ITEMS"])) { ?>
	<div class="atgbs">
		<div class="atgbs__inner">
			<div class="swiper-slider swiper-slider--atgbs"
			     data-slider-slides-per-view="<?=(count($arResult["ITEMS"])>=3?3:count($arResult["ITEMS"]))?>"
			     data-slider-slides-per-group="1"
			     data-slider-speed="600"
			     data-slider-breakpoints='{"640": {"slidesPerView": 1}, "1024": {"slidesPerView": 1}}'>
				<div class="swiper-container">
					<div class="swiper-wrapper"><?
						foreach($arResult["ITEMS"] as $arItem) {
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							
							$strLink = $arItem["PROPERTIES"]["LINK"]["VALUE"];
							?>
						
						<div class="swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							
							<div class="atgb">
								<a class="atgb__inner"<?=(empty($strLink)?' onclick="return false;"':'')?> href="<?=(empty($strLink)?'#':$strLink)?>">
									<div class="atgb__grid">
										<div class="atgb__icon">
											<svg class="icon icon--speaker">
												<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#<?=$arItem["PROPERTIES"]["ICON"]["VALUE_XML_ID"]?>"></use>
											</svg>
										</div>
										
										<div class="atgb__content">
											<div class="atgb__subtitle"><?=$arItem["NAME"]?></div>
											<div class="atgb__title"><?=$arItem["PROPERTIES"]["TEXT"]["VALUE"]?></div>
										</div>
									</div>
								
								</a>
							</div>
						
						</div><?
						}?>
					</div>
				</div>
				<div class="swiper-button-prev">
					<svg class="icon">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
					</svg>
				</div>
				<div class="swiper-button-next">
					<svg class="icon">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-right"></use>
					</svg>
				</div>
			</div>
		</div>
	</div><?
}