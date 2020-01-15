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
$this->setFrameMode(true);

use ig\CHelper; ?>
<div class="section section--grey section--article text">
    <div class="container">
        <h1><?php $APPLICATION->ShowTitle() ?></h1>
        <div class="fcard fcard--project<?= empty($arResult['PROPERTIES']['VIDEO_URL']['VALUE']) ? ' fcard--project-w-wrapping' : '' ?>">
            <div class="fcard__grid">
                <?php if (!empty($arResult['SLIDER_IMAGES']) || $arResult['DETAIL_PICTURE']['ID'] > 0) : ?>
                    <div class="fcard__image">
                        <?php if (!empty($arResult['SLIDER_IMAGES'])): ?>
                            <?php
                            $strSliderHtml = CHelper::getSlider('#SLIDER#', ['IMAGES' => $arResult['SLIDER_IMAGES']]);
                            echo $strSliderHtml;
                            ?>
                        <?php elseif ($arResult['DETAIL_PICTURE']['ID'] > 0) : ?>
                            <?php $strImageAlt = $arResult['NAME']; ?>
                            <div class="image-slider image-slider--autosize image-slider--autosize-h js-images-hover-slider js-images-popup-slider cursor-pointer">
                                <img class="active" data-lazy-src="<?= $arResult['TILE']['src'] ?>'
                                     src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png"
                                     data-src-full="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $strImageAlt ?>"
                                     data-ihs-content="<?= $arResult['DETAIL_PICTURE']['DESCRIPTION'] ?>">
                                <div class="image-slider__size">
                                    <img src="<?= $arResult['TILE']['src'] ?>" alt="<?= $strImageAlt ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
                <div class="fcard__main">
                    <?php if (!empty($arResult['PROPERTIES']['VIDEO_URL']['VALUE'])) : ?>
                        <div class="video-iframe">
                            <?= $arResult['PROPERTIES']['VIDEO_URL']['~VALUE'] ?>
                        </div>
                    <?php else: ?>
                        <div class="p text">
                            <?= $arResult['DETAIL_TEXT'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($arResult['PROPERTIES']['VIDEO_URL']['VALUE'])): ?>
                <div class="p text">
                    <?= $arResult['DETAIL_TEXT'] ?>
                </div>
            <?php endif; ?>
        </div>
        <?= $arResult['DISPLAY_PROPERTIES']['FREE_TEXT']['DISPLAY_VALUE'] ?? '' ?>
        <?php if (!empty($arResult['MORE_NEWS'])): ?>
            <div class="h2">Ещё новости</div>
            <div class="tgbs">
                <div class="tgbs__inner text-align-center">
                    <?php foreach ($arResult['MORE_NEWS'] as $arItem) : ?>
                        <div class="tgb tgb--img-hover tgb--1-3">
                            <a class="tgb__inner" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                <div class="tgb__image img-to-bg">
                                    <img data-lazy-src="<?= $arItem['IMAGE'] ?>"
                                         src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png" alt="">
                                </div>
                                <div class="tgb__overlay">
                                    <div class="tgb__overlay-part">
                                        <div class="tgb__overlay-part-inner">
                                            <div class="tgb__date"><?= substr($arItem['DATE_ACTIVE_FROM'], 0, 10) ?></div>
                                            <div class="tgb__title h4"><?= $arItem['NAME'] ?></div>
                                        </div>
                                    </div>
                                    <div class="tgb__overlay-part mobile-hide">
                                        <div class="tgb__overlay-part-inner">
                                            <div class="tgb__summary">
                                                <p><?= $arItem['PREVIEW_TEXT'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>