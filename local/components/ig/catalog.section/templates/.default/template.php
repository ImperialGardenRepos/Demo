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

/** @var Bitrix\Main\UI\PageNavigation $navObject */
$navObject = $arResult['NAV_PARAMS']['NAV_OBJECT'];
$currentPage = $navObject->getCurrentPage();
$nextPage = $currentPage + 1;
?>

<div class="section section--results section--grey js-section" data-remove-pages="true">
    <div class="container">
        <h1 class="h1--large"><?= $APPLICATION->GetTitle('title') ?></h1>
        <?php include '_sort.php'?>
        <div class="filter-results js-filter-results">
            <div class="icards icards">
                <div class="icards__inner js-cards-list"
                     data-scroll-load-filter-alias="<?= $arResult['NAV_PARAMS']['FILTER_ALIAS'] ?? '' ?>"
                     data-scroll-load-url="<?= $arParams['BASE_URL'] ?>" data-hide-on-last-load="#action-more"
                     data-scroll-load-page="<?= $nextPage ?>" data-scroll-load-inactive-class="hidden">
                    <?php foreach ($arResult['ITEMS'] as $item): ?>
                        <?php include '_item.php' ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        $APPLICATION->IncludeComponent(
            'bitrix:main.pagenavigation',
            'catalog',
            $arResult['NAV_PARAMS'],
            false
        );
        ?>
        <?php if ($currentPage === 1 && $arResult['DESCRIPTION']): ?>
            <div class="text p js-section-text">
                <?= $arResult['DESCRIPTION'] ?>
            </div>
        <?php endif; ?>
    </div>
</div>
