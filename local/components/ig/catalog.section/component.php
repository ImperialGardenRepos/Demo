<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


global $INTRANET_TOOLBAR;

use Bitrix\Iblock;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use ig\CHelper;
use ig\CRegistry;
use ig\sphinx\CatalogOffers;

CPageOption::SetOptionString('main', 'nav_page_in_session', "N");

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 36000000;
}

$request = Application::getInstance()->getContext()->getRequest();

/**
 * Adjust arParams array.
 */
$arParams['IS_AJAX'] = $request->isAjaxRequest();

$arParams['SET_LAST_MODIFIED'] = $arParams['SET_LAST_MODIFIED'] === 'Y';
$arParams['CATALOG_PRICE_ID'] = CRegistry::get('CATALOG_BASE_PRICE_ID');
$arParams['CATALOG_OLD_PRICE_ID'] = CRegistry::get('CATALOG_OLD_PRICE_ID');
$arParams['SORT_BY1'] = trim($arParams['SORT_BY1']);
$arParams['DETAIL_URL'] = trim($arParams['DETAIL_URL']);

$arParams['CACHE_FILTER'] = $arParams['CACHE_FILTER'] === 'Y';
if (!$arParams['CACHE_FILTER'] && count($arrFilter) > 0) {
    $arParams['CACHE_TIME'] = 0;
}

$arParams['NEWS_COUNT'] = (int)$arParams['NEWS_COUNT'];
if ($arParams['NEWS_COUNT'] <= 0) {
    $arParams['NEWS_COUNT'] = 10;
}

if ($_REQUEST['sortID'] > 0) {
    $intSortID = (int)$_REQUEST['sortID'];
    if ($intSortID > 0) {
        $arParams['SORT_ID'] = $intSortID;
    }
} elseif (
    (is_array($_REQUEST['F']['sortsID']) && !empty($_REQUEST['F']['sortsID']))
    || (is_array($_REQUEST['F']['vidsID']) && !empty($_REQUEST['F']['vidsID']))
) {
    foreach ($_REQUEST['F']['sortsID'] as $intSortID) {
        $intSortID = (int)$intSortID;
        if ($intSortID > 0) {
            $arParams['SORTS_ID'][] = $intSortID;
        }
    }

    foreach ($_REQUEST['F']['vidsID'] as $intVidID) {
        $intVidID = (int)$intVidID;
        if ($intVidID > 0) {
            $arParams['VIDS_ID'][] = $intVidID;
        }
    }
}

if (empty($arParams["SORT_ID"])) {
    if ($_REQUEST["page"] > 0) {
        $arParams["PAGE_NUM"] = intval($_REQUEST["page"]);
        if ($arParams["PAGE_NUM"] <= 0)
            $arParams["PAGE_NUM"] = 1;
    }

    if ($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"]) {
        $arNavParams = array(
            "nPageSize" => $arParams["NEWS_COUNT"],
            "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"] === 'Y',
            "bShowAll" => $arParams["PAGER_SHOW_ALL"],
        );

        if ($arParams["PAGE_NUM"] > 0) {
            $arNavParams["iNumPage"] = $arParams["PAGE_NUM"];
        }

        $arNavigation = CDBResult::GetNavParams($arNavParams);
        if ($arNavigation["PAGEN"] == 0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] > 0) {
            $arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
        }
    } else {
        $arNavParams = array(
            "nTopCount" => $arParams["NEWS_COUNT"],
            "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
        );
        $arNavigation = false;
    }

    if (empty($arParams["PAGER_PARAMS_NAME"]) || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PAGER_PARAMS_NAME"])) {
        $pagerParameters = array();
    } else {
        $pagerParameters = $GLOBALS[$arParams["PAGER_PARAMS_NAME"]];
        if (!is_array($pagerParameters)) {
            $pagerParameters = array();
        }
    }
}

$arVirtualPage = CRegistry::get("VIRT_PAGE");
if (!empty($arVirtualPage)) {
    $arParams["VIRT_PAGE"] = $arVirtualPage;
}

