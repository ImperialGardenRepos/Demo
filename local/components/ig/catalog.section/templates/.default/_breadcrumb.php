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
?>

<div class="breadcrumb">
    <?= $item['GROUP'] ?>
    <svg class="icon icon--long-arrow">
        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
    </svg>
    <a target="_blank" href="<?= $item['PATH'][1]['URL'] ?>">
        <?= $item['PATH'][1]['NAME'] ?>
    </a>
    <?php if (isset($item['PATH'][2])) : ?>
        <svg class="icon icon--long-arrow">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
        </svg>
        <a target="_blank" href="<?= $item['PATH'][2]['URL'] ?>">
            <?= $item['PATH'][2]['NAME'] ?>
        </a>
    <?php endif; ?>
</div>
