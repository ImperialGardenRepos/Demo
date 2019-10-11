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
<div class="section section--grey section--cart">
	<div class="container">
		<h1><?=$APPLICATION->GetTitle()?></h1>
		<div class="ccards js-icards-favorites" data-location-if-empty="<?=$APPLICATION->GetCurDir()?>"><?
			if(!empty($arResult["ITEMS"])) {?>
					<div class="ccards__inner"><?
						foreach($arResult["ITEMS"] as $arOffer) {
							$arOfferProp = $arOffer["PROPERTIES"];
							
							$arSort = $arResult["SORT"][$arOfferProp["CML2_LINK"]["VALUE"]];
							$arSortProp = $arSort["PROPERTIES"];
							
							if($arSort["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode('catalog')) {
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
								
								$strGroup = $arResult["OFFER_PARAMS"]["GROUP"][$arSortProp["GROUP"]["VALUE"]]["NAME"];
							} else {
								$strName = $arSort["NAME"];
								$strGroup = '';
							}
							
							$arSectionNav = $arResult["SECTIONS"][$arSort["IBLOCK_SECTION_ID"]]["NAV"];
							
							$strFormattedName = \ig\CFormat::formatPlantTitle(
								(!empty($arSortProp["IS_VIEW"]["VALUE"])?'':$strName),
								$arSectionNav[0]["NAME"],
								$arSectionNav[1]["NAME"]);
							
							$floatBasePrice = (empty($arOffer["BASE_PRICE_VALUE"])?$arOffer["MIN_PRICE_VALUE"]:$arOffer["BASE_PRICE_VALUE"]);
							$floatMinPrice = (empty($arBasketItem["PRICE"])?$arBasketItem["BASE_PRICE"]:$arBasketItem["PRICE"]);
							
//							$floatBasePrice = $arOffer["BASE_PRICE_VALUE"];
//							$floatMinPrice = (empty($arBasketItem["DISCOUNT_PRICE"])?$arBasketItem["BASE_PRICE"]:$arBasketItem["DISCOUNT_PRICE"]);
							
							if(empty($floatMinPrice)) $floatMinPrice = $floatBasePrice;
							
							if($floatBasePrice == $floatMinPrice) {
								unset($floatBasePrice);
							}
							
							$strTag = '';
							if(!empty($arSortProp["RECOMMENDED"]["VALUE"])) {
								$strTag .= '<div class="tag">Рекомендация ig</div>';
							}

							
							if(!empty($arSortProp["NEW"]["VALUE"])) {
								$strTag .= '<div class="tag">Новинка</div>';
							}
							
							$arImages = \ig\CHelper::getImagesArray($arOffer, $arSort, array(
								"RESIZE" => array("WIDTH" => 140, "HEIGHT" => 140), "CNT" => 1
							));
							
							$arImageData = \ig\CUtil::getFirst($arImages);
							
							
							$strImage = '';
							if(!empty($arImageData)) {
								$strImage .= '<img class="active" data-lazy-src="'.$arImageData["RESIZE"]["src"].'" src="'.SITE_TEMPLATE_PATH.'/img/blank.png" data-src-full="'.$arImageData["SRC"].'" data-ihs-content="'.$strTitle.'" alt="'.$strTitle.'">';
							} else{
								$strImage = '<img class="active" src="'.SITE_TEMPLATE_PATH.'/img/blank.png" alt="'.$arSort["NAME"].'">';
							}
							?>
						<div class="ccard js-icard js-icard-use-discounts ccard--favorites js-favorites-to-remove">
							<div class="ccard__inner js-icard-offer-actions">
								<div class="ccard__content" data-id="<?=$arOffer["ID"]?>" data-cart-data='<?=json_encode(array("offerID" => intval($arOffer["ID"]), "getDiscount" => 'Y'))?>'>
									<div class="ccard__header">
										<div class="cols-wrapper">
											<div class="cols cols--auto">
												<div class="col">
													<div class="breadcrumb"><?
														if(false) { ?>
															<a href="<?=$arResult["OFFER_PARAMS"]["GROUP"][$arSortProp["GROUP"]["VALUE"]]["URL"]?>"><?=$strGroup?></a><?
														}
														
														if(!empty($strGroup)) {?>
														<?=$strGroup?>
														<svg class="icon icon--long-arrow">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
														</svg><?
														}?>
														<a target="_blank" href="<?=$arSectionNav[0]["SECTION_PAGE_URL"]?>"><?=$arSectionNav[0]["NAME"]?></a><?
														if(isset($arSectionNav[1])) {?>
														<svg class="icon icon--long-arrow">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
														</svg>
														<a target="_blank" href="<?=$arSectionNav[1]["SECTION_PAGE_URL"]?>"><?=$arSectionNav[1]["NAME"]?></a><?
														}?>
													</div>
												</div><?
												if(!empty($strTag)) { ?>
												<div class="col text-align-right">
													<div class="tags"><?=$strTag?></div>
												</div><?
												}?>
											</div>
										</div>
										<div class="ccard__title">
											<a target="_blank" href="<?=$arSort["DETAIL_PAGE_URL"]?>" class="link--ib link--dotted" data-fancybox data-type="ajax" data-src="<?=$arSort["DETAIL_PAGE_URL"]?>"><?=$strFormattedName?></a>
										</div>
									</div>
									
									<div class="ccard__main">
										<div class="ccard__image image-slider image-slider--noshadow">
											<?=$strImage?>
										</div>
										<div class="ccard__pa">
											
											<div class="ccard__params"><?
												if(!empty($strTag)) { ?>
												<div class="tags mobile-show">
													<?=$strTag?>
												</div><?
												}?>
												<div class="ptgbs ptgbs--params">
													<?
													if(!empty($arOfferProp["SIZE"]["VALUE"])) { ?>
													<div class="ptgb ptgb--param-height">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Объем</div>
																<div class="ptgb__title"><?=$arOfferProp["SIZE"]["VALUE"].' '.$arOfferProp["UNIT"]["VALUE"]?></div>
															</div>
														</div>
													</div><?
													}
													
													if(!empty($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])) {?>
													<div class="ptgb ptgb--param-height">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Высота (cм)</div>
																<div class="ptgb__title"><?=\ig\CHelper::getGroupPropertiesValues($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])["UF_NAME"]?></div>
															</div>
														</div>
													</div><?
													}
							
													if($arOfferProp["CROWN_WIDTH"]["VALUE"]>0) {?>
													<div class="ptgb ptgb--param-width tablet-hide">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Ширина кроны (см)</div>
																<div class="ptgb__title"><?=$arOfferProp["CROWN_WIDTH"]["VALUE"]?></div>
															</div>
														</div>
													</div><?
													} ?>
													<div class="ptgb ptgb--param-pack"><?
													if(!empty($arOfferProp["PACK"]["VALUE"])) {?>
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Упаковка</div>
																<div class="ptgb__title">
																	<div class="ptgb__title-inner"><?=$arOfferProp["PACK"]["VALUE"]?></div>
																</div>
															</div>
														</div><?
													}?>
													</div>
													<div class="ptgb ptgb--param-price">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Цена шт.</div>
																
																
																<div class="ptgb__title">
																	<div class="js-icard-price-discount-wrapper<?=($floatBasePrice>0?'':' hidden')?>">
																		<div class="ccard__price color-active"><span class="font-bold js-icard-price-discount"><?=\ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN"=>''))?></span> <span class="font-light">₽</span></div>
																	</div>
																	<div class="js-icard-price-wrapper<?=($floatBasePrice>0?' hidden':'')?>">
																		<div class="ccard__price">
																			<span class="font-bold js-icard-price">
																				<?=\ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN"=>''))?>
																			</span>
																			<span class="font-light">₽</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<div class="ccard__actions">
												<div class="ptgbs ptgbs--actions">
													<div class="ptgb ptgb--action-price mobile-show-table-cell">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Цена</div>
																<div class="ptgb__title ptgb__title--textfield">
																	
																	<div class="ccard__price-total"><span class="font-bold js-icard-price"><?=\ig\CFormat::getFormattedPrice($floatMinPrice, "RUB", array("RUB_SIGN"=>''))?></span> <span class="font-light">₽</span></div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="ptgb ptgb--action-quantity">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Кол-во</div>
																<div class="ptgb__title ptgb__title--textfield">
						                                            <span class="input-spin touch-focused" data-trigger="spinner">
						                                                <span class="input-spin__btn" data-spin="down">&minus;</span>
						                                                <span data-spin-clone class="input-spin__value hidden"><?=$arBasketItem["QUANTITY"]?></span>
						                                                <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" name="QUANTITY[<?=$arOffer["ID"]?>]" data-rule="quantity" data-min="0" data-max="9999" data-step="1" value="6" maxlength="4" size="6">
						                                                <span class="input-spin__btn" data-spin="up">+</span>
						                                            </span>
																</div>
															</div>
														</div>
													</div>
													
													<div class="ptgb ptgb--action-total">
														<div class="ptgb__inner">
															<div class="ptgb__content">
																<div class="ptgb__subtitle">Сумма</div>
																<div class="ptgb__title ptgb__title--textfield">
																	<div class="ccard__price-total"><strong class="js-icard-price-total"><?=\ig\CFormat::getFormattedPrice($arBasketItem["SUM_NUM"], "RUB", array("RUB_SIGN"=>''))?></strong> <span class="font-light">₽</span></div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="ccard__actions-btns btns">
													<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
														<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
														<span class="icon icon--cart-tick">
				                                            <svg class="icon icon--tick">
				                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
				                                            </svg>
				                                            <svg class="icon icon--cart">
				                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
				                                            </svg>
				                                        </span>
													</button>
													<button class="btn btn--icon js-cart-remove ">
														<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
														</svg>
													</button>
													<button class="btn btn--icon btn--favorite js-favorites-toggle active">
														<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
														</svg>
														<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
														</svg>
														<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
														</svg>
													</button>
												</div>
											
											</div>
										</div>
									</div>
								</div>
							
							</div>
						</div><?
						}?>
					</div><?
			} else { ?>
				
				<p>Список избранного пуст. Сохраняйте товары в&nbsp;избранном  с&nbsp;помощью кнопки
					<svg class="icon icon--heart vmiddle color-active">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
					</svg>
					<a href="/katalog/rasteniya/" class="link--ib link--bordered">в каталоге</a>.</p>
				<?
			}?>
		</div>
	</div>
</div>