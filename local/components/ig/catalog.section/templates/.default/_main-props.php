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
use ig\CFormat; ?>

<div class="icard__main-col icard__main-col--features">
    <div class="ptgbs ptgbs--features">
        <?php
        //ToDo: get data on class level
        ?>
        <?= CFormat::formatListMainProperties($item, ['TITLE_CLASS' => 'nowrap']) ?>
    </div>
</div>