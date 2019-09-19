<?php

use Bitrix\Main\Application;
use ig\CFormat;
use ig\Helpers\Url;

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

$isLastPage = $arResult['NAV_RESULT']->NavPageCount <= $arResult['NAV_RESULT']->NavPageNomer;
$isFirstPage = (int)$arResult['NAV_RESULT']->NavPageNomer === 1;


$scrollLoadUrl = Url::getUrlWithoutParams();
$requestArray = Application::getInstance()->getContext()->getRequest()->toArray();
if (array_key_exists('filterAlias', $requestArray) && (string)$requestArray['filterAlias'] !== '') {
    $scrollLoadUrl .= '?filterAlias=' . $requestArray['filterAlias'];
}

$scrollLoadPage = isset($arResult['CURRENT_PAGE']) ? $arResult['CURRENT_PAGE'] + 1 : 2;

/**
 * Generating section cards output without wrapper, pagination etc.
 */
ob_start(); ?>

<?php $intCnt = 0; ?>
<?php foreach ($arResult['ITEMS'] as $arSort): ?>
    <?php
    $arOffer = $arSort['OFFER'];

    $arSortProp = $arSort['PROPERTIES'];
    $arOfferProp = $arOffer['PROPERTIES'];

    if (!empty($arSortProp['IS_RUSSIAN']['VALUE'])) {
        $strName = $arSort['NAME'];
        $strName2 = $arSortProp['NAME_LAT']['VALUE'];
    } else {
        $strName = $arSortProp['NAME_LAT']['VALUE'];
        $strName2 = $arSort['NAME'];
    }

    if (empty($strName)) {
        $strName = $strName2;
        $strName2 = '';
    }

    $arSectionNav = $arResult['SECTIONS'][$arSort['IBLOCK_SECTION_ID']]['NAV'];
    $strGroup = $arResult['OFFER_PARAMS']['GROUP'][$arSortProp['GROUP']['VALUE']]['NAME'];

    $strFormattedName = CFormat::formatPlantTitle(
        (empty($arSortProp['IS_VIEW']['VALUE']) ? $strName : ''),
        $arSectionNav[0]['NAME'],
        $arSectionNav[1]['NAME']
    );
    $strFormattedNameLat = CFormat::formatPlantTitle(
        (empty($arSortProp['IS_VIEW']['VALUE']) ? $arSortProp['NAME_LAT']['VALUE'] : ''),
        $arResult['IBLOCK_SECTIONS'][$arSectionNav[0]['ID']]['UF_NAME_LAT'],
        $arResult['IBLOCK_SECTIONS'][$arSectionNav[1]['ID']]['UF_NAME_LAT']
    ); ?>

    <div class="icard<?= $isLastPage ? ' scroll-load-last-one' : '' ?> js-icard" data-offer-index="0"
         data-offers-url="<?=Url::getUrlWithoutParams()?>?sortID=<?=$arSort['ID']?>">
        <div class="icard__inner">
            <div class="icard__content">
                <div class="icard__header">
                    <div class="cols-wrapper">
                        <div class="cols cols--auto">
                            <div class="col">
                                <div class="breadcrumb">
                                    <?= $strGroup ?>
                                    <svg class="icon icon--long-arrow">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
                                    </svg>
                                    <a target="_blank" href="<?= $arSectionNav[0]['SECTION_PAGE_URL'] ?>">
                                        <?= $arSectionNav[0]['NAME'] ?>
                                    </a>
                                    <?php if (isset($arSectionNav[1])) : ?>
                                        <svg class="icon icon--long-arrow">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-long-right"></use>
                                        </svg>
                                        <a target="_blank" href="<?= $arSectionNav[1]['SECTION_PAGE_URL'] ?>">
                                            <?= $arSectionNav[1]['NAME'] ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($arSortProp['RECOMMENDED']['VALUE'])): ?>
                                <div class="col text-align-right">
                                    <div class="tags">
                                        <div class="tag">Хит сезона</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="icard__title">
                        <a target="_blank" href="<?= $arSort['DETAIL_PAGE_URL'] ?>" class="link--bordered-pseudo">
                            <?= $strFormattedName ?>
                        </a>
                    </div>
                    <div class="icard__subtitle"><?= $strFormattedNameLat ?></div>
                </div>
                <div class="icard__main">
                    <div class="icard__main-grid">
                        <?= $this->__component->getImagesBlockHtml($arSort, $arOffer); ?>
                        <div class="icard__main-col icard__main-col--features">
                            <div class="ptgbs ptgbs--features">
                                <?= CFormat::formatListMainProperties($arSort, array('TITLE_CLASS' => 'nowrap')) ?>
                            </div>
                        </div>
                        <div class="icard__main-col">
                            <div class="icard__pa">
                                <div class="icard__params">
                                    <div class="icard__offers-params-wrapper">
                                        <div class="icard__offers-params js-icard-offer-params">
                                            <?= $this->__component->getParamsBlockHtml($arSort, $arOffer) ?>
                                        </div>
                                    </div>
                                    <div class="icard__quickview mobile-show">
                                        <a href="#" class="link--dotted" data-fancybox data-type="ajax"
                                           data-src="/local/ajax/catalog_detail_quick.php?sortID=<?= $arSort['ID'] ?>">
                                            <svg class="icon icon--zoom">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
                                            </svg>
                                            Быстрый просмотр
                                        </a>
                                    </div>
                                    <?php if ($arSort['OFFERS_CNT'] > 1) : ?>
                                        <div class="icard__counts">
                                            <span class="icard__count">Вариантов: <strong><?= $arSort['OFFERS_CNT'] ?></strong></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($arSort['OFFERS_CNT'] > 1) : ?>
                                    <div class="icard__nav ">
                                        <div class="icard__nav-link js-icard-offer-change" data-action="prev">
                                            <svg class="icon">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-up"></use>
                                            </svg>
                                        </div>
                                        <div class="icard__nav-page js-icard-offer-number">
                                            1
                                        </div>
                                        <div class="icard__nav-link js-icard-offer-change" data-action="next">
                                            <svg class="icon">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
                                            </svg>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="icard__nav  icard__nav--empty"></div>
                                <?php endif; ?>
                                <div class="icard__actions">
                                    <div class="icard__offers-actions-wrapper">
                                        <div class="icard__offers-actions js-icard-offer-actions">
                                            <?= $this->__component->getActionsBlockHtml($arSort, $arOffer); ?>
                                        </div>
                                    </div>
                                    <div class="icard__quickview mobile-hide">
                                        <a href="<?= $arSort['DETAIL_PAGE_URL'] ?>" class="link--dotted" data-fancybox
                                           data-type="ajax" data-src="<?= $arSort['DETAIL_PAGE_URL'] ?>">
                                            <svg class="icon icon--zoom">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-zoom"></use>
                                            </svg>
                                            быстрый просмотр
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $intCnt++; ?>
<?php endforeach; ?>

