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
<div class="filter__section-content-inner expand-it-inner">
    <div class="js-filter-ddbox-group-mobile-place"></div>
    <div class="js-filter-ddbox-usage-mobile-place"></div>
    <div class="cols-wrapper cols-wrapper--filter-groups js-filter-section-inputs">
        <div class="cols">
            <div class="col">
                <div class="filter__group js-filter-group">
                    <div class="filter__group-title">
                        Общие
                        <div class="filter__group-title-actions">
                            <div class="filter__group-title-action js-filter-group-reset tooltip"
                                 data-title="Сбросить все фильтры группы">
                                <svg class="icon icon--cross">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="filter__group-content">
                        <?php
                        foreach ($arParams['FILTER_DISPLAY_PARAMS']['COMMON'] as $propertyCode => $params) {
                            $currentParams = $params;
                            $templateFile = '_' . $params['TEMPLATE'] . '.php';
                            include $templateFile;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="filter__group js-filter-group">
                    <div class="filter__group-title">
                        Внешний вид
                        <div class="filter__group-title-actions">
                            <div class="filter__group-title-action js-filter-group-reset tooltip"
                                 data-title="Сбросить все фильтры группы">
                                <svg class="icon icon--cross">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="filter__group-content">
                        <?php
                        foreach ($arParams['FILTER_DISPLAY_PARAMS']['OUTFIT'] as $propertyCode => $params) {
                            $currentParams = $params;
                            $templateFile = '_' . $params['TEMPLATE'] . '.php';
                            include $templateFile;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="filter__group js-filter-group">
                    <div class="filter__group-title">
                        Экология
                        <div class="filter__group-title-actions">
                            <div class="filter__group-title-action js-filter-group-reset tooltip"
                                 data-title="Сбросить все фильтры группы">
                                <svg class="icon icon--cross">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="filter__group-content">
                        <?php
                        foreach ($arParams['FILTER_DISPLAY_PARAMS']['ECOLOGY'] as $propertyCode => $params) {
                            $currentParams = $params;
                            $templateFile = '_' . $params['TEMPLATE'] . '.php';
                            include $templateFile;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col col--double">
                <div class="filter__group js-filter-group">
                    <div class="filter__group-title">
                        Цветение и плоды
                        <div class="filter__group-title-actions">
                            <div class="filter__group-title-action js-filter-group-reset tooltip"
                                 data-title="Сбросить все фильтры группы">
                                <svg class="icon icon--cross">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="filter__group-content">
                        <div class="cols-wrapper cols-wrapper--filter-col-double">
                            <div class="cols">
                                <div class="col">
                                    <?php
                                    foreach ($arParams['FILTER_DISPLAY_PARAMS']['RIPENING_1'] as $propertyCode => $params) {
                                        $currentParams = $params;
                                        $templateFile = '_' . $params['TEMPLATE'] . '.php';
                                        include $templateFile;
                                    }
                                    ?>
                                </div>
                                <div class="col">
                                    <?php
                                    foreach ($arParams['FILTER_DISPLAY_PARAMS']['RIPENING_2'] as $propertyCode => $params) {
                                        $currentParams = $params;
                                        $templateFile = '_' . $params['TEMPLATE'] . '.php';
                                        include $templateFile;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>