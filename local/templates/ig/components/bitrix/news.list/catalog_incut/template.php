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
	<div class="incut">
		<h2>Полезные статьи</h2>
		<div class="tgbs">
			<div class="tgbs__inner">
				
				<div class="swiper-slider swiper-slider--tgbs"
				     data-slider-slides-per-view="4"
				     data-slider-slides-per-group="1"
				     data-slider-speed="600"
				     data-reinitialize-on-resize="true"
				     data-slider-breakpoints='{"640": {"slidesPerView": 3, "loop": true, "centeredSlides": true}, "9999": {"slidesPerView": 4, "loop": false, "centeredSlides": false}}'>
					
					<div class="swiper-container">
						<div class="swiper-wrapper"><?
							foreach($arResult["ITEMS"] as $arItem) {
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								
								if(!empty($arItem["PREVIEW_PICTURE"]["SRC"])) {
									$strImage = $arItem["PREVIEW_PICTURE"]["SRC"];
								} elseif(!empty($arItem["DETAIL_PICTURE"]["SRC"])) {
									$strImage = $arItem["DETAIL_PICTURE"]["SRC"];
								} else {
									$strImage = '';
								}
								?>
								<div class="swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<div class="tgb tgb--img-hover tgb--article">
										<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?
											if(!empty($strImage)) { ?>
												<div class="tgb__image img-to-bg">
												<img data-lazy-src="<?=$strImage?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="<?=$arItem["NAME"]?>">
												</div><?
											}?>
											
											<div class="tgb__overlay"><?
												if(!empty($arItem["DISPLAY_ACTIVE_FROM"])) { ?>
													<div class="tgb__date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div><?
												}?>
												<div class="tgb__title h4"><span class="link link--bordered-pseudo"><?=$arItem["NAME"]?></span>
												</div><?
												if(!empty($arItem["PREVIEW_TEXT"])) { ?>
													<div class="tgb__summary">
														<?=$arItem["PREVIEW_TEXT"]?>
													</div><?
												}?>
											</div>
										
										</a>
									</div>
								</div><?
							}?>
						</div>
					</div>
				
				</div>
			
			</div>
		</div>
	</div><?
}