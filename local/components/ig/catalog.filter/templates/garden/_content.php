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


<div class="cols-wrapper cols-wrapper--filter-main">
    <div class="cols">
        <?php foreach ($arParams['FILTER_DISPLAY_PARAMS']['COMMON'] as $propertyCode => $params): ?>
            <div class="col mobile-hide">
                <?php
                $currentParams = $params;
                $templateFile = '_' . $params['TEMPLATE'] . '.php';
                include $templateFile;
                ?>
                <div class="js-filter-ddbox-<?= strtolower($propertyCode) ?>-place"></div>
            </div>
        <?php endforeach; ?>
        <div class="col col--query">
            <div class="ddbox ddbox--textfield ddbox--over-filter-footer opened-blocked js-ddbox js-ddbox-query"
                 id="ddbox-query" data-ddbox-back-title="Вернуться в каталог">
                <div class="ddbox__selection">
                    <div class="ddbox__label">Поиск по названию</div>
                    <div class="ddbox__reset">
                        <svg class="icon icon--cross">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                        </svg>
                    </div>
                    <div class="ddbox__value js-ddbox-selection js-ddbox-open-blocked"></div>
                    <div class="ddbox__textfield">
                        <div class="textfield-wrapper">
                            <input type="text" class="textfield textfield--placeholder-top" name="searchQuery" value=""
                                   maxlength="150" placeholder="Название" autocomplete="off"
                                   data-action-url="/local/ajax/catalog-garden-filter.php">
                            <div class="textfield-reset">
                                <svg class="icon icon--cross">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                </svg>
                            </div>
                            <div class="textfield-after textfield-after--over-reset pointer-events-none">
                                <svg class="icon icon--search">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-search"></use>
                                </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="ddbox__dropdown">
                        <div class="ddbox__dropdown-container">
                            <div class="ddbox__dropdown-inner js-ddbox-scrollbar"></div>
                            <div class="ddbox__dropdown-scroll-gradient"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>