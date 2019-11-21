<?php

use ig\CHelper;

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
/** @var int $offersIndex */
/** @var CBitrixComponent $component */

if (isset($offersIndex)) {
    $offer = $item['OFFERS'][$offersIndex];
} else {
    $offer = reset($item['OFFERS']);
}
$images = CHelper::getImagesArray(
    $offer,
    $item,
    [
        'RESIZE' => [
            'WIDTH' => 246,
            'HEIGHT' => 246,
        ],
        'TITLE' => [
            'OFFER_DETAIL_PICTURE' => 'Сейчас',
            'SORT_DETAIL_PICTURE' => 'Общий вид',
        ],
    ]
);
$isFirst = true;
?>
<div class="icard__image-item<?= isset($offersIndex) ? '' : ' active' ?> image-slider js-images-hover-slider js-images-popup-slider cursor-pointer">
    <?php foreach ($images as $image): ?>
        <?php /** @var array $image */ ?>
        <img class="<?= $isFirst === true ? 'active' : '' ?>" alt="<?= $image['TITLE'] ?>"
             data-lazy-src="<?= \ig\CUtil::prepareImageUrl($image['RESIZE']['src']) ?>"
             data-src-full="<?= \ig\CUtil::prepareImageUrl($image['SRC']) ?>"
             data-ihs-content="<?= $image['TITLE'] ?>"
             src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png">
        <?php $isFirst = false ?>
    <?php endforeach; ?>
    <div class="icard__image-count">
        <svg class="icon icon--camera">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-camera"></use>
        </svg> <?= count($images) ?>
    </div>
</div>
