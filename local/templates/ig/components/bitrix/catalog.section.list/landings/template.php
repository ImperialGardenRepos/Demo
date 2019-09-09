<?php

use ig\CHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
$this->setFrameMode(true);

if ($arResult['SECTIONS'] !== []): ?>
    <div class="section section--grey section--article section--fullheight text">
        <div class="container">
            <div class="cols-wrapper cols-wrapper--h1-stabs">
                <div class="cols">
                    <div class="col col--heading">
                        <h1><?php $APPLICATION->ShowTitle(false) ?></h1>
                    </div>
                </div>
            </div>
            <div class="tgbs tgbs--mm">
                <div class="tgbs__inner">
                    <?php foreach ($arResult['SECTIONS'] as $section): ?>
                        <?php
                        $buttons = CIBlock::GetPanelButtons(
                            $section['IBLOCK_ID'],
                            0,
                            $section['ID'],
                            [
                                'SECTION_BUTTONS' => true,
                                'SESSID' => false
                            ]
                        );
                        $addLink = $buttons['edit']['add_section']['ACTION_URL'];
                        $addLinkText = $buttons['edit']['add_section']['TEXT'];

                        $this->AddEditAction($section['ID'], $addLink, $addLinkText);
                        $this->AddEditAction($section['ID'], $section['EDIT_LINK']);
                        $this->AddDeleteAction($section['ID'], $section['DELETE_LINK']);
                        ?>
                        <div id="<?= $this->GetEditAreaId($section['ID']) ?>"
                             class="tgb tgb--1-2 tgb--h50 tgb--img-hover">
                            <a class="tgb__inner" href="<?= $section['SECTION_PAGE_URL'] ?>">
                                <?php if ($section['PICTURE']): ?>
                                    <div class="tgb__image img-to-bg img-to-bg--inited"
                                         style="background-image: url(<?= $section['PICTURE']['SRC'] ?>);">
                                        <img alt="<?= $section['PICTURE']['ALT'] ?>"
                                             src="<?= $section['PICTURE']['SRC'] ?>"
                                             data-lazy-src="<?= $section['PICTURE']['SRC'] ?>"
                                             class="lazy-load-observer-inited lazy-loaded">
                                    </div>
                                <?php endif; ?>
                                <div class="tgb__overlay">
                                    <div class="tgb__overlay-part">
                                        <div class="tgb__overlay-part-inner">
                                            <div class="tgb__title h3">
                                                <?= $section['NAME'] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tgb__overlay-part mobile-hide">
                                        <div class="tgb__overlay-part-inner">
                                            <div class="text">
                                                <?php CHelper::renderText($section['DESCRIPTION'], $section['DESCRIPTION_TYPE']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>