<?php
/**
 * End catalog section buffer
 */
?>
<?php
$bufferedSectionContent = ob_get_clean();
?>

<?php
/**
 * Generating page params for ajax call
 */
?>
<?php if (empty($arParams['PAGE_NUM'])) : ?>
    <?php ob_start(); ?>

    <div class="icards icards">
        <div class="icards__inner"
             data-scroll-load-url="<?= $scrollLoadUrl ?>"
             data-scroll-load-page="<?= $scrollLoadPage ?>" data-hide-on-last-load="#action-more"
             data-scroll-load-inactive-class="hidden">
            <?= $bufferedSectionContent ?>
        </div>
    </div>
    <?php
    $bufferedSectionContent = ob_get_clean();
    ?>
<?php endif; ?>

<?php if (!$arParams['IS_AJAX']): ?>
    <div class="section section--results section--grey">
        <div class="container">

            <?php if ($arParams['PAGE_NUM'] < 2): ?>
                <h1 class="h1--large"><?php $APPLICATION->ShowTitle() ?></h1>
            <?php endif; ?>

            <?php if ($isFirstPage && !empty($arResult['DESCRIPTION'])) : ?>
                <?php
                $previewText = \ig\CUtil::smartTrim($arResult['DESCRIPTION'], 300, false, '');
                $restText = str_replace($previewText, '', $arResult['DESCRIPTION']); ?>
                <div class="text p js-toggle-container">
                    <p>
                        <?= $previewText ?>
                        <?php if (!empty($restText)) : ?>
                            <a href="#filter-results-summary-more" class="link--dotted js-toggle-element"
                               data-toggle-change-label="Скрыть">
                                Показать полностью
                            </a>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($restText)): ?>
                        <div class="p js-toggle-block hidden" id="filter-results-summary-more">
                            <?= $restText ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="filter-results js-filter-results">
                <?= $bufferedSectionContent ?>
            </div>
            <?php
            /**
             * Navigation and Show more buttons output
             */
            ?>
            <?php if ($arResult['NAV_RESULT']->NavPageCount > $arResult['NAV_RESULT']->NavPageNomer): ?>
                <div class="action-more" id="action-more">
                    <button class="btn btn--more btn--fullwidth js-scroll-load-trigger"
                            data-load-container=".filter-results .icards__inner">
                        <svg class="icon icon--eye">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-eye"></use>
                        </svg>
                        <span class="link link--ib link--dotted">Показать ещё</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($arResult['NAV_RESULT']->NavPageCount > 1): ?>
                <?= $arResult['NAV_STRING'] ?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <?= $bufferedSectionContent ?>
<?php endif; ?>