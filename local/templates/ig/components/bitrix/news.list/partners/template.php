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
?>
<div class="section section--grey section--article section--fullheight text">
    <div class="container">
        <h1><?= $arResult['NAME'] ?></h1>
        <p>
            <?php
            $APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '',
                [
                    'AREA_FILE_SHOW' => 'file',
                    'AREA_FILE_SUFFIX' => 'inc',
                    'EDIT_TEMPLATE' => '',
                    'PATH' => '/local/inc_area/partners-text.php'
                ]
            ); ?>
        </p>

        <?php if ($arResult['ITEMS'] !== []): ?>
            <div class="partners">
                <div class="partners__inner">
                    <?php foreach ($arResult['ITEMS'] as $arItem): ?>
                        <div class="partner">
                            <div class="partner__inner">
                                <?php if ($arItem['PREVIEW_PICTURE']): ?>
                                    <div class="partner__image">
                                        <img data-lazy-src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt="" src="">
                                    </div>
                                <?php endif; ?>
                                <div class="partner__title"><?= $arItem['NAME'] ?></div>
                                <?php if ($arItem['PROPERTIES']['LINK']['VALUE']) : ?>
                                    <div class="partner__summary">
                                        <!--noindex-->
                                        <a rel="nofollow" class="link--ib link--bordered text-ellipsis" target="_blank"
                                           href="<?= $arItem['PROPERTIES']['LINK']['VALUE'] ?>">
                                            <?= str_replace(['http://', 'https://'], '', $arItem['PROPERTIES']['LINK']['VALUE']) ?>
                                        </a>
                                        <!--/noindex-->
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?= $arResult['NAV_STRING']; ?>
    </div>
</div>