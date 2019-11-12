<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use ig\CHelper;

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
/** @var CBitrixComponent $componentName */
$isGridStarted = false;
?>
<?php if ($arResult['ITEMS'] !== []): ?>
    <?php
    $displayedBlocks = [];
    ?>
    <?php foreach ($arResult['ITEMS'] as $id => $item): ?>
        <?php
        /**
         * Skip blocks that were displayed already
         */
        if (in_array($id, $displayedBlocks, true)) {
            continue;
        }
        /**
         * Some transformations and useful variables common for any block type, like alignment
         */
        $props = $item['PROPERTIES'];
        $textVerticalAlignment = $props['TEXT_VERTICAL_ALIGN']['VALUE_XML_ID'] ?? 'bottom';
        $textHorizontalAlignment = $props['TEXT_HORIZONTAL_ALIGN']['VALUE_XML_ID'] ?? 'left';
        $headingTag = $props['HEADING_TYPE']['VALUE_XML_ID'] ?? 'div';
        $type = $props['BLOCK_TYPE']['VALUE_XML_ID'];

        /**
         * Edit and delete links for WYSIWYG editing
         */
        $buttons = CIBlock::GetPanelButtons(
            $item['IBLOCK_ID'],
            $item['ID'],
            $arResult['SECTION']['ID'],
            [
                'SECTION_BUTTONS' => false,
                'SESSID' => false,

            ]
        );
        $addLink = $buttons['edit']['add_element']['ACTION_URL'];
        $addLinkText = $buttons['edit']['add_element']['TEXT'];
        $editLink = $buttons['edit']['edit_element']['ACTION_URL'];
        $editLinkText = $buttons['edit']['edit_element']['TEXT'];
        $deleteLink = $buttons['edit']['delete_element']['ACTION_URL'];
        $deleteLinkText = $buttons['edit']['delete_element']['TEXT'];
        ?>
        <?php
        /**
         * Banner block
         */
        ?>
        <?php if ($type === 'banner'): ?>
            <?php
            $bannerWideClass = $props['STRETCH_TO_FULL_WIDTH']['VALUE'] === 1 ? 'banner--full-width' : '';

            /**
             * If link exists and no link text is provided, the whole banner should be hyperlink.
             * If text exists, button with hyperlink is displayed.
             * If no link exists, banner is not clickable.
             */

            /**
             * Idk if this is bug or feature, but placing this just after foreach don't work,
             * so we have to duplicate button call for every block type
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
            ?>
            <?php if ($props['LINK']['VALUE'] !== '' && $props['LINK_TEXT']['VALUE'] === ''): ?>
                <a class="landing-section banner <?= $bannerWideClass ?> banner--<?= $textVerticalAlignment ?> banner--<?= $textHorizontalAlignment ?>"
                   href="<?= $props['LINK']['VALUE'] ?>"
                   style="background: url(<?= CFile::GetPath($props['IMAGE']['VALUE']) ?>)">
                    <div class="banner__content" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                        <?php
                        if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                            $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                            echo "
                            <{$headingTag} class='banner__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                        }
                        ?>
                        <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                            <div class="banner__text">
                                <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php else: ?>
                <section
                        class="landing-section banner <?= $bannerWideClass ?> banner--<?= $textVerticalAlignment ?> banner--<?= $textHorizontalAlignment ?>"
                        style="background: url(<?= CFile::GetPath($props['IMAGE']['VALUE']) ?>)">
                    <div class="banner__content" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                        <?php
                        if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                            $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                            echo "
                            <{$headingTag} class='banner__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                        }
                        ?>
                        <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                            <div class="banner__text">
                                <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($props['LINK']['VALUE'] !== '' && $props['LINK_TEXT']['VALUE'] !== ''): ?>
                            <a class="btn btn--medium minw200 active btn--margined"
                               href="<?= $props['LINK']['VALUE'] ?>"><?= $props['LINK_TEXT']['VALUE'] ?></a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
            <?php continue; ?>
        <?php endif; ?>
        <?php
        /**
         * Common text block
         */
        ?>
        <?php if ($type === 'text'): ?>
            <?php
            /**
             * Getting classes for floating images and corresponding text blocks
             * Image alignment here too
             */
            $imageFlow = $props['IMAGE_FLOW']['VALUE_XML_ID'];
            $imageFlowClass = "text__content--img-flow-{$imageFlow}";
            $imageWidthClass = '';
            if ($imageFlow === 'left' || $imageFlow === 'right') {
                $imageMaxWidth = $props['IMAGE_MAX_WIDTH']['VALUE_XML_ID'] ?? 40;
                $imageWidthClass = "text__content--img-width-{$imageMaxWidth}";
            }
            $imgHorizontalAlignment = $props['IMAGE_HORIZONTAL_ALIGN']['VALUE_XML_ID'] ?? 'left';
            $imgVerticalAlignment = $props['IMAGE_VERTICAL_ALIGN']['VALUE_XML_ID'] ?? 'center';

            /**
             * Have to duplicate these, otherwise they simply don't work
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
            ?>
            <section class="landing-section text" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                <?php
                if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                    $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                    echo "
                            <{$headingTag} class='text__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                }
                ?>
                <div class="text__content <?= $imageFlowClass ?> <?= $imageWidthClass ?>">
                    <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                        <div class="text__text text__text--<?= $textVerticalAlignment ?> text__text--<?= $textHorizontalAlignment ?>">
                            <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($props['IMAGE']['VALUE'] !== ''): ?>
                        <div class="text__img text__img--<?= $imgVerticalAlignment ?> text__img--<?= $imgHorizontalAlignment ?>">
                            <img src="<?= CFile::GetPath($props['IMAGE']['VALUE']) ?>"
                                 alt="<?= $item['NAME'] ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php continue; ?>
        <?php endif; ?>
        <?php
        /**
         * Table block
         */
        ?>
        <?php if ($type === 'table'): ?>
            <?php
            /**
             * Have to duplicate these, otherwise they simply don't work
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
            ?>
            <section class="landing-section text" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                <?php
                if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                    $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                    echo "
                            <{$headingTag} class='text__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                }
                ?>
                <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                    <div class="text__content">
                        <div class="text__text">
                            <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
            <?php continue; ?>
        <?php endif; ?>
        <?php
        /**
         * Linked products block
         */
        ?>
        <?php if ($type === 'product'): ?>
            <?php
            $productPlants = $props['PRODUCT_PLANT']['VALUE'];
            $productGarden = $props['PRODUCT_GARDEN']['VALUE'];

            $componentName = $props['PRODUCT_TEMPLATE']['VALUE_XML_ID'] ?? 'ig:catalog.section';

            /**
             * Have to duplicate these, otherwise they simply don't work
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
            ?>
            <section class="landing-section products text" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                <?php
                if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                    $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                    echo "
                            <{$headingTag} class='products__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                }
                ?>
                <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                    <div class="text__content">
                        <div class="text__text">
                            <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php
                if ($productPlants !== false) {
                    global $arCatalogPlantFilter;
                    $arCatalogPlantFilter = [
                        'ID' => $productPlants,
                    ];
                    $APPLICATION->IncludeComponent(
                        $componentName,
                        '',
                        [
                            'IBLOCK_TYPE' => CATALOG_IBLOCK_TYPE,
                            'IBLOCK_ID' => CATALOG_IBLOCK_ID,
                            'NEWS_COUNT' => count($productPlants),
                            'PRICE_CODE' => [
                                'BASE',
                            ],
                            'SORT_BY1' => $props['SORT_FIELD']['VALUE_XML_ID'] ?? 'SORT',
                            'SORT_ORDER1' => $props['SORT_FIELD']['VALUE_XML_ID'] ?? 'ASC',
                            'FILTER_NAME' => 'arCatalogPlantFilter',
                            'USE_FILTER' => 'Y',
                            'SHOW_ALL_WO_SECTION' => 'Y',
                            'SEF_MODE' => 'Y',

                            'SEF_FOLDER' => '/katalog/rasteniya/',
                            'SEF_URL_TEMPLATES' => [
                                'compare' => 'compare.php?action=#ACTION_CODE#',
                                'element' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
                                'section' => '#SECTION_CODE_PATH#/',
                                'sections' => '',
                                'smart_filter' => '#SECTION_ID#/filter/#SMART_FILTER_PATH#/apply/',
                            ],
                            'SET_TITLE' => 'N',
                            'SET_BROWSER_TITLE' => 'N',
                            'SET_META_KEYWORDS' => 'N',
                            'SET_META_DESCRIPTION' => 'N',
                            'SET_LAST_MODIFIED' => 'Y',

                        ],
                        false
                    );
                }
                if ($productGarden !== false) {
                    global $arCatalogGardenFilter;
                    $arCatalogGardenFilter = [
                        'ID' => $productGarden,
                    ];
                    $APPLICATION->IncludeComponent(
                        $componentName,
                        '',
                        [
                            'IBLOCK_TYPE' => CATALOG_IBLOCK_TYPE,
                            'IBLOCK_ID' => CATALOG_GARDEN_IBLOCK_ID,
                            'NEWS_COUNT' => count($productGarden),
                            'PRICE_CODE' => [
                                'BASE',
                            ],
                            'SORT_BY1' => $props['SORT_FIELD']['VALUE_XML_ID'] ?? 'SORT',
                            'SORT_ORDER1' => $props['SORT_FIELD']['VALUE_XML_ID'] ?? 'ASC',
                            'FILTER_NAME' => 'arCatalogGardenFilter',
                            'USE_FILTER' => 'Y',
                            'SHOW_ALL_WO_SECTION' => 'Y',
                            'SEF_MODE' => 'Y',

                            'SEF_FOLDER' => '/katalog/tovary-dlya-sada/',
                            'SEF_URL_TEMPLATES' => [
                                'compare' => 'compare.php?action=#ACTION_CODE#',
                                'element' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
                                'section' => '#SECTION_CODE_PATH#/',
                                'sections' => '',
                                'smart_filter' => '#SECTION_ID#/filter/#SMART_FILTER_PATH#/apply/',
                            ],
                            'SET_TITLE' => 'N',
                            'SET_BROWSER_TITLE' => 'N',
                            'SET_META_KEYWORDS' => 'N',
                            'SET_META_DESCRIPTION' => 'N',
                            'SET_LAST_MODIFIED' => 'Y',

                        ],
                        false
                    );

                }

                ?>
            </section>
            <?php continue; ?>
        <?php endif; ?>
        <?php
        /**
         * Form block
         */
        ?>
        <?php if ($type === 'form'): ?>
            <?php

            $formTemplate = $props['FORM_TEMPLATE']['VALUE_XML_ID'] ?? 'row';

            /**
             * Have to duplicate these, otherwise they simply don't work
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
            ?>
            <section class="landing-section text" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                <?php
                if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                    $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                    echo "
                            <{$headingTag} class='products__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                }
                ?>
                <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                    <div class="text__content">
                        <div class="text__text">
                            <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:form.result.new',
                    $formTemplate,
                    [
                        'SEF_MODE' => 'Y',
                        'WEB_FORM_ID' => $props['FORM_ID']['VALUE'],
                        'SUCCESS_URL' => '',
                        'CHAIN_ITEM_TEXT' => '',
                        'CHAIN_ITEM_LINK' => '',
                        'IGNORE_CUSTOM_TEMPLATE' => 'Y',
                        'USE_EXTENDED_ERRORS' => 'Y',
                        'CACHE_TYPE' => 'A',
                        'CACHE_TIME' => '3600',
                        'SEF_FOLDER' => '/promo',
                        'VARIABLE_ALIASES' => [],
                    ],
                    false
                );
                ?>
            </section>
            <?php continue; ?>
        <?php endif; ?>
        <?php
        /**
         * Map block
         */
        ?>
        <?php if ($type === 'map'): ?>
            <?php
            if (is_array($props['LINKED_MAPS']['VALUE']) && $props['LINKED_MAPS']['VALUE'] !== []) {
                $maps = [$item];
                foreach ($props['LINKED_MAPS']['VALUE'] as $linkedMapId) {
                    if (array_key_exists((int)$linkedMapId, $arResult['ITEMS'])) {
                        $maps[] = $arResult['ITEMS'][(int)$linkedMapId];
                        $displayedBlocks[] = (int)$linkedMapId;
                    }
                }
            }
            ?>
            <section class="landing-section maps text">

                <?php if ($maps === null): ?>
                    <?php
                    if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== '') {
                        $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                        echo "
                            <{$headingTag} class='products__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                    }
                    ?>
                    <?php if ($props['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                        <div class="text__content">
                            <div class="text__text">
                                <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $this->AddEditAction($item['ID'], $addLink, $addLinkText);
                    $this->AddEditAction($item['ID'], $editLink, $editLinkText);
                    $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
                    ?>
                    <div class="ltabs-wrapper contacts container-anti-mobile">
                        <div class="contacts__map" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                            <div class="map js-contacts-map"
                                 data-point="<?= $item['PROPERTIES']['MAP_POINT']['VALUE'] ?>"
                                 data-center="<?= $item['PROPERTIES']['MAP_CENTER']['VALUE'] ?>"
                                 data-zoom="<?= $item['PROPERTIES']['MAP_ZOOM']['VALUE'] ?>">
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="ltabs-wrapper contacts container-anti-mobile">
                        <div class="ltabs js-tabs">
                            <div class="ltabs__list">
                                <?php foreach ($maps as $key => $map): ?>
                                    <?php
                                    /**
                                     * As we show several blocks in one iteration here, we have to generate edit links specially for each block
                                     */
                                    $buttons = CIBlock::GetPanelButtons(
                                        $map['IBLOCK_ID'],
                                        $map['ID'],
                                        $arResult['SECTION']['ID'],
                                        [
                                            'SECTION_BUTTONS' => false,
                                            'SESSID' => false,

                                        ]
                                    );
                                    $addLink = $buttons['edit']['add_element']['ACTION_URL'];
                                    $addLinkText = $buttons['edit']['add_element']['TEXT'];
                                    $editLink = $buttons['edit']['edit_element']['ACTION_URL'];
                                    $editLinkText = $buttons['edit']['edit_element']['TEXT'];
                                    $deleteLink = $buttons['edit']['delete_element']['ACTION_URL'];
                                    $deleteLinkText = $buttons['edit']['delete_element']['TEXT'];

                                    $this->AddEditAction($map['ID'], $addLink, $addLinkText);
                                    $this->AddEditAction($map['ID'], $editLink, $editLinkText);
                                    $this->AddDeleteAction($map['ID'], $deleteLink, $deleteLinkText);
                                    ?>
                                    <div class="ltabs__item ltabs__item--noborder ltabs__item--nohover text-align-center <?= $key === 0 ? 'active' : '' ?>"
                                         id="<?= $this->GetEditAreaId($map['ID']) ?>">
                                        <a class="ltabs__link ltabs__link--expand-click-area ltabs__link--dotted"
                                           href="#map-<?= $map['ID'] ?>">
                                            <?= CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false) ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-panes">
                            <?php foreach ($maps as $key => $map): ?>
                                <div class="tab-pane tab-pane--default tab-pane--hidden <?= $key === 0 ? 'active' : '' ?> js-contacts-tab-pane"
                                     id="map-<?= $map['ID'] ?>"
                                     data-point="<?= $map['PROPERTIES']['MAP_POINT']['VALUE'] ?>"
                                     data-zoom="<?= $map['PROPERTIES']['MAP_ZOOM']['VALUE'] ?>">
                                    <?php if ($map['PROPERTIES']['BLOCK_TEXT']['VALUE']['TEXT'] !== ''): ?>
                                        <?php CHelper::renderText($props['BLOCK_TEXT']['VALUE']['TEXT'], $props['BLOCK_TEXT']['VALUE']['TYPE']) ?>
                                    <?php endif; ?>
                                    <?php if ($map['PROPERTIES']['EMAIL']['VALUE'] !== ''): ?>
                                        <div class="h4">
                                            <a href="mailto:<?= $map['PROPERTIES']['EMAIL']['VALUE'] ?>"
                                               class="link--bordered-pseudo"><?= $map['PROPERTIES']['EMAIL']['VALUE'] ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="contacts__map">
                            <div class="map js-contacts-map"
                                 data-point="<?= $maps[0]['PROPERTIES']['MAP_POINT']['VALUE'] ?>"
                                 data-center="<?= $maps[0]['PROPERTIES']['MAP_CENTER']['VALUE'] ?>"
                                 data-zoom="<?= $maps[0]['PROPERTIES']['MAP_ZOOM']['VALUE'] ?>">
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </section>

            <?php continue; ?>
        <?php endif; ?>
        <?php
        /**
         * File block
         */
        ?>
        <?php if ($type === 'file'): ?>
            <?php

            /**
             * Have to duplicate these, otherwise they simply don't work
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);

            if ($props['BLOCK_HEADING']['VALUE']['TEXT'] !== ''):?>
                <div class="text landing-section">
                    <?php
                    $heading = CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false);
                    echo "
                            <{$headingTag} class='text__heading'>
                                {$heading}
                            </{$headingTag}>
                            ";
                    ?>
                </div>
            <?php endif; ?>
            <div class="action-bar h3 text-align-center" id="<?= $this->GetEditAreaId($item['ID']) ?>">
                <?php if ($props['FILE']['VALUE'] !== ''): ?>
                    <a target="_blank" href="<?= CFile::GetPath($props['FILE']['VALUE']) ?>"
                       class="nounderline-important">
                        <span class="link link--bordered-pseudo">
                            <?= CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false) ?>
                        </span>
                        <?php if ($props['ICON']['VALUE'] !== ''): ?>
                            <img src="<?= CFile::GetPath($props['ICON']['VALUE']) ?>"
                                 alt="<?= CHelper::renderText($props['BLOCK_HEADING']['VALUE']['TEXT'], $props['BLOCK_HEADING']['VALUE']['TYPE'], false) ?>">
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php
        /**
         * Grid block
         */
        ?>
        <?php if ($type !== 'grid' && $isGridStarted === true) {
            echo '</div>';
            $isGridStarted = false;
        } ?>

        <?php if ($type === 'grid'): ?>
            <?php
            if ($isGridStarted === false) {
                $isGridStarted = true;
                echo '<div class="row">';
            }
            /**
             * Have to duplicate these, otherwise they simply don't work
             */
            $this->AddEditAction($item['ID'], $addLink, $addLinkText);
            $this->AddEditAction($item['ID'], $editLink, $editLinkText);
            $this->AddDeleteAction($item['ID'], $deleteLink, $deleteLinkText);
            ?>
            <div class="column-4">
                <div class="grid-item js-grid-item">
                    <?php if ($props['IMAGE']['VALUE']): ?>
                        <img class="grid-item__img" alt="<?= $props['LINK_TEXT']['VALUE'] ?>"
                             src="<?= CFile::GetPath($props['IMAGE']['VALUE']) ?>">
                    <?php endif; ?>
                    <?php if ($props['LINK']['VALUE'] !== '' && $props['LINK_TEXT']['VALUE'] !== ''): ?>
                        <a class="grid-item__link" href="<?= $props['LINK']['VALUE'] ?>">
                            <span class="grid-item__link-inner"><?= $props['LINK_TEXT']['VALUE'] ?></span>
                        </a>
                    <?php endif; ?>
                    <div class="grid-item__overlay js-grid-item-overlay">
                        <?php if (count($props['SUBLINKS']['VALUE']) > 0 && count($props['SUBLINKS_TEXTS']['VALUE']) > 0): ?>
                            <div class="link-grid">
                                <?php foreach ($props['SUBLINKS']['VALUE'] as $subLinkKey => $subLink): ?>
                                    <a class="link-grid__item" href="<?= $subLink ?>">
                                        <?= $props['SUBLINKS_TEXTS']['VALUE'][$subLinkKey] ?? $subLink ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>

    <?php
    $buttons = CIBlock::GetPanelButtons(
        $arResult['SECTION']['IBLOCK_ID'],
        0,
        $arResult['SECTION']['ID'],
        [
            'SECTION_BUTTONS' => false,
            'SESSID' => false,

        ]
    );
    $addLink = $buttons['edit']['add_element']['ACTION_URL'];
    $addLinkText = $buttons['edit']['add_element']['TEXT'];

    $this->AddEditAction($arResult['SECTION']['ID'], $addLink, $addLinkText);
    ?>
    <div class="text landing-section landing-section--dummy"
         id="<?= $this->GetEditAreaId($arResult['SECTION']['ID']) ?>"></div>
<?php endif; ?>