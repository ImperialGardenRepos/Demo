<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var CMain $APPLICATION
 */

$this->setFrameMode(true);

if (!$arResult['NavShowAlways']) {
    if (
        (int)$arResult['NavRecordCount'] === 0
        || ((int)$arResult['NavPageCount'] === 1 && (bool)$arResult['NavShowAll'] === false)
    ) {
        return;
    }
}
$arResult['sUrlPath'] = str_replace('page-' . $arResult['NavPageNomer'] . '/', '', $arResult['sUrlPath']);
?>
<?php
/**
 * Setting view targets for meta
 */

$pageNum = '';
if ($arResult['NavPageNomer'] > 1) {
    $pageNum = ' – страница ' . $arResult['NavPageNomer'] . ' из ' . $arResult['NavPageCount'];
}
$APPLICATION->AddViewContent('pagination_meta', $pageNum);
$APPLICATION->AddViewContent('canonical', '<link rel="canonical" href="' . $arResult['sUrlPath'] . '">');
?>
<div class="pager">
    <?php
    $navQueryStringFull = ((string)$arResult['NavQueryString'] !== '' ? '?' . $arResult['NavQueryString'] : '');

    $bFirst = true;

    if ($arResult['NavPageNomer'] > 1):?>
        <?php if ($arResult['bSavePage']): ?>
            <a href="<?= $arResult['sUrlPath'] ?>page-<?= ($arResult['NavPageNomer'] - 1) ?>/<?= $navQueryStringFull ?>"
               class="pager__item pager__item--arrow">
                <svg class="icon">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
                </svg>
            </a>
        <?php else: ?>
            <?php if ($arResult['NavPageNomer'] > 2): ?>
                <a href="<?= $arResult["sUrlPath"] ?>page-<?= ($arResult['NavPageNomer'] - 1) ?>/?<?= $navQueryStringFull ?>"
                   class="pager__item pager__item--arrow">
                    <svg class="icon">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
                    </svg>
                </a>
            <?php else: ?>
                <a href="<?= $arResult['sUrlPath'] ?><?= $navQueryStringFull ?>"
                   class="pager__item pager__item--arrow">
                    <svg class="icon">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
                    </svg>
                </a>

            <?php endif; ?>
        <?php endif; ?>

        <?php if ($arResult['nStartPage'] > 1): ?>
            <?php
            $bFirst = false;
            ?>
            <?php if ($arResult['bSavePage']): ?>
                <a href="<?= $arResult['sUrlPath'] ?>/page-1/<?= $navQueryStringFull ?>"
                   class="pager__item">1</a>
            <?php else: ?>
                <a href="<?= $arResult['sUrlPath'] ?><?= $navQueryStringFull ?>" class="pager__item">1</a>
            <?php endif; ?>
            <?php if ($arResult['nStartPage'] > 2): ?>
                <span class="pager__item mobile-hide">...</span>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php do { ?>
        <?php if ((int)$arResult['nStartPage'] === (int)$arResult['NavPageNomer']): ?>
            <span class="pager__item active"><?= $arResult['nStartPage'] ?></span>
        <?php elseif ($arResult['nStartPage'] === 1 && $arResult['bSavePage'] === false): ?>
            <a href="<?= $arResult['sUrlPath'] ?><?= $navQueryStringFull ?>"
               class="pager__item"><?= $arResult['nStartPage'] ?></a>
        <?php else: ?>
            <a href="<?= $arResult['sUrlPath'] ?>page-<?= ($arResult['nStartPage']) ?>/<?= $navQueryStringFull ?>"
               class="pager__item"><?= $arResult['nStartPage'] ?></a>
        <?php endif; ?>
        <?php
        $arResult['nStartPage']++;
        $bFirst = false;
        ?>
    <?php } while ($arResult['nStartPage'] <= $arResult['nEndPage']); ?>
    <?php if ($arResult['NavPageNomer'] < $arResult['NavPageCount']): ?>
        <?php if ($arResult['nEndPage'] < $arResult['NavPageCount']): ?>
            <?php if ($arResult['nEndPage'] < ($arResult['NavPageCount'] - 1)): ?>
                <span class="pager__item">...</span>
            <?php endif; ?>
            <a href="<?= $arResult['sUrlPath'] ?>page-<?= $arResult['NavPageCount'] ?>/<?= $navQueryStringFull ?>"
               class="pager__item"><?= $arResult['NavPageCount'] ?></a>
        <?php endif; ?>
        <a href="<?= $arResult['sUrlPath'] ?>page-<?= ($arResult['NavPageNomer'] + 1) ?>/<?= $navQueryStringFull ?>"
           class="pager__item pager__item--arrow">
            <svg class="icon">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-right"></use>
            </svg>
        </a>
    <?php endif; ?>
</div>