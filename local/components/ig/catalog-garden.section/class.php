<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


class CatalogSection extends \CBitrixComponent
{
    public function prepareCartData()
    {
        \ig\CHelper::prepareCartData($this->arResult);
    }

    public function prepareGroupData()
    {
        \ig\CHelper::prepareGroupData($this->arResult);
    }

    public function getImagesBlockHtml($arProduct, $arOffer)
    {
        $arImages = \ig\CHelper::getImagesArray($arOffer, $arProduct, [
            "RESIZE" => ["WIDTH" => 390, "HEIGHT" => 290],
        ]);

        $strResult = '';

        if (empty($this->arParams["PRODUCT_ID"])) {
            $strResult = '
	<div class="icard__image">
		<div class="icard__image-inner js-icard-offer-images">';
        }

        $strResult .= '
			<div class="icard__image-item image-slider' . ($this->arParams["PRODUCT_ID"] > 0 ? '' : ' active') . ' js-images-hover-slider js-images-popup-slider cursor-pointer">';


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

        if (empty($this->arParams["PRODUCT_ID"])) {
            $strResult .= '
		</div>
	</div>';
        }

        return $strResult;
    }

    public function getParamsBlockHtml($arSort, $arOffer)
    {
        $arSortProp = $arSort["PROPERTIES"];
        $arOfferProp = $arOffer["PROPERTIES"];

        $strResult = '
		<div class="icard__offer-params' . ($this->arParams["PRODUCT_ID"] > 0 ? '' : ' active') . '">
			
			<div class="ptgbs ptgbs--params">
				
				<div class="ptgb ptgb--param-volume">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Объем</div>
							<div class="ptgb__title">' . $arOfferProp["SIZE"]["VALUE"] . ' ' . $arOfferProp["UNIT"]["VALUE"] . '</div>
						</div>
					</div>
				</div>
				<div class="ptgb ptgb--param-price">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Цена шт.</div>
							<div class="ptgb__title nowrap">
								
								<div class="icard__price' . (isset($arOffer["BASE_PRICE_VALUE"]) ? ' color-active' : '') . '">
									<span class="font-bold">' . \ig\CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", ["RUB_SIGN" => '']) . '</span>
									<span class="font-light">₽</span>
								</div>';

        $strResult .= '
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tags">
				<div class="tag mobile-show-inline-block">Хит сезона</div>';

        if (!empty($arOfferProp["ACTION"]["VALUE"])) {
            $strResult .= '<div class="tag">Скидка</div>';
        }

        if (!empty($arOfferProp["NEW"]["VALUE"])) {
            $strResult .= '<div class="tag">Новинка</div>';
        }

        $strResult .= '
			</div>
		</div>';

        return $strResult;
    }

    public function getActionsBlockHtml($arProduct, $arOffer)
    {
        $arProductProp = $arProduct["PROPERTIES"];
        $arOfferProp = $arOffer["PROPERTIES"];

        $intCartQuantity = $this->arResult["CART"][$arOffer["ID"]]["QUANTITY"];
        if ($this->arResult["CART"][$arOffer["ID"]]["PRICE"] > 0) {
            $arOffer["MIN_PRICE_VALUE"] = $this->arResult["CART"][$arOffer["ID"]]["PRICE"];
        }

        $strResult = '
		<div class="icard__offer-actions' . ($this->arParams["PRODUCT_ID"] > 0 ? '' : ' active') . '" data-id=\'' . $arOffer["ID"] . '\' data-cart-data=\'' . json_encode(['offerID' => $arOffer["ID"]]) . '\'>
			<div class="ptgbs ptgbs--actions">
				<div class="ptgb ptgb--action-price mobile-show-table-cell">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Цена</div>
							<div class="ptgb__title ptgb__title--textfield">
								<div class="icard__price-total">
									<span class="font-bold js-icard-price">' . \ig\CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", ["RUB_SIGN" => '']) . '</span>
									<span class="font-light">₽</span>
								</div>
							
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
                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="' . $arOffer["MIN_PRICE_VALUE"] . '" value="' . $this->arResult["CART"][$arOffer["ID"]]["QUANTITY"] . '" maxlength="4" size="6">
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
								<div class="icard__price-total inactive">
									<strong class="js-icard-price-total">0</strong>
									<span class="font-light">₽</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="icard__actions-btns btns">
				<button class="btn btn--cart js-cart-add" data-label=\'<span class="tablet-hide">В корзине</span>\' data-label-empty=\'В<span class="tablet-hide"> корзину</span>\' disabled>
					<span class="btn__title">В<span class="tablet-hide"> корзину</span></span>
					<span class="icon icon--cart-tick">
                    <svg class="icon icon--tick">
                        <use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                    </svg>
                    <svg class="icon icon--cart">
                        <use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                    </svg>
                    </span>
				</button>
				<button class="btn btn--icon js-cart-remove hidden">
					<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
						<use xlink:href="' . SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
					</svg>
				</button>
				<button class="btn btn--icon btn--favorite js-favorites-toggle">
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
