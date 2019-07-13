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

if(empty($arResult["ITEMS"])) { ?>
	<div class="header__popover-content js-scrollbar">
		
		<div class="header__popover-middle text-align-center">
			<div class="header__popover-block">
				<p>Ваша корзина пуста.<br>
					Наполните её <a href="/katalog/rasteniya/">растениями из каталога</a></p>
			</div>
		</div>
	
	</div><?
} else { ?>
	<div class="header__popover-content js-scrollbar">
		<div class="icards icards--compact">
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
					
					$arImages = \ig\CHelper::getImagesArray($arOffer, $arSort, array(
						"RESIZE" => array("WIDTH" => 90, "HEIGHT" => 90),
						"CNT" => 1
					));
					$arImage =\ig\CUtil::getFirst($arImages);
					?>
					<div class="icard icard--compact js-icard-use-discounts js-icard js-icard-to-remove" data-offer-index="0" data-offers-url="ajax/icard-offers.php">
						<div class="icard__inner">
							<div class="icard__content">
								<div class="icard__header">
									<div class="icard__title"><a href="<?=$arSort["DETAIL_PAGE_URL"]?>" class="link--bordered-pseudo"><?=$strFormattedName?></a></div><?
									if(!empty($strFormattedNameLat)) {?>
									<div class="icard__subtitle"><?=$strFormattedNameLat?></div><?
									}?>
								</div>
								<div class="icard__main">
									<div class="icard__main-grid"><?
										if($arImage["RESIZE"]["src"]) {?>
											<div class="icard__image">
												<div class="icard__image-inner js-icard-offer-images">
													<div class="icard__image-item image-slider active image-slider--noshadow">
														<img class="active" data-lazy-src="<?=$arImage["RESIZE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" data-src-full="<?=$arImage["SRC"]?>" data-ihs-content="<?=$strName?>" alt="<?=$strName?>">
													</div>
												</div>
											</div><?
										}
										
										if($arSort["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode('catalog')) {?>
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
		</div>
	</div>
	<div class="header__popover-footer">
		
		<div class="header__popover-footer-inner"><?
			if(false) {?>
			<div class="header__popover-message header__popover-message--dark text-align-center">
				<svg class="icon icon--exclamation">
					<use xlink:href="build/svg/symbol/svg/sprite.symbol.svg#icon-exclamation"></use>
				</svg>
				<span>До скидки <strong>10%</strong> нужно набрать на 20 000 ₽</span>
			</div><?
			}?>
			<div class="header__popover-message text-align-center">Товаров в корзине: <?=count($arResult["ITEMS"])?>. На сумму: <strong><?=\ig\CFormat::getFormattedPrice($arResult["CART_SUMM"], "RUB", array("RUB_SIGN" => "₽"))?></strong></div>
		</div>
		<div class="header__popover-footer-action">
			<a href="/personal/order/make/" class="btn btn--medium btn--norounded btn--fullwidth active">
				<svg class="icon icon--pen-plus">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-pen-plus"></use>
				</svg>
				<span>Оформить заказ</span>
			</a>
		</div>
	</div>
	<?
}
