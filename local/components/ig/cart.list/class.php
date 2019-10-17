<?php

use ig\CFormat;
use ig\CFormatGarden;
use ig\CHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

CBitrixComponent::includeComponentClass('ig:catalog-old.section');

/** @noinspection AutoloadingIssuesInspection */

class FavoriteList extends CatalogOldSection
{
    public function getParamsBlockHtml($arSort, $arOffer): string
    {
        if ($arSort['IBLOCK_ID'] == CHelper::getIblockIdByCode('catalog')) {
            return parent::getParamsBlockHtml($arSort, $arOffer);
        }
        return CFormatGarden::getParamsBlockHtml($arSort, $arOffer);
    }

    public function getActionsBlockHtml($arSort, $arOffer): string
    {
        if ($this->arResult["CART"][$arOffer["ID"]]["PRICE"] > 0) {
            $arOffer["MIN_PRICE_VALUE"] = $this->arResult["CART"][$arOffer["ID"]]["PRICE"];
        }

        $strResult = '
	<div class="icard__offer-actions' . ($this->arParams["SORT_ID"] > 0 ? '' : ' active') . '" data-id="' . $arOffer["ID"] . '" data-cart-data=\'{"offerID": ' . $arOffer["ID"] . '}\'>
		<div class="ptgbs ptgbs--actions">
			
			<div class="ptgb ptgb--action-price mobile-show-table-cell">
				<div class="ptgb__inner">
					<div class="ptgb__content">
						<div class="ptgb__subtitle">Цена
						</div>
						<div class="ptgb__title ptgb__title--textfield">
							
							<div class="icard__price-total">
								<span class="font-bold js-icard-price">' . CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], 'RUB', array("RUB_SIGN" => '')) . '</span>
								<span class="font-light">₽</span>
							</div>
						
						</div>
					</div>
				</div>
			</div>
			
			<div class="ptgb ptgb--action-quantity">
				<div class="ptgb__inner">
					<div class="ptgb__content">
						<div class="ptgb__subtitle">Кол-во
						</div>
						<div class="ptgb__title ptgb__title--textfield">
    <span class="input-spin touch-focused" data-trigger="spinner">
        <span class="input-spin__btn" data-spin="down">&minus;</span>
        <span data-spin-clone class="input-spin__value hidden">0</span>
        <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="' . $arOffer["MIN_PRICE_VALUE"] . '" value="1" maxlength="4" size="6">
        <span class="input-spin__btn" data-spin="up">+</span>
    </span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="ptgb ptgb--action-total">
				<div class="ptgb__inner">
					<div class="ptgb__content">
						<div class="ptgb__subtitle">Сумма
						</div>
						<div class="ptgb__title ptgb__title--textfield">
							<div class="icard__price-total">
								<strong class="js-icard-price-total">' . CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", ["RUB_SIGN" => '']) . '</strong> <span class="font-light">₽</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		
		</div>
		
		<div class="icard__actions-btns btns">
			<button class="btn btn--cart js-cart-add js-cart-add_' . $arOffer["ID"] . '" data-label=\'<span class="mobile-hide">В корзине</span>\' data-label-empty=\'В<span class="mobile-hide"> корзину</span>\'>
				<span class="btn__title"><span class="mobile-hide">В корзину</span></span>
				<span class="icon icon--cart-tick">
    <svg class="icon icon--tick">
        <use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
    </svg>
    <svg class="icon icon--cart">
        <use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
    </svg>
</span>
			</button>
			
			
			<button class="btn btn--icon js-cart-remove hidden js-cart-remove_' . $arOffer["ID"] . '">
				<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
					<use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
				</svg>
			</button>
			
			<button class="btn btn--icon btn--favorite js-favorites-toggle' . (in_array($arOffer["ID"], $this->arResult["FAVORITE"]) ? ' active' : '') . ' js-favoriteButton_' . $arOffer["ID"] . '">
				<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
					<use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
				</svg>
				<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
					<use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
				</svg>
				<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
					<use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
				</svg>
			</button>
		
		</div>
	
	</div>';

        return $strResult;
    }
}