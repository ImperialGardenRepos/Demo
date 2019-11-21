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

<div class="icard__title">
    <a target="_blank" href="<?= $item['DETAIL_PAGE_URL'] ?>"
       class="link--bordered-pseudo">
        <?= $item['NAME_MAIN_FULL'] ?? $item['NAME_MAIN'] ?>
    </a>
</div>
<div class="icard__subtitle"><?= $item['NAME_ALT_FULL'] ?? $item['NAME_ALT'] ?></div>