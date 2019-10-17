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
<div class="cols-wrapper cols-wrapper--filter-footer">
    <div class="cols">
        <div class="col col--filter-recommend mobile-hide">
            <div class="maxw-like-filter-col">
                <?php
                foreach ($arParams['FILTER_DISPLAY_PARAMS']['FOOTER'] as $propertyCode => $params) {
                    $currentParams = $params;
                    $templateFile = '_' . $params['TEMPLATE'] . '.php';
                    include $templateFile;
                }
                ?>
                <div class="js-filter-recommend-place"></div>
            </div>
        </div>
        <div class="col col--filter-total">
            <div class="ftotals">
                <div class="ftotal">
                    Растений:
                    <span class="ftotal__value js-filter-total-value"><?= $arResult['COUNT_PRODUCTS'] ?></span>
                </div>
                <div class="ftotal">
                    Предложений:
                    <span class="ftotal__value js-filter-total-value"><?= $arResult['COUNT_OFFERS'] ?></span>
                </div>
            </div>
        </div>
        <div class="col col--filter-result-btn">
            <a href="#" class="btn js-filter-show-results js-ddbox-close-all"
               data-expand-selector="#filter-main-list">Посмотреть</a>
        </div>
        <div class="col col--filter-link mobile-hide">
            <div class="filter-link"
                 data-place='{"0": ".js-filter-link-mobile-place", "640": ".js-filter-link-place"}'>
                <a href="<?= $arResult['RESULT_LINK'] ?>" class="link--dotted js-copy"
                   data-clipboard-text="<?= $arResult['RESULT_LINK'] ?>"
                   data-title="Ссылка скопирована">Ссылка на результат поиска</a>
            </div>
            <div class="js-filter-link-place"></div>
        </div>
    </div>
</div>
