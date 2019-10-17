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
<?php if (!empty($itemProp['RECOMMENDED']['VALUE'])): ?>
    <div class="col text-align-right">
        <div class="tags">
            <div class="tag">Хит сезона</div>
        </div>
    </div>
<?php endif; ?>
