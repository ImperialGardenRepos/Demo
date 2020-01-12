<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Садовый центр и питомник растений Imperial Garden");

$APPLICATION->SetPageProperty("description", "Садовый центр Imperial Garden - питомник и магазин, где можно купить садовые декоративные растения. Наш садовый центр удобно расположен в Московской области на Новорижском шоссе, вблизи Москвы.");
$APPLICATION->SetPageProperty("keywords", "садовый центр, Imperial Garden, сц на Новой Риге");
$APPLICATION->SetPageProperty("title", "Питомник растений и садовый центр Imperial Garden на Новой Риге");

$APPLICATION->SetPageProperty("big-background-image-path", "/local/templates/ig/pic/desktopderevo2.jpg");
$APPLICATION->SetPageProperty("big-background-image-path-mobile", "/local/templates/ig/pic/mobderevo4.jpg");

$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");

$GLOBALS["arIndexServicesFilter"] = [
    "!PROPERTY_SORT_INDEX" => false,
];

$strImagePath = $APPLICATION->GetProperty("big-background-image-path");
if (empty($strImagePath)) {
    $strImagePath = SITE_TEMPLATE_PATH . "/pic/hero-image.jpg";
}

$strImagePathMobile = $APPLICATION->GetProperty("big-background-image-path-mobile");
if (empty($strImagePath)) {
    $strImagePathMobile = SITE_TEMPLATE_PATH . "/pic/hero2-image-720.jpg";
}
?>
    <div class="section section--bring-to-front section--hero">
        <div class="section__bg img-to-bg mobile-hide">
            <img data-lazy-src="/upload/medialibrary/e02/bann.jpg" src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png" alt="">
        </div>
        <div class="section__bg img-to-bg mobile-show">
            <img data-lazy-src="/upload/medialibrary/e24/bann_m-_2_.jpg" src="<?= SITE_TEMPLATE_PATH ?>/img/blank.png"
                 alt="">
        </div>
        <div class="container">
            <div class="hero hero--autoheight">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "index_advert",
                    [
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "N",
                        "CACHE_FILTER" => "Y",
                        "CACHE_GROUPS" => "N",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "N",
                        "DISPLAY_PICTURE" => "N",
                        "DISPLAY_PREVIEW_TEXT" => "N",
                        "DISPLAY_TOP_PAGER" => "N",
                        "FIELD_CODE" => ["", ""],
                        "FILTER_NAME" => "",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "IBLOCK_ID" => "13",
                        "IBLOCK_TYPE" => "services",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "INCLUDE_SUBSECTIONS" => "N",
                        "MESSAGE_404" => "",
                        "NEWS_COUNT" => "20",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "PAGER_TITLE" => "Новости",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "PROPERTY_CODE" => ["ICON", "TEXT", ""],
                        "SET_BROWSER_TITLE" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_STATUS_404" => "N",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "SORT_BY1" => "SORT",
                        "SORT_BY2" => "ID",
                        "SORT_ORDER1" => "ASC",
                        "SORT_ORDER2" => "DESC",
                        "STRICT_SECTION_CHECK" => "N",
                    ]
                ); ?>
                <style>

                    @media screen and (min-width: 1620px) {
                        .hero__inner.hero__inner--winterdreams.mobile-hide {
                            padding-bottom: 40%!important;
                        }
                    }
                </style>
                <a href="/promo/winterdreams/" class="hero__inner hero__inner--winterdreams mobile-hide" style="padding-bottom: 33%;"></a>
                <a href="/promo/winterdreams/" class="hero__inner mobile-show" style="padding-bottom: 82%;"></a>
                <nav class="tabs tabs--flex" data-goto-hash-change="true">
                    <div class="tabs__inner js-tabs-fixed-center">
                        <div class="tabs__scroll js-tabs-fixed-center-scroll">
                            <div class="tabs__scroll-inner">
                                <ul class="tabs__list">
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#catalog">
                                            <svg class="icon">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-book-outline"></use>
                                            </svg>
                                            <span class="link link--ib link--dotted">Каталог</span>
                                        </a></li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#services">
                                            <svg class="icon">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-gardener-outline"></use>
                                            </svg>
                                            <span class="link link--ib link--dotted">Услуги</span>
                                        </a></li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#objects">
                                            <svg class="icon">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-maple-leaf"></use>
                                            </svg>
                                            <span class="link link--ib link--dotted">Объекты</span>
                                        </a></li>
                                    <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                              href="#entertainment">
                                            <svg class="icon">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-drink-apple"></use>
                                            </svg>
                                            <span class="link link--ib link--dotted">Досуг</span>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="tabs-vert-fixed scroll-activate" data-scroll-activate-trigger="#vertical-tabs-trigger"
         data-scroll-activate-offset-element=".header" data-scroll-activate-trigger-hook="onLeave"
         data-scroll-activate-reverse="true">
        <nav class="tabs tabs--vert tabs--active-allowed" data-goto-hash-change="true">
            <div class="tabs__inner js-tabs-fixed-center">
                <div class="tabs__scroll js-tabs-fixed-center-scroll">
                    <div class="tabs__scroll-inner">
                        <ul class="tabs__list">

                            <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#catalog">
                                    <svg class="icon">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-book-outline"></use>
                                    </svg>
                                    <span class="link link--ib link--dotted">Каталог</span>
                                </a></li>
                            <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#services">
                                    <svg class="icon">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-gardener-outline"></use>
                                    </svg>
                                    <span class="link link--ib link--dotted">Услуги</span>
                                </a></li>
                            <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#objects">
                                    <svg class="icon">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-maple-leaf"></use>
                                    </svg>
                                    <span class="link link--ib link--dotted">Объекты</span>
                                </a></li>
                            <li class="tabs__item"><a class="tabs__link tabs__link--block js-goto"
                                                      href="#entertainment">
                                    <svg class="icon">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/build/svg/symbol/svg/sprite.symbol.svg#icon-drink-apple"></use>
                                    </svg>
                                    <span class="link link--ib link--dotted">Досуг</span>
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div id="vertical-tabs-trigger" class="vertical-tabs-trigger">
    </div>
    <div class="section section--block bg-pattern bg-pattern--grey-leaves section--saw-before" id="catalog"
         data-goto-offset-element=".header">
        <div class="container">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                [
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/local/inc_area/index_catalog_text.php",
                ]
            ); ?> <? $APPLICATION->IncludeComponent(
                "ig:highloadblock.list",
                "index_groups",
                [
                    "BLOCK_ID" => "1",
                    "CHECK_PERMISSIONS" => "N",
                    "DETAIL_URL" => "",
                    "FILTER_NAME" => "",
                    "PAGEN_ID" => "",
                    "ROWS_PER_PAGE" => "",
                    "SORT_FIELD" => "ID",
                    "SORT_ORDER" => "DESC",
                ]
            ); ?>
            <div class="tgbs">
                <div class="tgbs__inner">
                    <div class="tgb tgb--featured tgb--img-hover">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/inc_area/index_virt_tour.php",
                            ]
                        ); ?>
                    </div>
                    <div class="tgb tgb--featured tgb--img-hover">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/inc_area/index_catalog_garden.php",
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "index_services",
    [
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "COMPONENT_TEMPLATE" => "index_services",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "N",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => [0 => "", 1 => "",],
        "FILTER_NAME" => "arIndexServicesFilter",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "6",
        "IBLOCK_TYPE" => "services",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "100",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [0 => "BLOCK_COLOR_INDEX",],
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "PROPERTY_SORT_INDEX",
        "SORT_BY2" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "DESC",
        "STRICT_SECTION_CHECK" => "N",
    ]
); ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "index_objects",
    [
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COUNT_ELEMENTS" => "N",
        "IBLOCK_ID" => "12",
        "IBLOCK_TYPE" => "services",
        "SECTION_CODE" => "",
        "SECTION_FIELDS" => ["", ""],
        "SECTION_ID" => "",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => ["UF_LINK", ""],
        "SHOW_PARENT_NAME" => "N",
        "TOP_DEPTH" => "1",
        "VIEW_MODE" => "LINE",
    ]
); ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "index_dosug",
    [
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "COMPONENT_TEMPLATE" => "index_services",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "N",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => [0 => "", 1 => "",],
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "20",
        "IBLOCK_TYPE" => "onas",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "100",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [0 => "INDEX_TEXT",],
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "DESC",
        "STRICT_SECTION_CHECK" => "N",
    ]
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>