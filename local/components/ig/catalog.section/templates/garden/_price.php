<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
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
/** @var array $item */
/** @var CBitrixComponent $component */
/** @var int $offersIndex */

if (isset($offersIndex)) {
    $offer = $item['OFFERS'][$offersIndex];
} else {
    $offer = reset($item['OFFERS']);
}
$cartData = [
    'offerID' => $offer['ID'],
];

use ig\CFormat; ?>
<div class="icard__offer-actions<?= isset($offersIndex) ? '' : ' active' ?>" data-id="<?= $offer['ID'] ?>"
     data-cart-data='<?= json_encode($cartData) ?>'>
    <div class="ptgbs ptgbs--actions">
        <div class="ptgb ptgb--action-price mobile-show-table-cell">
            <div class="ptgb__inner">
                <div class="ptgb__content">
                    <div class="ptgb__subtitle">Цена</div>
                    <div class="ptgb__title ptgb__title--textfield">
                        <div class="icard__price-total">
                            <span class="font-bold js-icard-price"><?= CFormat::getFormattedPrice($offer['MIN_PRICE_VALUE']) ?></span>
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
                        <div class="input-spin touch-focused" data-trigger="spinner">
                            <span class="input-spin__btn" data-spin="down">&minus;</span>
                            <span data-spin-clone class="input-spin__value hidden">0</span>
                            <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner"
                                   data-spin="spinner" title="Количество"
                                   data-rule="quantity" data-min="0" data-max="9999" data-step="1"
                                   data-price="<?= $offer['MIN_PRICE_VALUE'] ?>" value="1" maxlength="4" size="6">
                            <span class="input-spin__btn" data-spin="up">+</span>
                        </div>
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
                            <strong class="js-icard-price-total">
                                <?= CFormat::getFormattedPrice($offer['MIN_PRICE_VALUE'], 'RUB', ['RUB_SIGN' => '']) ?>
                            </strong>&nbsp<span class="font-light">₽</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $dataButtonToCartLabel = 'В<span class="mobile-hide"> корзину</span>';
    $dataButtonInCartLabel = '<span class="mobile-hide">В корзине</span>';
    ?>
    <div class="icard__actions-btns btns">
        <button class="btn btn--cart js-cart-add js-cart-add_<?= $offer['ID'] ?>"
                data-label='<?= $dataButtonInCartLabel ?>'
                data-label-empty='<?= $dataButtonToCartLabel ?>'>
            <span class="btn__title">
                <span class="mobile-hide">В корзину</span>
            </span>
            <span class="icon icon--cart-tick">
                <svg class="icon icon--tick">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                </svg>
                <svg class="icon icon--cart">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                </svg>
            </span>
        </button>
        <button class="btn btn--icon js-cart-remove hidden js-cart-remove_<?= $offer['ID'] ?>">
            <svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
            </svg>
        </button>
        <button class="btn btn--icon btn--favorite js-favorites-toggle js-favoriteButton_<?= $offer['ID'] ?>">
            <svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
            </svg>
            <svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
            </svg>
            <svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
            </svg>
        </button>
    </div>
</div>