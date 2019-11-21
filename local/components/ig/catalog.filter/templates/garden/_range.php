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

/** @var string $propertyCode */
/** @var array $params */


$currentProperty = $arResult['PROPERTIES'][$propertyCode];

/** ToDo:: make customizable */
$min = $currentProperty['RANGE_CURRENT']['FROM'] ?? 0;
$max = $currentProperty['RANGE_CURRENT']['TO'] ?? 11;

$minText = $currentProperty['VALUES']['FROM'][$min + 1]['NAME'];
$maxText = $currentProperty['VALUES']['TO'][$max + 1]['NAME'];

//ToDo:: check ids everywhere
/**
 * Get min and max values from Sphinx automatically
 */
?>
<div class="ddbox js-ddbox" id="ddbox-period-<?= strtolower($propertyCode) ?>" data-ddbox-value-separator=" — ">
    <div class="ddbox__selection">
        <div class="ddbox__label"><?= $params['NAME'] ?></div>
        <div class="ddbox__reset">
            <svg class="icon icon--cross">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
            </svg>
        </div>
        <div class="ddbox__arrow">
            <svg class="icon icon--chevron-down">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
            </svg>
        </div>
        <div class="ddbox__value js-ddbox-selection" data-default-value="[0,11]"></div>
        <div class="ddbox__value js-ddbox-placeholder"><?= $minText ?> — <?= $maxText ?></div>
    </div>
    <div class="ddbox__dropdown">
        <div class="ddbox__dropdown-inner js-scrollbar">

            <div class="js-range range-slider-wrapper">
                <div class="range-slider js-range-slider" data-min="0" data-max="11"
                     data-minvalue="<?= $min ?>" data-maxvalue="<?= $max ?>" data-step="1" data-suffix=""
                     data-decimals="0" data-margin="1" data-range-format-function="index_to_month_name"></div>

                <div class="js-range-input hidden">
                    <input type="text" class="textfield js-ddbox-input js-range-input-get-min"
                           name="F[PROPERTY_<?= $propertyCode ?>][FROM]" data-prefix="От " data-suffix=""
                           data-reduce="0"
                           value="<?= $min ?>" data-default-value="0">
                </div>

                <div class="js-range-input hidden">
                    <input type="text" class="textfield js-ddbox-input js-range-input-get-max"
                           name="F[PROPERTY_<?= $propertyCode ?>][TO]" data-prefix="До " data-suffix="" data-reduce="0"
                           value="<?= $max ?>" data-default-value="11">
                </div>

                <div class="range-slider__values">
                    <div class="range-slider__value range-slider__value--min js-range-min-set"></div>
                    <div class="range-slider__value range-slider__value--sep">
                        —
                    </div>
                    <div class="range-slider__value range-slider__value--max js-range-max-set"></div>
                </div>
            </div>
        </div>
        <div class="ddbox__dropdown-scroll-gradient"></div>
    </div>
</div>
