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
<div class="ltabs__item">
    <div class="flex flex-align-baseline">
        <a class="ltabs__link" href="/katalog/rasteniya/">Растения</a>
        <div class="nav stabs mobile-hide">
            <div class="stabs__list">
                <div class="stabs__item">
                    <a class="stabs__link hidden" href="/katalog/rasteniya/">Все</a>
                </div>
            </div>
        </div>
        <div class="nav stabs mobile-hide">
            <div class="stabs__list">
                <div class="stabs__item">
                    <a href="<?= CATALOG_PRICE_LIST_PATH ?>Price.XLS?<?=time()?>"
                       class="nounderline-important">
                        <svg class="icon">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-download-cloud"></use>
                        </svg>
                        <span class="stabs__link stabs__link--link no-text-transform">Прайс</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ltabs__item active">
    <div class="flex flex-align-baseline">
        <a class="ltabs__link" href="#">Товары для сада</a>
        <div class="nav stabs"
             data-place='{"0": ".js-filter-stabs-mobile-place", "640": ".js-filter-stabs-place"}'>
            <div class="stabs__list">
                <div class="stabs__item active hidden">
                    <a class="stabs__link" href="/katalog/tovary-dlya-sada/">Все</a>
                </div>
                <div class="stabs__item mobile-show-inline-block">
                    <a href="<?= CATALOG_PRICE_LIST_PATH ?>Price.XLS?<?=time()?>" class="nounderline-important">
                        <svg class="icon">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-download-cloud"></use>
                        </svg>
                        <span class="stabs__link stabs__link--link no-text-transform">Прайс</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="js-filter-stabs-place"></div>
        <div class="nav stabs mobile-hide">
            <div class="stabs__list">
                <div class="stabs__item">
                    <a href="<?= CATALOG_PRICE_LIST_PATH ?>PRICE_SAD.XLS?<?=time()?>" class="nounderline-important">
                        <svg class="icon">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-download-cloud"></use>
                        </svg>
                        <span class="stabs__link stabs__link--link no-text-transform">Прайс</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