$arParams["IS_NEW"] = strpos($APPLICATION->GetCurDir(), '/katalog/rasteniya/novinki/') === 0;
$arParams["IS_ACTION"] = strpos($APPLICATION->GetCurDir(), '/katalog/rasteniya/akcii/') === 0;

$arSearchParams = CRegistry::get('catalogFilter');
if (!is_array($arSearchParams)) $arSearchParams = array();

ob_start();

if ($this->startResultCache(false, array(($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()), $bUSER_HAVE_ACCESS, $arNavigation, $arSearchParams, $pagerParameters))) {
    if (!Loader::includeModule("iblock")) {
        $this->abortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }
    $arResult = array(
        "CURRENT_PAGE" => (empty($arNavigation["PAGEN"]) ? 1 : $arNavigation["PAGEN"])
    );

    global $USER_FIELD_MANAGER;

    $this->prepareGroupData();
    $this->prepareCartData();
    CHelper::preparePriceData(CHelper::getIblockIdByCode('offers'), $arResult);

    if ($arParams["VARIABLES"]["SECTION_ID"] > 0) {
        $rsSec = CIBlockSection::GetList(
            Array(),
            array(
                "ACTIVE" => "Y",
                "ID" => $arParams["VARIABLES"]["SECTION_ID"],
                "IBLOCK_ID" => CHelper::getIblockIdByCode("catalog")
            ), false, array("*", "UF_*"));
        $arSec = $rsSec->GetNext();

        if (empty($arSec["ID"])) {
            $this->abortResultCache();
            Iblock\Component\Tools::process404(
                trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_NEWS_NA")
                , true
                , true
                , true
            );
            return;
        } else {
            $arResult["SECTION"] = $arSec;
            $arResult["SECTION"]["PATH"] = array();
            $rsPath = CIBlockSection::GetNavChain($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["IBLOCK_SECTION_ID"]);

            $rsPath->SetUrlTemplates("", $arParams["SECTION_URL"], $arParams["IBLOCK_URL"]);
            while ($arPath = $rsPath->GetNext()) {

                $arUF = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_" . $arPath["IBLOCK_ID"] . "_SECTION", $arPath['ID']);
                foreach ($arUF as $strCode => $arValue) {
                    $arPath[$strCode] = $arValue["VALUE"];
                }

                $arResult["SECTION"]["PATH"][] = $arPath;
            }

            $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSec["IBLOCK_ID"], $arSec["ID"]);
            $arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

            $arResult["NAME"] = $arSec["NAME"];
            $arResult["DESCRIPTION"] = $arSec["DESCRIPTION"];
        }

        if ($arSec["DEPTH_LEVEL"] == 1) {
            $arSearchParams["CAT_SECTION_1"] = $arParams["VARIABLES"]["SECTION_ID"];
        } else {
            $arSearchParams["CAT_SECTION_2"] = $arParams["VARIABLES"]["SECTION_ID"];
        }
    }

    // virt page data
    if ($arParams["VIRT_PAGE"]["UF_H1"]) {
        $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] = $arParams["VIRT_PAGE"]["UF_H1"];
    }

    if ($arParams["VIRT_PAGE"]["UF_TEXT"]) {
        $arResult["DESCRIPTION"] = $arParams["VIRT_PAGE"]["UF_TEXT"];
    } else {
        $arResult["DESCRIPTION"] = $arSec["DESCRIPTION"];
    }

    if (empty($arResult["NAME"])) {
        $arResult["NAME"] = $APPLICATION->GetTitle('page_title');
    }

    /**
     * If there's a global filter - use it to find goods.
     * If no filter - find goods via Sphinx
     */
    $globalFilter = $arParams['FILTER_NAME'];
    global $$globalFilter;
    if (is_array($$globalFilter) && array_key_exists('ID', $$globalFilter) && $arParams['USE_FILTER'] === 'Y' && count(($$globalFilter)['ID']) > 0) {
        $filter = $$globalFilter;
        if (!array_key_exists('ACTIVE', $filter)) {
            $filter['ACTIVE'] = 'Y';
        }
        $arFilterExt = $filter;
        $arOffersFilterExt = [];
        $skusByProduct = CCatalogSku::getOffersList($filter['ID']);
        foreach ($skusByProduct as $skuArray) {
            foreach ($skuArray as $key => $dummy) {
                $arOffersFilterExt[] = $key;
            }
        }

    } else {
        $arFilterExt = [];
        $arOffersFilterExt = [];

        $strCurrentDir = $APPLICATION->GetcurDir();
        if ($arParams['IS_NEW']) {
            $arSearchParams['PROPERTY_NEW'] = CHelper::getEnumID(CHelper::getIblockIdByCode('offers'), 'NEW', 'Да');
        } elseif ($arParams['IS_ACTION']) {
            $arSearchParams['>CATALOG_PRICE_' . CRegistry::get('CATALOG_ACTION_PRICE_ID')] = 0;
        }

        $arOffersSearchParams = array(
            'FILTER' => $arSearchParams,
            'LIMIT' => 100000
        );
        unset($arOffersSearchParams['FILTER']['vidsID'], $arOffersSearchParams['FILTER']['sortsID']);

        $obSearch = new CatalogOffers();
        $rsItems = $obSearch->search($arOffersSearchParams);
        while ($arItem = $obSearch->fetch($rsItems)) {
            $arFilterExt['ID'][] = $arItem['cat_id'];
            $arOffersFilterExt['ID'][] = $arItem['id'];
        }
    }


    if (empty($arFilterExt["ID"])) $arFilterExt["ID"] = array(-1);

    if ($arParams["SORT_ID"] > 0) {
        $arFilterExt["ID"] = $arParams["SORT_ID"];
    } elseif (!empty($arParams["SORTS_ID"]) || !empty($arParams["VIDS_ID"])) {
        if (!empty($arParams["SORTS_ID"]) && !empty($arParams["VIDS_ID"])) {
            $arFilterExt[] = array(
                "LOGIC" => "OR",
                array("IBLOCK_SECTION_ID" => $arParams["VIDS_ID"]),
                array("ID" => $arParams["SORTS_ID"])
            );
        } elseif (!empty($arParams["VIDS_ID"])) {
            $arFilterExt["IBLOCK_SECTION_ID"] = $arParams["VIDS_ID"];
        } elseif (!empty($arParams["SORTS_ID"])) {
            $arFilterExt["ID"] = $arParams["SORTS_ID"];
        }
    }

    $arOffersSelect = array(
        "*", "PROPERTY_*", "PROPERTY_ACTION", "PROPERTY_AVAILABLE", "PROPERTY_CML2_LINK"
    );

    CHelper::addSelectFields($arOffersSelect);

    $catalogIBlockId = $arParams['IBLOCK_ID'] ?? CHelper::getIblockIdByCode('catalog');
    $arSortFilter = array_merge(
        $arFilterExt,
        [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $catalogIBlockId
        ]
    );

    /**
     * Checking whether sort parameters were given.
     * If yes - use them, else use default (name and price).
     */
    $sortArray = [];
    if ($arParams['SORT_BY1'] !== '') {
        $sortArray[$arParams['SORT_BY1']] = array_key_exists('SORT_ORDER', $arParams) ? $arParams['SORT_ORDER'] : 'ASC';
    }
    if ($sortArray === []) {
        $sortArray = [
            'CATALOG_PRICE_' . CRegistry::get('CATALOG_BASE_PRICE_ID') => 'ASC',
            'NAME' => 'ASC'
        ];
    }

    $rsI = CIBlockElement::GetList(
        $sortArray,
        $arSortFilter,
        false,
        $arNavParams,
        [
            '*',
            'PROPERTIES_*'
        ]
    );
    while ($obI = $rsI->GetNextElement()) {
        $arI = $obI->GetFields();
        $arI["PROPERTIES"] = $obI->GetProperties();


        $arResult["SECTIONS"][$arI["IBLOCK_SECTION_ID"]] = array();

        $arI["OFFER"] = array();

        $arResult["ITEMS"][$arI["ID"]] = $arI;
    }
    if (!empty($arResult['ITEMS'])) {
        // get offers
        $rsO = CIBlockElement::GetList(
            [
                'CATALOG_PRICE_' . CRegistry::get('CATALOG_BASE_PRICE_ID') => 'ASC'
            ],
            array_merge(
                $arSearchParams,
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => CHelper::getSkuIBlockId($catalogIBlockId),
                    'PROPERTY_CML2_LINK' => array_keys($arResult['ITEMS'])
                ],
                $arOffersFilterExt
            ),
            false,
            false,
            $arOffersSelect
        );

        $intCnt = 0;
        while ($obO = $rsO->GetNextElement()) {
            $arO = $obO->GetFields();

            //$intCnt = count($arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFER"]);

            if ($arParams["SORT_ID"] > 0) {
                if ($intCnt > 0) { // по ajax подгружаем 2+
                    $arO["PROPERTIES"] = $obO->GetProperties();
                    CHelper::prepareItemPrices($arO);

                    $arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFER"][] = $arO;
                }
            } else {
                if (!empty($arO["PROPERTY_ACTION_VALUE"])) {
                    $arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFERS_ACTION_CNT"]++;
                }

                if (CHelper::isAvailable($arO["PROPERTY_AVAILABLE_VALUE"])) {
                    $arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFERS_AVAILABLE_CNT"];
                }

                if (empty($arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFER"])) {
                    $arO["PROPERTIES"] = $obO->GetProperties();
                    CHelper::prepareItemPrices($arO);

                    $arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFER"] = $arO;
                }
            }

            $arResult["ITEMS"][$arO["PROPERTY_CML2_LINK_VALUE"]]["OFFERS_CNT"]++;

            $intCnt++;
        }
    }

    $arResult["NAV_STRING"] = $rsI->GetPageNavStringEx(
        $navComponentObject,
        $arParams["PAGER_TITLE"],
        $arParams["PAGER_TEMPLATE"],
        $arParams["PAGER_SHOW_ALWAYS"],
        $this
    );

    $arResult["IBLOCK_SECTIONS"] = array();
    if (!empty($arResult["SECTIONS"])) {
        $rsSec = CIBlockSection::GetList(Array(), array("ACTIVE" => "Y", "IBLOCK_ID" => CHelper::getIblockIdByCode('catalog'), "ID" => array_keys($arResult["SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL"));
        while ($arSec = $rsSec->GetNext()) {
            $rsNav = CIBlockSection::GetNavChain(CHelper::getIblockIdByCode('catalog'), $arSec["ID"], array("NAME", "SECTION_PAGE_URL", "ID"));
            while ($arNav = $rsNav->GetNext()) {
                $arSec["NAV"][] = $arNav;
                $arResult["IBLOCK_SECTIONS"][$arNav["ID"]] = false;
            }

            $arResult["SECTIONS"][$arSec["ID"]] = $arSec;
        }
    }

    if (!empty($arResult["IBLOCK_SECTIONS"])) {
        $rsSecTmp = \CIBlockSection::GetList(Array(), array("IBLOCK_ID" => CHelper::getIblockIdByCode('catalog'), "ID" => array_keys($arResult["IBLOCK_SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL", "UF_NAME_LAT"));
        while ($arSecTmp = $rsSecTmp->GetNext()) {
            $arResult["IBLOCK_SECTIONS"][$arSecTmp["ID"]] = $arSecTmp;
        }
    }

    $arResult["NAV_CACHED_DATA"] = null;
    $arResult["NAV_RESULT"] = $rsI;
    $arResult["NAV_PARAM"] = $navComponentParameters;

    // seo
    if ($arVirtualPage) {
        $arResult["META_TAGS"]["H1"] = $arVirtualPage["UF_H1"];
    } else {
        $arResult["META_TAGS"]["H1"] = $APPLICATION->GetTitle();
        $arResult["META_TAGS"]["BROWSER_TITLE"] = $APPLICATION->GetTitle();
    }

    if (empty($arParams["SORT_ID"])) {
        $this->includeComponentTemplate();
    } else {
        $arHtmlResult = array("html" => array());
        foreach ($arResult["ITEMS"] as $arSort) {
            foreach ($arSort["OFFER"] as $arOffer) {
                $arHtmlResult["html"]["images"] .= $this->getImagesBlockHtml($arSort, $arOffer);
                $arHtmlResult["html"]["params"] .= $this->getParamsBlockHtml($arSort, $arOffer);
                $arHtmlResult["html"]["actions"] .= $this->getActionsBlockHtml($arSort, $arOffer);
            }
        }

        echo json_encode($arHtmlResult);
    }
}

$strContents = ob_get_contents();
ob_end_clean();

if (strpos($strContents, '<!--ARTICLES_INCUT-->') !== false) {
    // aticles
    ob_start();

    $APPLICATION->IncludeComponent("bitrix:news.list", "catalog_incut", Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("", ""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "4",
        "IBLOCK_TYPE" => "articles",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "4",
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
        "PROPERTY_CODE" => array("", ""),
        "SET_BROWSER_TITLE" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "Y",
        "SET_META_KEYWORDS" => "Y",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "ID",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N"
    ));

    $strArticlesHtml = ob_get_contents();
    ob_end_clean();


    $strContents = str_replace('<!--ARTICLES_INCUT-->', $strArticlesHtml, $strContents);
}

if ($arParams["IS_NEW"]) {
    $APPLICATION->AddChainItem('Новинки');
} elseif ($arParams["IS_ACTION"]) {
    $APPLICATION->AddChainItem('Акции');
}

if (is_array($arResult["SECTION"])) {
    foreach ($arResult["SECTION"]["PATH"] as $arPath) {
        if ($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "") {
            $APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
        } else {
            $APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
        }
    }
}

$strPageTitle = (empty($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) ? $arResult["NAME"] : $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]);
if (!empty($arResult["SECTION"])) {
    $APPLICATION->AddChainItem($strPageTitle);
}

if ($arParams["IS_AJAX"]) {
    if ($arParams["PAGE_NUM"] > 0 || $arParams["SORT_ID"] > 0) {
        $APPLICATION->RestartBuffer();
        echo $strContents;
        require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
    } else {
        $strNavChain = $APPLICATION->GetNavChain(false, 2, SITE_TEMPLATE_PATH . '/components/bitrix/breadcrumb/.default/template.php', true, false);

        $strContents = $strNavChain . $strContents;

        CRegistry::add('catalog-results_html', $strContents);

        $arResponse = array();

        if (empty($arParams["SORTS_ID"]) && empty($arParams["VIDS_ID"])) {
            $strFilterHtml = CRegistry::get('catalog-filter_html');
            if (!empty($strFilterHtml)) {
                $arResponse["filter_html"] = $strFilterHtml;
            }
            $strResultLink = CRegistry::get('catalog-page_url');
            if (!empty($strResultLink)) {
                $arResponse["page_url"] = $strResultLink;
            }
        }

        $strSearchHtml = CRegistry::get('catalog-html');
        if (!empty($strSearchHtml)) {
            $arResponse["html"] = $strSearchHtml;
        }

        $strResultsHtml = CRegistry::get('catalog-results_html');
        if (!empty($strResultsHtml)) {
            $arResponse["results_html"] = $strResultsHtml;
        }

        $APPLICATION->RestartBuffer();
        echo json_encode($arResponse);

        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
    }

    die();
} else {
    echo $strContents;

    $arTitleOptions = null;

    $this->setTemplateCachedData($arResult["NAV_CACHED_DATA"]);

    if (empty($arVirtualPage)) {
        if (!empty($arResult["META_TAGS"])) {
            if ($arResult["META_TAGS"]["BROWSER_TITLE"]) {
                $APPLICATION->SetTitle($arResult["META_TAGS"]["BROWSER_TITLE"], $arTitleOptions);

                $APPLICATION->SetPageProperty("title", $arResult["META_TAGS"]["BROWSER_TITLE"], $arTitleOptions);
            }

            if ($arResult["META_TAGS"]["KEYWORDS"]) {
                $APPLICATION->SetPageProperty("keywords", $arResult["META_TAGS"]["KEYWORDS"], $arTitleOptions);
            }

            if ($arResult["META_TAGS"]["DESCRIPTION"]) {
                $APPLICATION->SetPageProperty("description", $arResult["META_TAGS"]["DESCRIPTION"], $arTitleOptions);
            }
        }
    }
}