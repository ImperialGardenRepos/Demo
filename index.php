<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Садовый центр и питомник растений Imperial Garden');
$APPLICATION->SetPageProperty('description', 'Садовый центр Imperial Garden - питомник и магазин, где можно купить садовые декоративные растения. Наш садовый центр удобно расположен в Московской области на Новорижском шоссе, вблизи Москвы.');
$APPLICATION->SetPageProperty('title', 'Питомник растений и садовый центр Imperial Garden на Новой Риге');

$APPLICATION->SetPageProperty('NOT_SHOW_NAV_CHAIN', 'Y');

$GLOBALS['arIndexServicesFilter'] = [
    '!PROPERTY_SORT_INDEX' => false,
];

?>
    <div class="section section--bring-to-front section--hero">
        <?php
        $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            [
                'AREA_FILE_SHOW' => 'file',
                'AREA_FILE_SUFFIX' => 'inc',
                'EDIT_TEMPLATE' => '',
                'PATH' => '/local/inc_area/index_banner_images.php',
            ]
        );
        ?>
        <div class="container">
            <div class="hero hero--autoheight">
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:news.list',
                    'index_advert',
                    [
                        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                        'ADD_SECTIONS_CHAIN' => 'N',
                        'AJAX_MODE' => 'N',
                        'AJAX_OPTION_ADDITIONAL' => '',
                        'AJAX_OPTION_HISTORY' => 'N',
                        'AJAX_OPTION_JUMP' => 'N',
                        'AJAX_OPTION_STYLE' => 'N',
                        'CACHE_FILTER' => 'Y',
                        'CACHE_GROUPS' => 'N',
                        'CACHE_TIME' => '36000000',
                        'CACHE_TYPE' => 'A',
                        'CHECK_DATES' => 'Y',
                        'DETAIL_URL' => '',
                        'DISPLAY_BOTTOM_PAGER' => 'N',
                        'DISPLAY_DATE' => 'N',
                        'DISPLAY_NAME' => 'N',
                        'DISPLAY_PICTURE' => 'N',
                        'DISPLAY_PREVIEW_TEXT' => 'N',
                        'DISPLAY_TOP_PAGER' => 'N',
                        'FIELD_CODE' => ['', ''],
                        'FILTER_NAME' => '',
                        'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                        'IBLOCK_ID' => '13',
                        'IBLOCK_TYPE' => 'services',
                        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                        'INCLUDE_SUBSECTIONS' => 'N',
                        'MESSAGE_404' => '',
                        'NEWS_COUNT' => '20',
                        'PAGER_BASE_LINK_ENABLE' => 'N',
                        'PAGER_DESC_NUMBERING' => 'N',
                        'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
                        'PAGER_SHOW_ALL' => 'N',
                        'PAGER_SHOW_ALWAYS' => 'N',
                        'PAGER_TEMPLATE' => '.default',
                        'PAGER_TITLE' => 'Новости',
                        'PARENT_SECTION' => '',
                        'PARENT_SECTION_CODE' => '',
                        'PREVIEW_TRUNCATE_LEN' => '',
                        'PROPERTY_CODE' => ['ICON', 'TEXT', ''],
                        'SET_BROWSER_TITLE' => 'N',
                        'SET_LAST_MODIFIED' => 'N',
                        'SET_META_DESCRIPTION' => 'N',
                        'SET_META_KEYWORDS' => 'N',
                        'SET_STATUS_404' => 'N',
                        'SET_TITLE' => 'N',
                        'SHOW_404' => 'N',
                        'SORT_BY1' => 'SORT',
                        'SORT_BY2' => 'ID',
                        'SORT_ORDER1' => 'ASC',
                        'SORT_ORDER2' => 'DESC',
                        'STRICT_SECTION_CHECK' => 'N',
                    ]
                );
                $APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    [
                        'AREA_FILE_SHOW' => 'file',
                        'AREA_FILE_SUFFIX' => 'inc',
                        'EDIT_TEMPLATE' => '',
                        'PATH' => '/local/inc_area/index_banner_links.php',
                    ]
                );
                $APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    [
                        'AREA_FILE_SHOW' => 'file',
                        'AREA_FILE_SUFFIX' => 'inc',
                        'EDIT_TEMPLATE' => '',
                        'PATH' => '/local/inc_area/index_banner_tabs.php',
                    ]
                );
                ?>
            </div>
        </div>
    </div>
