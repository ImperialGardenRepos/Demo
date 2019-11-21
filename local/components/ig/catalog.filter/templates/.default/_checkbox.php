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

$values = $arResult['PROPERTIES'][$propertyCode]['VALUES'];
/**
 * Single checkbox intends to have single value referring to checked state.
 * @var $value
 */
$value = array_shift($values);

$countResultDelta = $value['COUNT'] - $arResult['COUNT_OFFERS'];
$disabled = $value['COUNT'] === 0;
$checked = $value['CHECKED'] === true;
?>

<div class="checkboxes<?= $params['CLASSES'] ?>">
    <div class="checkboxes__inner">
        <label class="checkbox checkbox--block checkbox-plain-js js-ddbox-input<?= ($bDisabled ? ' disabled' : '') ?>">
            <input type="checkbox" name="F[<?= $propertyCode ?>]"
                   value="<?= $value['VALUE'] ?>"<?= $checked === true ? ' checked' : '' ?><?= $disabled === true ? ' disabled' : '' ?>>
            <div class="checkbox__icon"></div>
            <?= $params['NAME'] ?>
            <?php if ($checked !== true): ?>
                <span class="count"><?= $countResultDelta ?></span>
            <?php endif; ?>
        </label>
    </div>
</div>