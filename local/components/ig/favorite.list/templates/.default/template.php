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
?>
<div class="header__popover-content js-scrollbar"><?
	if(!empty($arResult["ITEMS"])) { ?>
		<div class="icards  icards--compact">
			<div class="icards__inner"><?
				foreach($arResult["ITEMS"] as $arOffer) {
					$arOfferProp = $arOffer["PROPERTIES"];
					
					$arSort = $arResult["SORT"][$arOfferProp["CML2_LINK"]["VALUE"]];
					$arSortProp = $arSort["PROPERTIES"];
					
					if($arOffer["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode('offers')) {
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
					} else {
						$strName = $arSort["NAME"];
						$strName2 = '';
					}
					
					if($arSort["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode('catalog')) {
						$strFormattedName = \ig\CFormat::formatPlantTitle((empty($arSortProp["IS_VIEW"]["VALUE"]) ? $strName : ''), $arSectionNav[0]["NAME"], $arSectionNav[1]["NAME"]);
					} else {
						$strFormattedName = $arSort["NAME"];
					}
					
					if($arOffer["DETAIL_PICTURE"]>0)
						$intImage = $arOffer["DETAIL_PICTURE"];
					elseif($arSort["DETAIL_PICTURE"]>0)
						$intImage = $arSort["DETAIL_PICTURE"];
					
					$arPreview = array();
					if($intImage>0) {
						$arPreview = CFile::ResizeImageGet($intImage, array(
							"width"  => 246,
							'height' => 246
						), BX_RESIZE_IMAGE_PROPORTIONAL, false);
					}
					?>
					<div class="icard icard--compact js-icard js-favorites-to-remove" data-offer-index="0" data-offers-url="ajax/icard-offers.php">
						<div class="icard__inner">
							
							<div class="icard__content">
								
								<div class="icard__header">
									<div class="icard__title"><a href="<?=$arSort["DETAIL_PAGE_URL"]?>" class="link--bordered-pseudo"><?=$strFormattedName?></a></div>
									
									<div class="icard__subtitle"><?=$strName2?></div>
								</div>
								
								<div class="icard__main">
									<div class="icard__main-grid"><?
										if($arPreview["src"]) {?>
										<div class="icard__image">
											<div class="icard__image-inner js-icard-offer-images">
												<div class="icard__image-item image-slider active image-slider--noshadow">
													<img class="active" data-lazy-src="<?=$arPreview["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" data-src-full="<?=CFile::GetPath($intImage)?>" data-ihs-content="<?=$strName?>" alt="<?=$strName?>">
												
												</div>
											</div>
										</div><?
										}
					
										if($arSort["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode('catalog')) {
										?>
										<div class="icard__main-col icard__main-col--features">
											<div class="ptgbs ptgbs--features">
												<?=\ig\CFormat::formatListMainProperties($arSort);?>
											</div>
										</div><?
										}?>
										<div class="icard__main-col">
											<div class="icard__pa">
												<div class="icard__params">
													<div class="icard__offers-params-wrapper">
														<div class="icard__offers-params js-icard-offer-params"><?
															echo $this -> __component -> getParamsBlockHtml($arSort, $arOffer);?>
														</div>
													</div>
												</div>
												<div class="icard__actions">
													<div class="icard__offers-actions-wrapper">
														<div class="icard__offers-actions js-icard-offer-actions"><?
																echo $this -> __component -> getActionsBlockHtml($arSort, $arOffer);?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><?
				}?>
			</div>
		</div><?
	} else { ?>
		<div class="header__popover-block text-align-center">
			<p>Список избранного пуст.<br> Сохраняйте товары в&nbsp;избранном<br> с&nbsp;помощью кнопки
				<svg class="icon icon--heart vmiddle color-active">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
				</svg>
				<a href="/katalog/rasteniya/" class="link--ib link--bordered">в каталоге</a></p>
		</div><?
	}?>
</div>
<?
if(!empty($arResult["ITEMS"])) { ?>
<div class="header__popover-footer">
	<div class="header__popover-footer-inner">
		<div class="header__popover-message text-align-center">Товаров в избранном: <?=count($arResult["ITEMS"])?></div>
	</div><?
	if(true) {?>
	<div class="header__popover-footer-action">
		<a href="/izbrannoe/" class="btn btn--medium btn--norounded btn--fullwidth active">
			<svg class="icon icon--heart-outline">
				<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
			</svg>
			<span>Перейти в избранное</span> </a>
	</div><?
	}?>
</div><?
}