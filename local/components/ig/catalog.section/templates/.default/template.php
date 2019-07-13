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

$bIsLastPage = $arResult["NAV_RESULT"] -> NavPageCount <= $arResult["NAV_RESULT"] -> NavPageNomer;

if(!$arParams["IS_AJAX"]) { ?>
<div class="section section--results section--grey">
	<div class="container">
		<div class="filter-results js-filter-results"><?
}

if($arParams["PAGE_NUM"] < 2) { ?>
	<h1 class="h1--large"><?=$arResult["META_TAGS"]["H1"]?></h1><?


	if(!empty($arResult["DESCRIPTION"])) {
		$strPreviewText = \ig\CUtil::smartTrim($arResult["DESCRIPTION"], 300, false, '');
		$strRestText = str_replace($strPreviewText, '', $arResult["DESCRIPTION"]); ?>
<div class="text p">
	<p><?=$strPreviewText?><?
		if(!empty($strRestText)) { ?>
			<a href="#filter-results-summary-more" class="link--dotted js-toggle-element" data-toggle-change-label="Скрыть">Показать полностью</a><?
		}?>
	</p><?
		if(!empty($strRestText)) { ?>
		<div class="p hidden" id="filter-results-summary-more">
			<?=$strRestText?>
		</div><?
		}?>
</div><?
	}
}

