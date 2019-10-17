<?php

use ig\Seo\Meta;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var CMain $APPLICATION
 */
$this->setFrameMode(true);

/**
 * Setting view targets for meta
 */

$meta = Meta::getInstance();
$meta->setCurrentPage((int)$arResult['CURRENT_PAGE']);
$meta->setTotalPage((int)$arResult['PAGE_COUNT']);

$filterAlias = (string)$arParams['FILTER_ALIAS'] !== '' ? "?filterAlias={$arParams['FILTER_ALIAS']}" : '';
?>
<?php /** Show more start */ ?>
<?php if ($arResult['CURRENT_PAGE'] < $arResult['PAGE_COUNT']): ?>
    <div class="action-more js-more" id="action-more">
        <button class="btn btn--more btn--fullwidth js-scroll-load-trigger"
                data-load-container=".filter-results .icards__inner">
            <svg class="icon icon--eye">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-eye"></use>
            </svg>
            <span class="link link--ib link--dotted">Показать ещё</span>
        </button>
    </div>
<?php endif ?>
<?php /** Show more finish */ ?>

<div class="pager js-pagination" data-current-page="<?= $arResult['CURRENT_PAGE'] ?>"
     data-total-page="<?= $arResult['PAGE_COUNT'] ?>">
    <?php if ((int)$arResult['CURRENT_PAGE'] > 1): ?>
        <?php /** Arrow left start */ ?>
        <?php if ((int)$arResult['CURRENT_PAGE'] > 2): ?>
            <a href="<?= $arParams['BASE_URL'] ?>page-<?= (int)$arResult['CURRENT_PAGE'] - 1 ?>/<?= $filterAlias ?>"
               class="pager__item pager__item--arrow">
                <svg class="icon">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
                </svg>
            </a>
        <?php else: ?>
            <a href="<?= $arParams['BASE_URL'] ?><?= $filterAlias ?>"
               class="pager__item pager__item--arrow">
                <svg class="icon">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
                </svg>
            </a>
        <?php endif; ?>
        <?php /** Arrow left end */ ?>

        <?php /** First page display start */ ?>
        <?php if ((int)$arResult['START_PAGE'] > 1): ?>
            <a href="<?= $arParams['BASE_URL'] ?><?= $filterAlias ?>" class="pager__item">1</a>
            <?php if ($arResult['START_PAGE'] > 2): ?>
                <span class="pager__item mobile-hide">...</span>
            <?php endif; ?>
        <?php endif; ?>
        <?php /** First page display end */ ?>
    <?php endif; ?>

    <?php /** Page listing start */ ?>
    <?php do { ?>
        <?php if ((int)$arResult['START_PAGE'] === (int)$arResult['CURRENT_PAGE']): ?>
            <span class="pager__item active"><?= $arResult['CURRENT_PAGE'] ?></span>
        <?php elseif ((int)$arResult['START_PAGE'] === 1): ?>
            <a href="<?= $arParams['BASE_URL'] ?><?= $filterAlias ?>"
               class="pager__item">1</a>
        <?php else: ?>
            <a href="<?= $arParams['BASE_URL'] ?>page-<?= $arResult['START_PAGE'] ?>/<?= $filterAlias ?>"
               class="pager__item"><?= $arResult['START_PAGE'] ?></a>
        <?php endif; ?>
        <?php
        $arResult['START_PAGE']++;
        $isFirstPage = false;
        ?>
    <?php } while ((int)$arResult['START_PAGE'] <= (int)$arResult['END_PAGE']); ?>
    <?php /** Page listing end */ ?>


    <?php if ((int)$arResult['CURRENT_PAGE'] < (int)$arResult['PAGE_COUNT']): ?>
        <?php /** Last page display start */ ?>
        <?php if ((int)$arResult['END_PAGE'] < (int)$arResult['PAGE_COUNT']): ?>
            <?php if ((int)$arResult['END_PAGE'] < ((int)$arResult['PAGE_COUNT'] - 1)): ?>
                <span class="pager__item">...</span>
            <?php endif; ?>
            <a href="<?= $arParams['BASE_URL'] ?>page-<?= (int)$arResult['PAGE_COUNT'] ?>/<?= $filterAlias ?>"
               class="pager__item"><?= $arResult['PAGE_COUNT'] ?></a>
        <?php endif; ?>
        <?php /** Last page display end */ ?>
        <?php /** Arrow right start */ ?>
        <a href="<?= $arParams['BASE_URL'] ?>page-<?= $arResult['CURRENT_PAGE'] + 1 ?>/<?= $filterAlias ?>"
           class="pager__item pager__item--arrow">
            <svg class="icon">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-right"></use>
            </svg>
        </a>
        <?php /** Arrow right end */ ?>
    <?php endif; ?>
</div>