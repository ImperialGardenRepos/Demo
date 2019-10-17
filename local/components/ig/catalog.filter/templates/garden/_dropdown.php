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


$sum = array_sum(array_column($currentProperty['VALUES'], 'COUNT'));
$ddDisabled = $sum === 0;

$type = $params['TYPE'];
if ((string)$type === '') {
    $type = 'radio';
}
$controlClass = '';
if ($type === 'radio') {
    $controlClass = ' checkbox--radio';
}
?>
<div class="ddbox js-ddbox<?= $ddDisabled === true ? ' disabled' : '' ?><?= $params['CLASSES'] ?>"
     id="ddbox-<?= strtolower($propertyCode) ?>">
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
        <div class="ddbox__value js-ddbox-selection" data-default-value=""></div>
        <div class="ddbox__value js-ddbox-placeholder<?=$ddDisabled?' active':''?>"><?= $params['ALL'] ?></div>
    </div>
    <div class="ddbox__dropdown">
        <div class="ddbox__dropdown-inner js-scrollbar">
            <div class="checkboxes">
                <div class="checkboxes__inner">
                    <?php if ($type === 'radio'): ?>
                        <label class="checkbox checkbox--block<?= $controlClass ?> checkbox-plain-js js-ddbox-input">
                            <input type="<?= $type ?>" name="F[<?= $propertyCode ?>]"
                                   checked value="" data-default-checked="">
                            <span class="checkbox__icon"></span>
                            <span class="count"></span>
                            <span class="js-ddbox-value"><?= $params['ALL'] ?></span>
                        </label>
                    <?php endif; ?>
                    <?php foreach ($currentProperty['VALUES'] as $propertyValue): ?>
                        <?php
                        $countResultDelta = $propertyValue['COUNT'] - $arResult['COUNT_OFFERS'];
                        $disabled = $propertyValue['COUNT'] === 0;
                        $checked = $propertyValue['CHECKED'] === true;
                        ?>
                        <label class="checkbox checkbox--block<?= $controlClass ?> checkbox-plain-js js-ddbox-input<?= $disabled === true ? ' disabled' : '' ?>">
                            <input type="<?= $type ?>" name="F[<?= $propertyCode ?>]"
                                   value="<?= $propertyValue['VALUE'] ?>" <?= $checked ? ' checked' : '' ?>>
                            <div class="checkbox__icon"></div>
                            <?php if ($propertyValue['COLOR']): ?>
                                <span class="checkbox-color checkbox-color--bordered"
                                      style="background-color: <?= $propertyValue['COLOR'] ?>;"></span>
                            <?php endif; ?>
                            <?php if ($propertyValue['ICON']): ?>
                                <svg class="icon icon--ddbox">
                                    <use xlink:href="/local/templates/ig//build/svg/symbol/svg/sprite.symbol.svg<?= $propertyValue['ICON'] ?>"></use>
                                </svg>
                            <?php endif; ?>
                            <?php if ($checked !== true && $countResultDelta !== 0): ?>
                                <span class="count"><?= $countResultDelta ?></span>
                            <?php endif; ?>
                            <span class="js-ddbox-value"><?= $propertyValue['NAME'] ?></span>
                        </label>
                        <?php $dataDefaultChecked = '' ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="ddbox__dropdown-scroll-gradient"></div>
    </div>
</div>
