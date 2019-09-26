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

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\SystemException;
use ig\CHelper;
use ig\CRegistry;
use ig\Helpers\Url;
use ig\Highload\Base;
use ig\Highload\VirtualPage;
use ig\sphinx\CatalogOffers;

$cCatalogOffers = new CatalogOffers();
$request = Application::getInstance()->getContext()->getRequest();
$arParams['IS_AJAX'] = $request->isAjaxRequest();
$arParams['EXPAND_FILTER'] = (int)$_COOKIE['filter_active'] > 0 ? 'Y' : 'N';

$virtualPage = VirtualPage::getByUrl(Url::getUrlWithoutParams());
if(count($virtualPage) === 1) {
    $virtualPage = array_shift($virtualPage);
} else {
    $virtualPage = null;
}
if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

$arResult = array();


CModule::IncludeModule("iblock");

$requestArray = $request->toArray();
unset($requestArray['?filterAlias']);
// virtual page
if ($virtualPage !== null && $virtualPage['UF_PARAMS']) {
    parse_str($virtualPage['UF_PARAMS'], $requestArray);
}

// get lists
$cacheInstance = Cache::createInstance();
$cacheId = md5(serialize(array_merge($arParams, $requestArray['F']['PROPERTY_GROUP'])));


if (strpos($APPLICATION->GetCurDir(), '/katalog/rasteniya/novinki/') === 0) {
    $arResult['IS_NEW'] = true;
    $arResult['FILTER_PAGE_URL'] = '/katalog/rasteniya/novinki/';
} elseif (strpos($APPLICATION->GetCurDir(), '/katalog/rasteniya/akcii/') === 0) {
    $arResult["IS_ACTION"] = true;
    $arResult["FILTER_PAGE_URL"] = '/katalog/rasteniya/akcii/';
} else {
    $arResult["FILTER_PAGE_URL"] = '/katalog/rasteniya/';
}

