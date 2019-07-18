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

$strPageTitle = (empty($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) ? $arResult["NAME"] : $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]);
$bIsLastPage = $arResult["NAV_RESULT"]->NavPageCount<=$arResult["NAV_RESULT"]->NavPageNomer;

if(!$arParams["IS_AJAX"]) {?>
<div class="section section--results section--grey section--fullheight">
	<div class="container">
		<div class="filter-results js-filter-results"><?
}

if($arParams["PAGE_NUM"]<2) {?>
			<h1 class="h1"><?=$strPageTitle?></h1><?
}

if(!empty($arResult["DESCRIPTION"])) {
	$strPreviewText = \ig\CUtil::smartTrim($arResult["DESCRIPTION"], 300, false, '');
	$strRestText = str_replace($strPreviewText, '', $arResult["DESCRIPTION"]); ?>
				<div class="text p">
					<p><?=$strPreviewText?><?
	if(!empty($strRestText)) { ?>
						<a href="#filter-results-summary-more" class="link--dotted js-toggle-element" data-toggle-change-label="Скрыть">Показать полностью</a><?
	} ?>
					</p><?
	if(!empty($strRestText)) { ?>
					<div class="p hidden" id="filter-results-summary-more">
						<?=$strRestText?>
					</div><?
	}?>
				</div><?
}

if(empty($arParams["PAGE_NUM"])) {?>
			<div class="icards icards--goods">
				<div class="icards__inner" data-scroll-load-url="<?=$APPLICATION->GetCurPageParam('', array("PAGEN_1", "PAGEN_2", "PAGEN_3"))?>" data-scroll-load-page="<?=($arResult["CURRENT_PAGE"]+1)?>" data-hide-on-last-load="#action-more" data-scroll-load-inactive-class="hidden"><?
}

$intCnt = 0;
foreach($arResult["ITEMS"] as $arProduct) {
	$arOffer = $arProduct["OFFER"];
	
	$arProductProp = $arProduct["PROPERTIES"];
	$arOfferProp = $arOffer["PROPERTIES"]; ?>
			<div class="icards__item">
				<div class="icard icard--goods js-icard<?=($bIsLastPage?' scroll-load-last-one':'')?>" data-offer-index="0" data-offers-url="<?=$APPLICATION->GetCurPageParam('productID='.$arProduct["ID"], array("productID"))?>">
					<div class="icard__inner">
						<div class="icard__content">
							<div class="icard__header">
								<div class="cols-wrapper">
									<div class="cols cols--auto">
										<div class="col">
											<div class="icard__quickview mobile-hide">
												<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" class="link--dotted" data-fancybox data-type="ajax" data-src="<?=$arProduct["DETAIL_PAGE_URL"]?>">
													<svg class="icon icon--zoom">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
													</svg>
													быстрый просмотр
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="icard__title"><a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" class="link--bordered-pseudo"><?=$arProduct["NAME"]?></a></div>


								<div class="icard__counts mobile-hide"><?
									if($arProduct["OFFERS_CNT"]>0) {?>
									<span class="icard__count">
										Вариантов: <strong><?=$arProduct["OFFERS_CNT"]?></strong>
									</span><?
									}
	?>
								</div>
							</div>
							<div class="icard__main">
								<div class="icard__main-grid">
									<?= $this -> __component -> getImagesBlockHtml($arProduct, $arOffer);?>
									<div class="icard__main-col">
										<div class="icard__pa">
											<div class="icard__params">
												<div class="icard__offers-params-wrapper">
													<div class="icard__offers-params js-icard-offer-params">
													<?= $this -> __component -> getParamsBlockHtml($arProduct, $arOffer);?>
													</div>
												</div>
												<div class="icard__quickview mobile-show">
													<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" class="link--dotted" data-fancybox data-type="ajax" data-src="<?=$arProduct["DETAIL_PAGE_URL"]?>">
														<svg class="icon icon--zoom">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
														</svg>
														Быстрый просмотр </a>
												</div>

											</div><?
											if($arProduct["OFFERS_CNT"]>1) {?>
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
											}?>
											<div class="icard__actions">
												<div class="icard__offers-actions-wrapper">
													<div class="icard__offers-actions js-icard-offer-actions">
														<?= $this -> __component -> getActionsBlockHtml($arProduct, $arOffer);?>
													</div>
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
}
	
if(empty($arParams["PAGE_NUM"])) {?>
				</div>
			</div><?
	if($arResult["NAV_RESULT"]->NavPageCount>$arResult["NAV_RESULT"]->NavPageNomer) { ?>
		
			<div class="action-more" id="action-more">
				<button class="btn btn--more btn--fullwidth js-scroll-load-trigger" data-load-container=".filter-results .icards__inner">
					<svg class="icon icon--eye">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-eye"></use>
					</svg>
					<span class="link link--ib link--dotted">Показать ещё</span>
				</button>
			</div><?
	}

	if($arResult["NAV_RESULT"]->NavPageCount>1) {
		echo $arResult["NAV_STRING"];
	}?>
		</div><?
}

if(!$arParams["IS_AJAX"]) { ?>
	</div>
</div><?
}
