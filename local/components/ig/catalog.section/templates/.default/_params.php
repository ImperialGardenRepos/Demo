<?php

use ig\CFormat;

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
/** @var int $offersIndex */

/** @var CBitrixComponent $component */

$itemProperties = $item['PROPERTIES'];
if (isset($offersIndex)) {
    $offer = $item['OFFERS'][$offersIndex];
} else {
    $offer = reset($item['OFFERS']);
}
$offerProperties = $offer['PROPERTIES'];
?>


<div class="icard__offer-params<?= isset($offersIndex) ? '' : ' active' ?>">
    <div class="ptgbs ptgbs--params">
        <?php if (!empty($offerProperties['HEIGHT_NOW_EXT']['VALUE'])): ?>
            <div class="ptgb ptgb--param-height">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">
                            Высота (cм)
                        </div>
                        <div class="ptgb__title nowrap"><?= CFormat::formatPropertyValue('HEIGHT_NOW_EXT', $offerProperties['HEIGHT_NOW_EXT']['VALUE']) ?></div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php if ($offerProperties['SIZE']['VALUE'] !== ''): ?>
            <div class="ptgb ptgb--param-height">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">
                            Размер
                        </div>
                        <div class="ptgb__title nowrap"><?= $offerProperties['SIZE']['VALUE'] ?> <?= strtolower($offerProperties['UNIT']['VALUE']) ?></div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php if ($offerProperties['FRACTION']['VALUE'] !== ''): ?>
            <div class="ptgb ptgb--param-width">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">
                            Фракция
                        </div>
                        <div class="ptgb__title nowrap"><?= $offerProperties['FRACTION']['VALUE'] ?></div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php if ($offerProperties['CROWN_WIDTH']['VALUE'] > 0): ?>
            <div class="ptgb ptgb--param-width">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">
                            Ширина кроны (см)
                        </div>
                        <div class="ptgb__title nowrap"><?= $offerProperties['CROWN_WIDTH']['VALUE'] ?></div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php if ($itemProperties['GROUP']['VALUE'] === 'TEg4drYJ' && strlen($offerProperties['CONTAINER_SIZE']['VALUE'])): ?>
            <div class="ptgb ptgb--param-pack">
                <div class="ptgb__inner">
                    <div class="ptgb__content">
                        <div class="ptgb__subtitle">
                            <?= $offerProperties['CONTAINER_SIZE']['NAME'] ?>
                        </div>
                        <div class="ptgb__title nowrap">
                            <div class="ptgb__title-inner"><?= $offerProperties['CONTAINER_SIZE']['VALUE'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="ptgb ptgb--param-pack">
            <div class="ptgb__inner">
                <div class="ptgb__content">
                    <div class="ptgb__subtitle">
                        Упаковка
                    </div>
                    <div class="ptgb__title">
                        <div class="ptgb__title-inner"><?= CFormat::formatContainerPack($item, $offer) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ptgb ptgb--param-price">
            <div class="ptgb__inner">
                <div class="ptgb__content">
                    <div class="ptgb__subtitle">Цена шт.</div>
                    <div class="ptgb__title">
                        <div class="js-icard-price-discount-wrapper<?= $offer['BASE_PRICE_VALUE'] > 0 ? '' : ' hidden' ?>">
                            <div class="icard__price color-active">
                                <span class="font-bold js-icard-price-discount"><?= CFormat::getFormattedPrice($offer['MIN_PRICE_VALUE']) ?></span>
                            </div>
                        </div>
                        <div class="js-icard-price-wrapper<?= $offer['BASE_PRICE_VALUE'] > 0 ? ' hidden' : '' ?>">
                            <div class="icard__price">
                                <span class="font-bold js-icard-price"><?= CFormat::getFormattedPrice($offer['MIN_PRICE_VALUE'], 'RUB', ['RUB_SIGN' => '']) ?></span>
                                <span class="font-light">₽</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tags">
        <?php if (!empty($itemProperties['RECOMMENDED']['VALUE'])): ?>
            <div class="tag mobile-show-inline-block">
                Хит сезона
            </div>
        <?php endif; ?>
    </div>
</div>