if ($cacheInstance->initCache($arParams["CACHE_TIME"], $cacheId)) {
    $arResult = $cacheInstance->getVars();
} elseif ($cacheInstance->startDataCache()) {
    $arResult["OFFER_PARAMS"]["GROUP"] = array();
    $rsGroup = Base::getList(Base::getHighloadBlockIDByName("Group"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID",
        "UF_ICON"
    ), array(), true);
    while ($arGroup = $rsGroup->Fetch()) {
        if (strpos($arGroup["UF_ICON"], "#") === 0) {
            $strIcon = SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg' . $arGroup["UF_ICON"];
        } else {
            $strIcon = $arGroup["UF_ICON"];
        }

        $arResult["OFFER_PARAMS"]["GROUP"]["VALUES"][$arGroup["UF_XML_ID"]] = array(
            "ID" => $arGroup["ID"],
            "NAME" => $arGroup["UF_NAME"],
            "VALUE" => $arGroup["UF_XML_ID"],
            "SPHINX_VALUE" => CatalogOffers::convertValueToSphinx("PROPERTY_GROUP", $arGroup["UF_XML_ID"]),
            "COUNT" => 0,
            "ICON" => $strIcon,
            "DISABLED" => "N"
        );
    }
    $arResult["OFFER_PARAMS"]["COLOR"] = array();
    $rsList = Base::getList(Base::getHighloadBlockIDByName("Colors"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $sphinxVal = CatalogOffers::convertValueToSphinx("PROPERTY_COLOR_FRUIT", $arList["UF_XML_ID"]);
        $arResult["OFFER_PARAMS"]["COLOR"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "COLOR" => $arList["UF_XML_ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => $sphinxVal,
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["HEIGHT_NOW"] = array();
    $rsList = Base::getList(Base::getHighloadBlockIDByName("PeriodHeightNow"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["HEIGHT_NOW"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => CatalogOffers::convertValueToSphinx("PROPERTY_HEIGHT_NOW", $arList["UF_XML_ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["HEIGHT_10"] = array();
    $rsList = Base::getList(Base::getHighloadBlockIDByName("PeriodHeight"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["HEIGHT_10"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => CatalogOffers::convertValueToSphinx("PROPERTY_HEIGHT_10", $arList["UF_XML_ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"] = array();
    $rsList = Base::getList(Base::getHighloadBlockIDByName("PeriodPrice"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => CatalogOffers::convertValueToSphinx("CATALOG_PRICE_LIST", $arList["UF_XML_ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["AVAILABLE"] = array();
    $rsList = CIBlockPropertyEnum::GetList(Array(
        "DEF" => "DESC",
        "SORT" => "ASC"
    ), Array("IBLOCK_ID" => CHelper::getIblockIdByCode('offers'), "CODE" => "AVAILABLE"));
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["AVAILABLE"]["VALUES"][$arList["ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["VALUE"],
            "VALUE" => $arList["ID"],
            "SPHINX_VALUE" => CatalogOffers::convertValueToSphinx("PROPERTY_AVAILABLE", $arList["ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $cacheInstance->endDataCache($arResult);
}

if ($requestArray["search"] == 'Y') {
    $arParams["QUERY"] = trim($requestArray["searchQuery"]);

    if (empty($arParams["QUERY"])) {
        unset($arParams["QUERY"]);
    }

    if (strlen($arParams["QUERY"])) {
        $arQuery = explode(' ', $arParams["QUERY"]);
        $arTmp = array();
        foreach ($arQuery as $intIndex => $strQuery) {
            if (strlen($strQuery) > 2) {
                $arTmp[] = $strQuery;
            }
        }
        $arQuery = $arTmp;
        unset($arTmp);

        if (count($arQuery) > 0) {
            $arResult["SEARCH"]["QUERY"] = $arQuery;

            if (count($arQuery) == 1) {
                $arFound = $this->searchEntity($arQuery[0]);
            } else {
                $arFound = array();
                $arPrevQuery = array();
                foreach ($arQuery as $intCnt => $strQuery) {
                    $arSearch = $this->searchEntity($strQuery, array("SKIP" => $arSearch["TYPE"], "FOUND" => $arSearch));
                    if (!empty($arSearch)) {
                        $arFoundTmp = $arFound;
                        $arFound = $arSearch;

                        if ($arFound["TYPE"] == 'SORT') {

                            if ($intCnt < count($arQuery) - 1) {
                                $strQuery = implode(" ", $arPrevQuery) . implode(" ", array_slice($arQuery, $intCnt));
                                $arFound = $this->searchEntity($strQuery, array("SKIP" => "SORT",
                                    "FOUND" => $arFoundTmp
                                ));
                            }
                            break;
                        }
                    } else {
                        if ($arFound["TYPE"] == "ROD") {
                            $arSearchParams = array("SKIP" => '', "FOUND" => $arFound);
                        } elseif ($arFound["TYPE"] == 'VID') {
                            $arSearchParams = array("SKIP" => 'ROD', "FOUND" => $arFound);
                        } elseif ($arFound["TYPE"] == "SORT") {
                            $arSearchParams = array("SKIP" => 'VID', "FOUND" => $arFound);
                        }

                        $arTmp = $arPrevQuery;
                        $arTmp[] = $strQuery;

                        $arSearch = $this->searchEntity(implode(" ", $arTmp), $arSearchParams);
                        if (!empty($arSearch)) {
                            $arFound = $arSearch;
                        } else {
                            $arPrevQuery = array();
                        }
                    }

                    $arPrevQuery[] = $strQuery;
                }
            }
        }
    }

    if (!empty($arFound)) {
        $arResult["SEARCH"]["DATA"] = $this->getSearchResult($arFound);
    }


    ob_start();
    $this->includeComponentTemplate("search");
    $strSearchHtml = ob_get_clean();

} else {

    if (strlen($requestArray['filterAlias']) > 0) {
        if (!$GLOBALS['USER']->IsAdmin()) {
            foreach ($requestArray as $requestKey => $requestValue) {
                if ($requestKey !== 'filterAlias' && $requestKey !== 'page' && $requestKey !== 'PAGEN_1') {
                    Tools::process404('', false);
                    return;
                }
            }
        }

        $requestArray['F'] = $this->getFilterByAlias($requestArray['filterAlias']);
        if (!empty($requestArray['F'])) {
            $requestArray['frmCatalogFilterSent'] = 'Y';
        }
    }

    $arPropertyTreeParams = array();
    if (($requestArray['frmCatalogFilterSent'] === 'Y') && strlen($requestArray['F']['PROPERTY_GROUP']) > 0) {
        $arResult['GROUP_ID'] = $arResult['OFFER_PARAMS']['GROUP']['VALUES'][$requestArray['F']['PROPERTY_GROUP']]['ID'];
        $arPropertyTreeParams['ENABLE_TYPE'] = $arResult['GROUP_ID'];
    }

    // зависимые от группы свойства
    $arResult['PROPERTY_TREE'] = CHelper::getPropertyTree($arPropertyTreeParams);
    foreach ($arResult['PROPERTY_TREE'] as $strParamCode => $arParamData) {
        if (!isset($arResult['OFFER_PARAMS'][$strParamCode])) {
            $arResult['OFFER_PARAMS'][$strParamCode] = array(
                'NAME' => $arParamData['NAME']
            );
        }

        $arResult['OFFER_PARAMS'][$strParamCode]['DISABLED'] = 'N';

        foreach ($arParamData['VALUES'] as $arValue) {
            if ($arValue['DISABLED'] != 'Y') {
                $bPropertyDisabled = false;
            }

            $arResult['OFFER_PARAMS'][$strParamCode]['VALUES'][] = array(
                'NAME' => $arValue['NAME'],
                'ICON' => $arValue['ICON'],
                'VALUE' => $arValue['XML_ID'],
                'SPHINX_VALUE' => CatalogOffers::convertValueToSphinx('PROPERTY_' . $strParamCode, $arValue['XML_ID']),
                'COUNT' => 0,
                'DISABLED' => $arValue['DISABLED']
            );
        }
    }

// глобальное выключение свойств для группы
    if ($arResult["GROUP_ID"] > 0) {
        $rsPropGroup = Base::getList(Base::getHighloadBlockIDByName("PropertyGroup"), array("UF_ACTIVE" => 1), array(
            "UF_NAME",
            "ID",
            "UF_CODE",
            "UF_POINTER"
        ), array(), true);
        while ($arPropGroup = $rsPropGroup->Fetch()) {
            if (!in_array($arResult["GROUP_ID"], $arPropGroup["UF_POINTER"])) {
                $arResult["OFFER_PARAMS"][$arPropGroup["UF_CODE"]]["DISABLED"] = 'Y';
            }
        }
    }

    $arSearchParams = array();
    if ($requestArray["frmCatalogFilterSent"] == 'Y') {
        $intSelectedGroupID = false;
        foreach ($requestArray["F"] as $strCode => $obValue) {
            $strCode = trim($strCode);

            if (is_array($obValue)) {
                if ($strCode == 'PROPERTY_FLOWERING' || $strCode == 'PROPERTY_RIPENING') {
                    if (isset($obValue["FROM"]) && $obValue["FROM"] == 0)
                        unset($obValue["FROM"]);
                    if (isset($obValue["TO"]) && $obValue["TO"] == 11)
                        unset($obValue["TO"]);
                }

                if (!empty($obValue)) {
                    CatalogFilter::$arRequestFilter[$strCode] = $obValue;
                }
            } else if (!empty(trim($obValue))) {
                CatalogFilter::$arRequestFilter[$strCode] = trim($obValue);
            }

            if (!empty(CatalogFilter::$arRequestFilter[$strCode])) {
                $arSearchParams[$strCode] = CatalogOffers::convertValueToSphinx($strCode, CatalogFilter::$arRequestFilter[$strCode]);
            }
        }

        if (empty($requestArray["filterAlias"])) {
            $arResult["FILTER_ALIAS"] = $this->processUseFilter($requestArray["F"]);
        }
    }

    $arFacetExcludeParams = array_keys($arSearchParams);
    $arFacetExcludeParams[] = 'PROPERTY_GROUP';
    $arFacetExcludeParams[] = 'PROPERTY_USAGE';

    $arFacetExcludeParams = array_unique($arFacetExcludeParams);


    $obSearch = new CatalogOffers();

    $arResult["COUNT_TOTAL_OFFERS"] = $obSearch->getCount(array(), 'id');
    $arResult["COUNT_DATA"] = array();
    $arSearchGroupParams = array();
    $arSearchUsageParams = array();


    if (!empty($arSearchParams["PROPERTY_GROUP"])) {
        $arSearchGroupParams["PROPERTY_GROUP"] = $arSearchParams["PROPERTY_GROUP"];
        $arSearchUsageParams["PROPERTY_GROUP"] = $arSearchParams["PROPERTY_GROUP"];
    }

    if (!empty($arSearchParams["PROPERTY_USAGE"])) {
        $arSearchUsageParams["PROPERTY_USAGE"] = $arSearchParams["PROPERTY_USAGE"];
    }

    if ($arResult["IS_ACTION"]) {
        $arSearchParams[">CATALOG_PRICE_" . CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;

        $arSearchGroupParams[">CATALOG_PRICE_" . CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;
        $arSearchUsageParams[">CATALOG_PRICE_" . CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;
    } elseif ($arResult["IS_NEW"]) {
        $intNewEnumID = CHelper::getEnumID(CHelper::getIblockIdByCode('offers'), "NEW", "Да");
        $arSearchParams["PROPERTY_NEW"] = $intNewEnumID;

        $arSearchGroupParams["PROPERTY_NEW"] = $intNewEnumID;
        $arSearchUsageParams["PROPERTY_NEW"] = $intNewEnumID;
    }

    // GROUP
    $arFacetSearchParams = array("FILTER" => $arSearchGroupParams);
    $arFacetFields = CatalogOffers::getFacetFields(array("INCLUDE" => "PROPERTY_GROUP"));


    $arFacetData = $obSearch->searchFacet(
        $arFacetSearchParams,
        $arFacetFields,
        true
    );

    $arResult["COUNT_DATA"]["GROUP"]["TOTAL"] = $obSearch->getCount(array(), 'id');
    $arResult["COUNT_GROUP_PRODUCTS"] = $obSearch->getCount($arSearchGroupParams, 'id');


    foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
        foreach ($arDataList as $arData) {
            $arResult["COUNT_DATA"]["GROUP"][$arData[$strSphinxCode]] = $arData["count"];
        }
    }

    // USAGE
    $arFacetData = $obSearch->searchFacet(array("FILTER" => $arSearchUsageParams), CatalogOffers::getFacetFields(array("INCLUDE" => "PROPERTY_USAGE")), true);

    $arResult["COUNT_DATA"]["USAGE"]["TOTAL"] = $obSearch->getCount($arSearchUsageParams, 'id');
    $arResult["COUNT_USAGE_PRODUCTS"] = $obSearch->getCount($arSearchUsageParams, 'id');

    foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
        foreach ($arDataList as $arData) {
            $arResult["COUNT_DATA"]["USAGE"][(string)$arData[$strSphinxCode]] = $arData["count"];
        }
    }

    $arResult["COUNT_PRODUCTS"] = $obSearch->getCount($arSearchParams, 'cat_id');
    $arResult["COUNT_OFFERS"] = $obSearch->getCount($arSearchParams, 'id');

    if (!$arResult["IS_ACTION"] && !$arResult["IS_NEW"]) {
        // фасетим по нефильтрованным параметрам
        $arFacetData = $obSearch->searchFacet(array("FILTER" => $arSearchParams), CatalogOffers::getFacetFields(array("EXCLUDE" => $arFacetExcludeParams)), true);
        foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {

            $strCode = str_replace("PROPERTY_", '', CatalogOffers::convertFieldCode($strSphinxCode, false));

            $arTotalFilter = $arSearchParams;
            unset($arTotalFilter[CatalogOffers::convertFieldCode($strSphinxCode, false)]);

            $arResult["COUNT_DATA"][$strCode]["TOTAL"] = $obSearch->getCount($arTotalFilter, 'id');
            foreach ($arDataList as $arData) {
                $arResult["COUNT_DATA"][$strCode][$arData[$strSphinxCode]] = $arData["count"];
            }
        }

        // фасетим по фильтрованным параметрам
        foreach ($arSearchParams as $strCode => $value) {
            $arTmpFilter = $arSearchParams;

            $arParamData = CatalogOffers::getSphinxConfig(CatalogOffers::convertFieldCode($strCode));

            if ($arParamData["CONTROL"] == 'CHECKBOX' && $arParamData["MULTIPLE"] == 'Y') {
                // сначала берем фасет по неотмеченным вариантам
                $arTmpFilter["NOT_" . $strCode] = $arTmpFilter[$strCode];
                unset($arTmpFilter[$strCode]);

                $arFacetData = $obSearch->searchFacet(array("FILTER" => $arTmpFilter), CatalogOffers::getFacetFields(array("INCLUDE" => $strCode)), true);

                foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
                    $strCodeTmp = str_replace("PROPERTY_", '', CatalogOffers::convertFieldCode($strSphinxCode, false));
                    foreach ($arDataList as $arData) {
                        $arResult["COUNT_DATA"][$strCodeTmp][$arData[$strSphinxCode]] = $arData["count"];
                    }
                }

                // по отмеченным фасет взять нельзя, т.к. неизвестны пересечения с другими вариантами, тут берем простым count
                foreach ($arSearchParams[$strCode] as $key => $selectedValue) {
                    $arTmpFilter = $arSearchParams;

                    unset($arTmpFilter[$strCode][$key]);
                    if (empty($arTmpFilter[$strCode])) {
                        unset($arTmpFilter[$strCode]);
                    }


                    $arResult["COUNT_DATA"][str_replace("PROPERTY_", '', $strCode)][$selectedValue] = $obSearch->getCount($arTmpFilter, 'id');
                }

            } else {
                unset($arTmpFilter[$strCode]);

                $arFacetData = $obSearch->searchFacet(array("FILTER" => $arTmpFilter), CatalogOffers::getFacetFields(array("INCLUDE" => $strCode)), true);

                foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
                    $strCode = str_replace("PROPERTY_", '', CatalogOffers::convertFieldCode($strSphinxCode, false));
                    $arResult["COUNT_DATA"][$strCode]["TOTAL"] = $obSearch->getCount($arTmpFilter, 'id');
                    foreach ($arDataList as $arData) {
                        $arResult["COUNT_DATA"][$strCode][$arData[$strSphinxCode]] = $arData["count"];
                    }
                }
            }
        }
    }
    $arFilterTmp = $arSearchParams;
    unset($arFilterTmp['PROPERTY_GROUP'], $arFilterTmp['PROPERTY_USAGE']);

    $arResult["FILTER_EMPTY"] = (empty($arFilterTmp) ? 'Y' : 'N');


    /**
     * Set result link
     */
    try {
        $url = Url::getUrlWithoutParams(false);
        $host = Url::getHost();
        $link = $host . $url;
        $isResultFilterAlias = false;
        if (isset($arResult['FILTER_ALIAS']) && (string)$arResult['FILTER_ALIAS'] !== '') {
            $link .= '?filterAlias=' . $arResult['FILTER_ALIAS'];
            $isResultFilterAlias = true;
        }

        if ($isResultFilterAlias === false && array_key_exists('filterAlias', $requestArray)) {
            $link .= '?filterAlias=' . $requestArray['filterAlias'];
        }

        $arResult['RESULT_LINK'] = $link;
    } catch (SystemException $e) {
        /**
         * ToDo:: 500.
         */
    }

    CRegistry::add('catalogFilter', $arSearchParams);

    ob_start();
    $this->includeComponentTemplate();
    $strFilterHtml = ob_get_clean();
}

if ($arParams['IS_AJAX'] === true) {
    $arResponse = [];
    try {
        if (strlen($strFilterHtml) > 0) {
            CRegistry::add('catalog-filter_html', $strFilterHtml);
        }

        if (strlen($arResult['RESULT_LINK']) > 0) {
            CRegistry::add('catalog-page_url', $arResult['RESULT_LINK']);
        }

        if (strlen($strSearchHtml) > 0) {
            CRegistry::add('catalog-html', $strSearchHtml);
        }
    } catch (Exception $e) {
        /**
         * ToDo:: 500
         */
    }
} else {
    echo $strFilterHtml;
}

