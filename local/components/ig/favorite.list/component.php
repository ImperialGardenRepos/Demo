<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

global $INTRANET_TOOLBAR;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$request = Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest());

$arParams["SET_LAST_MODIFIED"] = $arParams["SET_LAST_MODIFIED"] === "Y";
$arParams["CATALOG_PRICE_ID"] = \ig\CRegistry::get("CATALOG_BASE_PRICE_ID");
$arParams["CATALOG_OLD_PRICE_ID"] = \ig\CRegistry::get("CATALOG_OLD_PRICE_ID");


$arResult["FAVORITE"] = \ig\CFavorite::getInstance()->getFavorite();


if (!Loader::includeModule("iblock")) {
    $this->abortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if (!empty($arResult["FAVORITE"])) {
    $this->prepareGroupData();

    // get offers
    $arSortID = [];
    $arOffersSelect = [
        "*", "PROPERTY_*", "CATALOG_GROUP_" . $arParams["CATALOG_PRICE_ID"], "CATALOG_GROUP_" . $arParams["CATALOG_OLD_PRICE_ID"],
    ];
    $arTmpItems = [];
    $rsO = CIBlockElement::GetList(["CATALOG_PRICE_" . $arParams["CATALOG_PRICE_ID"] => "ASC"], [
        "ACTIVE" => "Y",
        "IBLOCK_ID" => [\ig\CHelper::getIblockIdByCode('offers'), \ig\CHelper::getIblockIdByCode('offers-garden')],
        "ID" => $arResult["FAVORITE"],
    ], false, false, $arOffersSelect);
    while ($obO = $rsO->GetNextElement()) {
        $arO = $obO->GetFields();
        $arO["PROPERTIES"] = $obO->GetProperties();

        if (empty($arO["PROPERTIES"]["AVAILABLE"]["VALUE"])) {
            $arO["PROPERTIES"]["AVAILABLE"]["VALUE"] = 'Под заказ';
        }

        \ig\CHelper::prepareItemPrices($arO);

        if ($arO["PROPERTIES"]["CML2_LINK"]["VALUE"] > 0) {
            $arSortID[] = $arO["PROPERTIES"]["CML2_LINK"]["VALUE"];
        }

        $arTmpItems[$arO["ID"]] = $arO;
    }

    foreach ($arResult["FAVORITE"] as $intID) {
        if (isset($arTmpItems[$intID])) {
            $arResult["ITEMS"][] = $arTmpItems[$intID];
        }
    }
    unset($arTmpItems);

    if (!empty($arSortID)) {
        $rsI = CIBlockElement::GetList(["NAME" => "ASC", "ID" => "DESC"], [
            "ACTIVE" => "Y",
            "ID" => $arSortID,
            "IBLOCK_ID" => [\ig\CHelper::getIblockIdByCode('catalog'), \ig\CHelper::getIblockIdByCode('catalog-garden')],
        ], false, $arNavParams, [
            "*", "PROPERTIES_*",
        ]);
        while ($obI = $rsI->GetNextElement()) {
            $arI = $obI->GetFields();
            $arI["PROPERTIES"] = $obI->GetProperties();

            $arI["DETAIL_PAGE_URL"] = \ig\CHelper::prepareCatalogDetailUrl($arI);

            $arResult["SORT"][$arI["ID"]] = $arI;

            $arResult["SECTIONS"][$arI["IBLOCK_SECTION_ID"]] = [];
        }
    }
}

if (!empty($arResult["SECTIONS"])) {
    $rsSec = CIBlockSection::GetList([], ["ACTIVE" => "Y", "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog'), "ID" => array_keys($arResult["SECTIONS"])], false, ["NAME", "SECTION_PAGE_URL"]);
    while ($arSec = $rsSec->GetNext()) {
        $rsNav = CIBlockSection::GetNavChain(\ig\CHelper::getIblockIdByCode('catalog'), $arSec["ID"]);
        while ($arNav = $rsNav->GetNext()) {
            $arSec["NAV"][] = $arNav;
        }

        $arResult["SECTIONS"][$arSec["ID"]] = $arSec;
    }
}

$this->includeComponentTemplate();