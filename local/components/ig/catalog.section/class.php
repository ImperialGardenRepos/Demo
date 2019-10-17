<?php

use ig\CFormat;
use ig\CHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


class CatalogSection extends CBitrixComponent
{
    public function prepareCartData(): void
    {
        CHelper::prepareCartData($this->arResult);
    }

    public function prepareGroupData(): void
    {
        CHelper::prepareGroupData($this->arResult);
    }

    public function getImagesBlockHtml($arSort, $arOffer): string
    {
        $arImages = CHelper::getImagesArray($arOffer, $arSort, array(
            "RESIZE" => array("WIDTH" => 246, "HEIGHT" => 246), "TITLE" => array("OFFER_DETAIL_PICTURE" => "Сейчас", "SORT_DETAIL_PICTURE" => 'Общий вид')
        ));

        $strResult = '';

        if (empty($this->arParams["SORT_ID"])) {
            $strResult = '
	<div class="icard__image">
		<div class="icard__image-inner js-icard-offer-images">';
        }

        $strResult .= '
			<div class="icard__image-item image-slider' . ($this->arParams["SORT_ID"] > 0 ? '' : ' active') . ' js-images-hover-slider js-images-popup-slider cursor-pointer">';


        if (!empty($arImages)) {
            $intCnt = 0;
            foreach ($arImages as $intImageID => $arImage) {
                $strResult .= '<img' . ($intCnt == 0 ? ' class="active"' : '') . ' data-lazy-src="' . \ig\CUtil::prepareImageUrl($arImage["RESIZE"]["src"]) . '" src="' . SITE_TEMPLATE_PATH . '/img/blank.png" data-src-full="' . \ig\CUtil::prepareImageUrl($arImage["SRC"]) . '" data-ihs-content="' . $arImage["TITLE"] . '" alt="' . $arImage["TITLE"] . '">';

                $intCnt++;
            }
        }

        $strResult .= '
				<div class="icard__image-count">
					<svg class="icon icon--camera">
						<use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-camera"></use>
					</svg>
					' . count($arImages) . '
				</div>
			
			</div>';

        if (empty($this->arParams["SORT_ID"])) {
            $strResult .= '
		</div>
	</div>';
        }

        return $strResult;
    }

    public function getParamsBlockHtml($arSort, $arOffer): string
    {
        $arSortProp = $arSort["PROPERTIES"];
        $arOfferProp = $arOffer["PROPERTIES"];

        $strResult = '
	<div class="icard__offer-params' . ($this->arParams["SORT_ID"] > 0 ? '' : ' active') . '">
		
		<div class="ptgbs ptgbs--params">';
        if (!empty($arOfferProp["HEIGHT_NOW_EXT"]["VALUE"])) {
            $strResult .= '
				<div class="ptgb ptgb--param-height">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">
								Высота (cм)
							</div>
							<div class="ptgb__title noerap">' . CFormat::formatPropertyValue('HEIGHT_NOW_EXT', $arOfferProp["HEIGHT_NOW_EXT"]["VALUE"]) . '</div>
						</div>
					</div>
				</div>';
        }
        if ($arOfferProp["SIZE"]["VALUE"] !== '') {
            $strResult .= '
				<div class="ptgb ptgb--param-height">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">
								Размер
							</div>
							<div class="ptgb__title noerap">' . $arOfferProp["SIZE"]["VALUE"] . ' ' . strtolower($arOfferProp['UNIT']['VALUE']) . '</div>
						</div>
					</div>
				</div>';
        }

        if ($arOfferProp["FRACTION"]["VALUE"] !== '') {
            $strResult .= '
				<div class="ptgb ptgb--param-width">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">
								Фракция
							</div>
							<div class="ptgb__title nowrap">' . $arOfferProp["FRACTION"]["VALUE"] . '</div>
						</div>
					</div>
				</div>';
        }
        if ($arOfferProp["CROWN_WIDTH"]["VALUE"] > 0) {
            $strResult .= '
				<div class="ptgb ptgb--param-width">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">
								Ширина кроны (см)
							</div>
							<div class="ptgb__title nowrap">' . $arOfferProp["CROWN_WIDTH"]["VALUE"] . '</div>
						</div>
					</div>
				</div>';
        }

        if ($arSort["PROPERTIES"]["GROUP"]["VALUE"] == 'TEg4drYJ') {
            if (strlen($arOffer["PROPERTIES"]["CONTAINER_SIZE"]["VALUE"])) {
                $strResult .= '
			<div class="ptgb ptgb--param-pack">
				<div class="ptgb__inner">
					<div class="ptgb__content">
						<div class="ptgb__subtitle">
							' . $arOffer["PROPERTIES"]["CONTAINER_SIZE"]["NAME"] . '
						</div>
						<div class="ptgb__title nowrap">
							<div class="ptgb__title-inner">' . $arOffer["PROPERTIES"]["CONTAINER_SIZE"]["VALUE"] . '</div>
						</div>
					</div>
				</div>
			</div>';
            }
        }

        $strResult .= '
			<div class="ptgb ptgb--param-pack">
				<div class="ptgb__inner">
					<div class="ptgb__content">
						<div class="ptgb__subtitle">
							Упаковка
						</div>
						<div class="ptgb__title">
							<div class="ptgb__title-inner">' . CFormat::formatContainerPack($arOffer, $arSort) . '</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="ptgb ptgb--param-price">
				<div class="ptgb__inner">
					<div class="ptgb__content">
						<div class="ptgb__subtitle">Цена шт.</div>
						<div class="ptgb__title">
                            <div class="js-icard-price-discount-wrapper' . ($arOffer["BASE_PRICE_VALUE"] > 0 ? '' : ' hidden') . '">
                                <div class="icard__price color-active"><span class="font-bold js-icard-price-discount">' . CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"]) . '</span></div>
                                
                            </div>
                            <div class="js-icard-price-wrapper' . ($arOffer["BASE_PRICE_VALUE"] > 0 ? ' hidden' : '') . '">
                                <div class="icard__price"><span class="font-bold js-icard-price">' . CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", array("RUB_SIGN" => '')) . '</span> <span class="font-light">₽</span></div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="tags">';
        if (!empty($arSortProp["RECOMMENDED"]["VALUE"])) {
            $strResult .= '
				<div class="tag mobile-show-inline-block">
					Хит сезона
				</div>';
        }

        $strResult .= '
		
		</div>
	</div>';

        return $strResult;
    }

    public function getActionsBlockHtml($arSort, $arOffer): string
    {
        $arSortProp = $arSort["PROPERTIES"];
        $arOfferProp = $arOffer["PROPERTIES"];

        $intCartQuantity = $this->arResult["CART"][$arOffer["ID"]]["QUANTITY"];
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
								<span class="font-bold js-icard-price">' . CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"]) . '</span>
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
						<div class="ptgb__subtitle">Сумма</div>
						<div class="ptgb__title ptgb__title--textfield">
							<div class="icard__price-total">
								<strong class="js-icard-price-total">' . CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", array("RUB_SIGN" => '')) . '</strong> <span class="font-light">₽</span>
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