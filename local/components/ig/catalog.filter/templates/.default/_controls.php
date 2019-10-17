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
/** @var CBitrixComponent $component */
?>
<div class="filter__section-title filter__section-title--back expand-it<?= ($arParams['EXPAND_FILTER'] === 'Y' ? ' active' : '') ?>"
     data-expand-selector="#filter-main-list">
    <svg class="icon icon--arrow-left">
        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
    </svg>
    <span class="filter__section-title-link link--ib link--dotted">Вернуться в каталог</span>
</div>
<div class="filter__section-title filter__section-title--open expand-it<?= ($arParams['EXPAND_FILTER'] === 'Y' ? ' active' : '') ?>">

    <svg class="icon icon--filter">
        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-filter"></use>
    </svg>
    <span class="filter__section-title-link link--ib link--dotted">Фильтры</span>

    <div class="filter__section-title-action no-propagation js-filter-reset mobile-hide">
        <span class="link--ib link--dotted">Сбросить</span>
        <svg class="icon icon--cross">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
        </svg>
    </div>
    <div class="filter__section-title-action no-propagation js-filter-reset mobile-show"
         data-ddbox-reset-inputs="#ddbox-group, #ddbox-usage, #ddbox-query, .js-filter-section-inputs">
        <span class="link--ib link--dotted">Сбросить</span>
        <svg class="icon icon--cross">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
        </svg>
    </div>
</div>