<?php
$APPLICATION->IncludeComponent(
    'bitrix:main.include',
    '',
    [
        'AREA_FILE_SHOW' => 'file',
        'AREA_FILE_SUFFIX' => 'inc',
        'EDIT_TEMPLATE' => '',
        'PATH' => '/local/inc_area/index_tabs_side_teaser.php',
    ]
);
?>
    <div class="section section--block bg-pattern bg-pattern--grey-leaves section--saw-before" id="catalog"
         data-goto-offset-element=".header">
        <div class="container">
            <?php
            $APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '',
                [
                    'AREA_FILE_SHOW' => 'file',
                    'AREA_FILE_SUFFIX' => 'inc',
                    'EDIT_TEMPLATE' => '',
                    'PATH' => '/local/inc_area/index_catalog_text.php',
                ]
            );
            $APPLICATION->IncludeComponent(
                'ig:highloadblock.list',
                'index_groups',
                [
                    'BLOCK_ID' => '1',
                    'CHECK_PERMISSIONS' => 'N',
                    'DETAIL_URL' => '',
                    'FILTER_NAME' => '',
                    'PAGEN_ID' => '',
                    'ROWS_PER_PAGE' => '',
                    'SORT_FIELD' => 'ID',
                    'SORT_ORDER' => 'DESC',
                ]
            );
            ?>
            <div class="tgbs">
                <div class="tgbs__inner">
                    <div class="tgb tgb--featured tgb--img-hover">
                        <?php
                        $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'AREA_FILE_SUFFIX' => 'inc',
                                'EDIT_TEMPLATE' => '',
                                'PATH' => '/local/inc_area/index_virt_tour.php',
                            ]
                        );
                        ?>
                    </div>
                    <div class="tgb tgb--featured tgb--img-hover">
                        <?php
                        $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'AREA_FILE_SUFFIX' => 'inc',
                                'EDIT_TEMPLATE' => '',
                                'PATH' => '/local/inc_area/index_catalog_garden.php',
                            ]
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$APPLICATION->IncludeComponent(
    'bitrix:news.list',
    'index_services',
    [
        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
        'ADD_SECTIONS_CHAIN' => 'N',
        'AJAX_MODE' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'N',
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'N',
        'CACHE_TIME' => '36000000',
        'CACHE_TYPE' => 'A',
        'CHECK_DATES' => 'Y',
        'COMPONENT_TEMPLATE' => 'index_services',
        'DETAIL_URL' => '',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'DISPLAY_DATE' => 'N',
        'DISPLAY_NAME' => 'N',
        'DISPLAY_PICTURE' => 'N',
        'DISPLAY_PREVIEW_TEXT' => 'N',
        'DISPLAY_TOP_PAGER' => 'N',
        'FIELD_CODE' => [0 => '', 1 => '',],
        'FILTER_NAME' => 'arIndexServicesFilter',
        'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
        'IBLOCK_ID' => '6',
        'IBLOCK_TYPE' => 'services',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'INCLUDE_SUBSECTIONS' => 'N',
        'MESSAGE_404' => '',
        'NEWS_COUNT' => '100',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => '.default',
        'PAGER_TITLE' => 'Новости',
        'PARENT_SECTION' => '',
        'PARENT_SECTION_CODE' => '',
        'PREVIEW_TRUNCATE_LEN' => '',
        'PROPERTY_CODE' => [0 => 'BLOCK_COLOR_INDEX',],
        'SET_BROWSER_TITLE' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'SET_META_DESCRIPTION' => 'N',
        'SET_META_KEYWORDS' => 'N',
        'SET_STATUS_404' => 'N',
        'SET_TITLE' => 'N',
        'SHOW_404' => 'N',
        'SORT_BY1' => 'PROPERTY_SORT_INDEX',
        'SORT_BY2' => 'ID',
        'SORT_ORDER1' => 'ASC',
        'SORT_ORDER2' => 'DESC',
        'STRICT_SECTION_CHECK' => 'N',
    ]
);
$APPLICATION->IncludeComponent(
    'bitrix:catalog.section.list',
    'index_objects',
    [
        'ADD_SECTIONS_CHAIN' => 'N',
        'CACHE_GROUPS' => 'N',
        'CACHE_TIME' => '36000000',
        'CACHE_TYPE' => 'A',
        'COUNT_ELEMENTS' => 'N',
        'IBLOCK_ID' => '12',
        'IBLOCK_TYPE' => 'services',
        'SECTION_CODE' => '',
        'SECTION_FIELDS' => ['', ''],
        'SECTION_ID' => '',
        'SECTION_URL' => '',
        'SECTION_USER_FIELDS' => ['UF_LINK', ''],
        'SHOW_PARENT_NAME' => 'N',
        'TOP_DEPTH' => '1',
        'VIEW_MODE' => 'LINE',
    ]
);
$APPLICATION->IncludeComponent(
    'bitrix:news.list',
    'index_dosug',
    [
        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
        'ADD_SECTIONS_CHAIN' => 'N',
        'AJAX_MODE' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'N',
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'N',
        'CACHE_TIME' => '36000000',
        'CACHE_TYPE' => 'A',
        'CHECK_DATES' => 'Y',
        'COMPONENT_TEMPLATE' => 'index_services',
        'DETAIL_URL' => '',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'DISPLAY_DATE' => 'N',
        'DISPLAY_NAME' => 'N',
        'DISPLAY_PICTURE' => 'N',
        'DISPLAY_PREVIEW_TEXT' => 'N',
        'DISPLAY_TOP_PAGER' => 'N',
        'FIELD_CODE' => [0 => '', 1 => '',],
        'FILTER_NAME' => '',
        'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
        'IBLOCK_ID' => '20',
        'IBLOCK_TYPE' => 'onas',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'INCLUDE_SUBSECTIONS' => 'N',
        'MESSAGE_404' => '',
        'NEWS_COUNT' => '100',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => '.default',
        'PAGER_TITLE' => 'Новости',
        'PARENT_SECTION' => '',
        'PARENT_SECTION_CODE' => '',
        'PREVIEW_TRUNCATE_LEN' => '',
        'PROPERTY_CODE' => [0 => 'INDEX_TEXT',],
        'SET_BROWSER_TITLE' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'SET_META_DESCRIPTION' => 'N',
        'SET_META_KEYWORDS' => 'N',
        'SET_STATUS_404' => 'N',
        'SET_TITLE' => 'N',
        'SHOW_404' => 'N',
        'SORT_BY1' => 'SORT',
        'SORT_BY2' => 'ID',
        'SORT_ORDER1' => 'ASC',
        'SORT_ORDER2' => 'DESC',
        'STRICT_SECTION_CHECK' => 'N',
    ]
);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';