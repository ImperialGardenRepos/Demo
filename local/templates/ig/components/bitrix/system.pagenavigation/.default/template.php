<?php

use Bitrix\Main\Application;
use ig\Seo\Meta;

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

$meta = Meta::getInstance();
$meta->setCurrentPage((int)$arResult['NavPageNomer']);
$meta->setTotalPage((int)$arResult['NavPageCount']);

$requestArray = Application::getInstance()->getContext()->getRequest()->toArray();
$allowedParams = [
    'filterAlias'
];
$requestArrayClean = [];
$navQueryStringFull = '';
foreach ($requestArray as $key => $value) {
    if (in_array($key, $allowedParams, true)) {
        $requestArrayClean[] = "$key=$value";
    }
}
if ($requestArrayClean !== []) {
    $navQueryStringFull = '?' . implode('&', $requestArrayClean);
}

?>
<div class="pager js-pagination" data-current-page="<?=$arResult['NavPageNomer']?>" data-total-page="<?=$arResult['NavPageCount']?>">
    <?php
    $bFirst = true;

    if ((int)$arResult['NavPageNomer'] > 1):?>
        <?php if ($arResult['bSavePage']): ?>
            <a href="<?= $arResult['sUrlPath'] ?>page-<?= (int)$arResult['NavPageNomer'] - 1 ?>/<?= $navQueryStringFull ?>"
               class="pager__item pager__item--arrow">
                <svg class="icon">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
                </svg>
            </a>
        <?php else: ?>
            <?php if ((int)$arResult['NavPageNomer'] > 2): ?>
                <a href="<?= $arResult["sUrlPath"] ?>page-<?= (int)$arResult['NavPageNomer'] - 1?>/<?= $navQueryStringFull ?>"
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

        <?php if ((int)$arResult['nStartPage'] > 1): ?>
            <?php
            $bFirst = false;
            ?>
            <?php if ($arResult['bSavePage']): ?>
                <a href="<?= $arResult['sUrlPath'] ?><?= $navQueryStringFull ?>"
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
        <?php elseif ((int)$arResult['nStartPage'] === 1 && (bool)$arResult['bSavePage'] === false): ?>
            <a href="<?= $arResult['sUrlPath'] ?><?= $navQueryStringFull ?>"
               class="pager__item"><?= $arResult['nStartPage'] ?></a>
        <?php else: ?>
            <a href="<?= $arResult['sUrlPath'] ?>page-<?= $arResult['nStartPage'] ?>/<?= $navQueryStringFull ?>"
               class="pager__item"><?= $arResult['nStartPage'] ?></a>
        <?php endif; ?>
        <?php
        $arResult['nStartPage']++;
        $bFirst = false;
        ?>
    <?php } while ((int)$arResult['nStartPage'] <= (int)$arResult['nEndPage']); ?>
    <?php if ((int)$arResult['NavPageNomer'] < (int)$arResult['NavPageCount']): ?>
        <?php if ((int)$arResult['nEndPage'] < (int)$arResult['NavPageCount']): ?>
            <?php if ((int)$arResult['nEndPage'] < ((int)$arResult['NavPageCount'] - 1)): ?>
                <span class="pager__item">...</span>
            <?php endif; ?>
            <a href="<?= $arResult['sUrlPath'] ?>page-<?= (int)$arResult['NavPageCount'] ?>/<?= $navQueryStringFull ?>"
               class="pager__item"><?= $arResult['NavPageCount'] ?></a>
        <?php endif; ?>
        <a href="<?= $arResult['sUrlPath'] ?>page-<?= $arResult['NavPageNomer'] + 1 ?>/<?= $navQueryStringFull ?>"
           class="pager__item pager__item--arrow">
            <svg class="icon">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-right"></use>
            </svg>
        </a>
    <?php endif; ?>
</div>