if(empty($arParams["PAGE_NUM"])) { ?>
				<div class="icards">
					<div class="icards__inner" data-scroll-load-url="<?=$APPLICATION->GetCurPageParam('', array("PAGEN_1", "PAGEN_2", "PAGEN_3"))?>" data-scroll-load-page="<?=($arResult["CURRENT_PAGE"]+1)?>" data-hide-on-last-load="#action-more" data-scroll-load-inactive-class="hidden"><?
}
						$intCnt = 0;
						foreach($arResult["ITEMS"] as $arSort) {
							$arOffer = $arSort["OFFER"];
							
							$arSortProp = $arSort["PROPERTIES"];
							$arOfferProp = $arOffer["PROPERTIES"];
							
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
							
							<div class="icard<?=($bIsLastPage?' scroll-load-last-one':'')?> js-icard" data-offer-index="0" data-offers-url="<?=$APPLICATION->GetCurPageParam('sortID='.$arSort["ID"], array("sortID"))?>">
								<div class="icard__inner">
									
									<div class="icard__content">
										
										<div class="icard__header">
											
											<div class="cols-wrapper">
												<div class="cols cols--auto">
													
													<div class="col">
														
														<div class="breadcrumb">
															<?
															if(false) {?><a target="_blank" href="<?=$arResult["OFFER_PARAMS"]["GROUP"][$arSortProp["GROUP"]["VALUE"]]["URL"]?>"><?=$strGroup?></a><?
															}?>
															<?=$strGroup?>
															<svg class="icon icon--long-arrow">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
															</svg>
															<a target="_blank" href="<?=$arSectionNav[0]["SECTION_PAGE_URL"]?>"><?=$arSectionNav[0]["NAME"]?></a><?
															if(isset($arSectionNav[1])) {?>
															<svg class="icon icon--long-arrow">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
															</svg>
															<a target="_blank" href="<?=$arSectionNav[1]["SECTION_PAGE_URL"]?>"><?=$arSectionNav[1]["NAME"]?></a><?
															}?>
														</div>
													
													</div>
													<?
													if(!empty($arSortProp["RECOMMENDED"]["VALUE"])) { ?>
														<div class="col text-align-right">
															<div class="tags">
																<div class="tag">Хит сезона</div>
															</div>
														</div><?
													}?>
												
												</div>
											</div>
											<div class="icard__title"><a target="_blank" href="<?=$arSort["DETAIL_PAGE_URL"]?>" class="link--bordered-pseudo"><?=$strFormattedName?></a></div>
											
											<div class="icard__subtitle"><?=$strFormattedNameLat?></div>
										</div>
										
										
										<div class="icard__main">
											<div class="icard__main-grid">
												<?= $this -> __component -> getImagesBlockHtml($arSort, $arOffer);?>
												<div class="icard__main-col icard__main-col--features">
													<div class="ptgbs ptgbs--features">
														<?=\ig\CFormat::formatListMainProperties($arSort, array("TITLE_CLASS" => 'nowrap'));?>
													</div>
												</div>
												<div class="icard__main-col">
													<div class="icard__pa">
														<div class="icard__params">
															<div class="icard__offers-params-wrapper">
																<div class="icard__offers-params js-icard-offer-params">
																	<?= $this -> __component -> getParamsBlockHtml($arSort, $arOffer);?>
																</div>
															</div>
															<div class="icard__quickview mobile-show">
																<a href="#" class="link--dotted" data-fancybox data-type="ajax" data-src="/local/ajax/catalog_detail_quick.php?sortID=<?=$arSort["ID"]?>">
																	<svg class="icon icon--zoom">
																		<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
																	</svg>
																	Быстрый просмотр </a>
															</div><?
															
															if($arSort["OFFERS_CNT"]>1) { ?>
																<div class="icard__counts">
																<span class="icard__count">Предложений: <strong><?=$arSort["OFFERS_CNT"]?></strong></span>
																<?
																if($arSort["OFFERS_AVAILABLE_CNT"]>0) { ?>
																	<span class="icard__count mobile-hide">
																	В наличие: <strong><?=intval($arSort["OFFERS_AVAILABLE_CNT"])?></strong>
																	</span><?
																}
																
																if($arSort["OFFERS_ACTION_CNT"]>0) { ?>
																	<span class="icard__count mobile-hide">
																	Со скидкой: <strong><?=intval($arSort["OFFERS_ACTION_CNT"])?></strong>
																	</span><?
																} ?>
																</div><?
															}?>
														</div><?
														if($arSort["OFFERS_CNT"]>1) { ?>
															<div class="icard__nav ">
															
																<div class="icard__nav-link js-icard-offer-change" data-action="prev">
																	<svg class="icon">
																		<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-up"></use>
																	</svg>
																</div>
																<div class="icard__nav-page js-icard-offer-number">
																	1
																</div>
																<div class="icard__nav-link js-icard-offer-change" data-action="next">
																	<svg class="icon">
																		<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
																	</svg>
																</div>
															</div><?
														} else { ?>
															<div class="icard__nav  icard__nav--empty"></div><?
														}?>
														<div class="icard__actions">
															<div class="icard__offers-actions-wrapper">
																<div class="icard__offers-actions js-icard-offer-actions">
																	<?= $this -> __component -> getActionsBlockHtml($arSort, $arOffer);?>
																</div>
															</div>
															<div class="icard__quickview mobile-hide">
																<a href="<?=$arSort["DETAIL_PAGE_URL"]?>" class="link--dotted" data-fancybox data-type="ajax" data-src="<?=$arSort["DETAIL_PAGE_URL"]?>">
																	<svg class="icon icon--zoom">
																		<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
																	</svg>
																	быстрый просмотр </a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><?
							if($intCnt == 4 && !$arParams["IS_AJAX"]) {
								//echo '<!--ARTICLES_INCUT-->';
							}
							
							$intCnt++;
						}

if(empty($arParams["PAGE_NUM"])) {?>
					</div>
				</div><?
	
	if($arResult["NAV_RESULT"] -> NavPageCount > $arResult["NAV_RESULT"] -> NavPageNomer) { ?>
		
		<div class="action-more" id="action-more">
		<button class="btn btn--more btn--fullwidth js-scroll-load-trigger" data-load-container=".filter-results .icards__inner">
			<svg class="icon icon--eye">
				<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-eye"></use>
			</svg>
			<span class="link link--ib link--dotted">Показать ещё</span>
		</button>
		</div><?
	}
	
	if($arResult["NAV_RESULT"] -> NavPageCount>1) { ?>
		<?=$arResult["NAV_STRING"]?><?
	}?>
			</div><?
}

if(!$arParams["IS_AJAX"]) { ?>
		</div>
	</div><?
}