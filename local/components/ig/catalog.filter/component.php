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

use Bitrix\Main\Application,
    Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Main\Data\Cache;

$cCatalogOffers = new \ig\sphinx\CCatalogOffers();
$request = Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest()); // && $_REQUEST["frmCatalogFilterSent"] == 'Y'?'Y':'N'
$arParams["EXPAND_FILTER"] = intval($_COOKIE["filter_active"]) > 0 ? 'Y' : 'N';

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 3600;

$arResult = array();

CModule::IncludeModule("iblock");

$arRequest = $_REQUEST;

// virtual page
$arVirtualPage = \ig\CRegistry::get("VIRT_PAGE");
if (!empty($arVirtualPage)) {
    if (!empty($arVirtualPage["UF_PARAMS"])) {
        parse_str($arVirtualPage["UF_PARAMS"], $arRequest);
    }
}

// get lists
$obCache = Cache::createInstance();
$strCacheID = md5(serialize($arParams, $arRequest["F"]["PROPERTY_GROUP"]));

if (strpos($APPLICATION->GetCurDir(), '/katalog/rasteniya/novinki/') === 0) {
    $arResult["IS_NEW"] = true;
    $arResult["FILTER_PAGE_URL"] = '/katalog/rasteniya/novinki/';
} elseif (strpos($APPLICATION->GetCurDir(), '/katalog/rasteniya/akcii/') === 0) {
    $arResult["IS_ACTION"] = true;
    $arResult["FILTER_PAGE_URL"] = '/katalog/rasteniya/akcii/';
} else {
    $arResult["FILTER_PAGE_URL"] = '/katalog/rasteniya/';
}

if ($obCache->initCache($arParams["CACHE_TIME"], $strCacheID)) {
    $arResult = $obCache->getVars();
} elseif ($obCache->startDataCache()) {
    $arResult["OFFER_PARAMS"]["GROUP"] = array();
    $rsGroup = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("Group"), array("UF_ACTIVE" => 1), array(
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
            "SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_GROUP", $arGroup["UF_XML_ID"]),
            "COUNT" => 0,
            "ICON" => $strIcon,
            "DISABLED" => "N"
        );
    }
    $arResult["OFFER_PARAMS"]["COLOR"] = array();
    $rsList = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("Colors"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $sphinxVal = \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_COLOR_FRUIT", $arList["UF_XML_ID"]);
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
    $rsList = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PeriodHeightNow"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["HEIGHT_NOW"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_HEIGHT_NOW", $arList["UF_XML_ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["HEIGHT_10"] = array();
    $rsList = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PeriodHeight"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["HEIGHT_10"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_HEIGHT_10", $arList["UF_XML_ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"] = array();
    $rsList = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PeriodPrice"), array("UF_ACTIVE" => 1), array(
        "UF_NAME",
        "ID",
        "UF_XML_ID"
    ), array(), true);
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["VALUES"][$arList["UF_XML_ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["UF_NAME"],
            "VALUE" => $arList["UF_XML_ID"],
            "SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("CATALOG_PRICE_LIST", $arList["UF_XML_ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $arResult["OFFER_PARAMS"]["AVAILABLE"] = array();
    $rsList = CIBlockPropertyEnum::GetList(Array(
        "DEF" => "DESC",
        "SORT" => "ASC"
    ), Array("IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('offers'), "CODE" => "AVAILABLE"));
    while ($arList = $rsList->Fetch()) {
        $arResult["OFFER_PARAMS"]["AVAILABLE"]["VALUES"][$arList["ID"]] = array(
            "ID" => $arList["ID"],
            "NAME" => $arList["VALUE"],
            "VALUE" => $arList["ID"],
            "SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_AVAILABLE", $arList["ID"]),
            "COUNT" => 0,
            "DISABLED" => "N"
        );
    }

    $obCache->endDataCache($arResult);
}

if ($arRequest["search"] == 'Y') {
    $arParams["QUERY"] = trim($arRequest["searchQuery"]);

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
    $strSearchHtml = ob_get_contents();
    ob_end_clean();
} else {

    if (strlen($arRequest["filterAlias"]) > 0) {
        if (!$GLOBALS["USER"]->IsAdmin()) {
            foreach ($_REQUEST as $strKey => $strValue) {
                if ($strKey != 'filterAlias' && strpos($strKey, 'PAGEN_') !== 0) {
                    \Bitrix\Iblock\Component\Tools::process404('', false, true, false);

                    return;
                }
            }
        }

        $arRequest["F"] = $this->getFilterByAlias($arRequest["filterAlias"]);
        if (!empty($arRequest["F"])) {
            $arRequest["frmCatalogFilterSent"] = 'Y';
        }
    }

    $arPropertyTreeParams = array();
    if ($arRequest["frmCatalogFilterSent"] == 'Y') {
        if (strlen($arRequest["F"]["PROPERTY_GROUP"]) > 0) {
            $arResult["GROUP_ID"] = $arResult["OFFER_PARAMS"]["GROUP"]["VALUES"][$arRequest["F"]["PROPERTY_GROUP"]]["ID"];
            $arPropertyTreeParams["ENABLE_TYPE"] = $arResult["GROUP_ID"];
        }
    }

    // зависимые от группы свойства
    $arResult["PROPERTY_TREE"] = \ig\CHelper::getPropertyTree($arPropertyTreeParams);
    foreach ($arResult["PROPERTY_TREE"] as $strParamCode => $arParamData) {
        if (!isset($arResult["OFFER_PARAMS"][$strParamCode])) {
            $arResult["OFFER_PARAMS"][$strParamCode] = array(
                "NAME" => $arParamData["NAME"]
            );
        }

        $arResult["OFFER_PARAMS"][$strParamCode]["DISABLED"] = 'N';

        foreach ($arParamData["VALUES"] as $arValue) {
            if ($arValue["DISABLED"] != 'Y') {
                $bPropertyDisabled = false;
            }

            $arResult["OFFER_PARAMS"][$strParamCode]["VALUES"][] = array(
                "NAME" => $arValue["NAME"],
                "ICON" => $arValue["ICON"],
                "VALUE" => $arValue["XML_ID"],
                "SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_" . $strParamCode, $arValue["XML_ID"]),
                "COUNT" => 0,
                "DISABLED" => $arValue["DISABLED"]
            );
        }
    }

// глобальное выключение свойств для группы
    if ($arResult["GROUP_ID"] > 0) {
        $rsPropGroup = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PropertyGroup"), array("UF_ACTIVE" => 1), array(
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
    if ($arRequest["frmCatalogFilterSent"] == 'Y') {
        $intSelectedGroupID = false;
        foreach ($arRequest["F"] as $strCode => $obValue) {
            $strCode = trim($strCode);

            if (is_array($obValue)) {
                if ($strCode == 'PROPERTY_FLOWERING' || $strCode == 'PROPERTY_RIPENING') {
                    if (isset($obValue["FROM"]) && $obValue["FROM"] == 0)
                        unset($obValue["FROM"]);
                    if (isset($obValue["TO"]) && $obValue["TO"] == 11)
                        unset($obValue["TO"]);
                }

                if (!empty($obValue)) {
                    \CatalogFilter::$arRequestFilter[$strCode] = $obValue;
                }
            } else {
                if (!empty(trim($obValue))) {
                    \CatalogFilter::$arRequestFilter[$strCode] = trim($obValue);
                }
            }

            if (!empty(\CatalogFilter::$arRequestFilter[$strCode])) {
                $arSearchParams[$strCode] = \ig\sphinx\CCatalogOffers::convertValueToSphinx($strCode, \CatalogFilter::$arRequestFilter[$strCode]);
            }
        }

        if (empty($arRequest["filterAlias"])) {
            $arResult["FILTER_ALIAS"] = $this->processUseFilter($arRequest["F"]);
        }
    }

    $arFacetExcludeParams = array_keys($arSearchParams);
    $arFacetExcludeParams[] = 'PROPERTY_GROUP';
    $arFacetExcludeParams[] = 'PROPERTY_USAGE';

    $arFacetExcludeParams = array_unique($arFacetExcludeParams);


    $obSearch = new \ig\sphinx\CCatalogOffers();

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
        $arSearchParams[">CATALOG_PRICE_" . \ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;

        $arSearchGroupParams[">CATALOG_PRICE_" . \ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;
        $arSearchUsageParams[">CATALOG_PRICE_" . \ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")] = 0;
    } elseif ($arResult["IS_NEW"]) {
        $intNewEnumID = \ig\CHelper::getEnumID(\ig\CHelper::getIblockIdByCode('offers'), "NEW", "Да");
        $arSearchParams["PROPERTY_NEW"] = $intNewEnumID;

        $arSearchGroupParams["PROPERTY_NEW"] = $intNewEnumID;
        $arSearchUsageParams["PROPERTY_NEW"] = $intNewEnumID;
    }

    // GROUP
    $arFacetSearchParams = array("FILTER" => $arSearchGroupParams);
    $arFacetFields = \ig\sphinx\CCatalogOffers::getFacetFields(array("INCLUDE" => "PROPERTY_GROUP"));



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
    $arFacetData = $obSearch->searchFacet(array("FILTER" => $arSearchUsageParams), \ig\sphinx\CCatalogOffers::getFacetFields(array("INCLUDE" => "PROPERTY_USAGE")), true);

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
        $arFacetData = $obSearch->searchFacet(array("FILTER" => $arSearchParams), \ig\sphinx\CCatalogOffers::getFacetFields(array("EXCLUDE" => $arFacetExcludeParams)), true);
        foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {

            $strCode = str_replace("PROPERTY_", '', \ig\sphinx\CCatalogOffers::convertFieldCode($strSphinxCode, false));

            $arTotalFilter = $arSearchParams;
            unset($arTotalFilter[\ig\sphinx\CCatalogOffers::convertFieldCode($strSphinxCode, false)]);

            $arResult["COUNT_DATA"][$strCode]["TOTAL"] = $obSearch->getCount($arTotalFilter, 'id');
            foreach ($arDataList as $arData) {
                $arResult["COUNT_DATA"][$strCode][$arData[$strSphinxCode]] = $arData["count"];
            }
        }

        // фасетим по фильтрованным параметрам
        foreach ($arSearchParams as $strCode => $value) {
            $arTmpFilter = $arSearchParams;

            $arParamData = \ig\sphinx\CCatalogOffers::getSphinxConfig(\ig\sphinx\CCatalogOffers::convertFieldCode($strCode));

            if ($arParamData["CONTROL"] == 'CHECKBOX' && $arParamData["MULTIPLE"] == 'Y') {
                // сначала берем фасет по неотмеченным вариантам
                $arTmpFilter["NOT_" . $strCode] = $arTmpFilter[$strCode];
                unset($arTmpFilter[$strCode]);

                $arFacetData = $obSearch->searchFacet(array("FILTER" => $arTmpFilter), \ig\sphinx\CCatalogOffers::getFacetFields(array("INCLUDE" => $strCode)), true);

                foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
                    $strCodeTmp = str_replace("PROPERTY_", '', \ig\sphinx\CCatalogOffers::convertFieldCode($strSphinxCode, false));
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

                $arFacetData = $obSearch->searchFacet(array("FILTER" => $arTmpFilter), \ig\sphinx\CCatalogOffers::getFacetFields(array("INCLUDE" => $strCode)), true);

                foreach ($arFacetData["FACET"] as $strSphinxCode => $arDataList) {
                    $strCode = str_replace("PROPERTY_", '', \ig\sphinx\CCatalogOffers::convertFieldCode($strSphinxCode, false));
                    $arResult["COUNT_DATA"][$strCode]["TOTAL"] = $obSearch->getCount($arTmpFilter, 'id');
                    foreach ($arDataList as $arData) {
                        $arResult["COUNT_DATA"][$strCode][$arData[$strSphinxCode]] = $arData["count"];
                    }
                }
            }
        }
    }
    $arFilterTmp = $arSearchParams;
    unset($arFilterTmp["PROPERTY_GROUP"]);
    unset($arFilterTmp["PROPERTY_USAGE"]);

    $arResult["FILTER_EMPTY"] = (empty($arFilterTmp) ? 'Y' : 'N');

    if (!empty($arResult["FILTER_ALIAS"])) {
        $arResult["RESULT_LINK"] = 'https://' . $_SERVER["HTTP_HOST"] . '/katalog/rasteniya/?filterAlias=' . $arResult["FILTER_ALIAS"];
    } else {
        $arResult["RESULT_LINK"] = 'https://' . $_SERVER["HTTP_HOST"] . '/katalog/rasteniya/?' . str_replace('AJAX=Y', 'AJAX=N', $APPLICATION->GetCurParam());
    }

    \ig\CRegistry::add('catalogFilter', $arSearchParams);

    ob_start();
    $this->includeComponentTemplate($strTemplate);

    $strFilterHrml = ob_get_contents();
    ob_end_clean();

    if (false) {
        ob_start();

        $arListSearchParams = array(
            "FILTER" => $arSearchParams,
            "LIMIT" => 100,
            "ORDER" => array("name" => "asc")
        );

        $arList = array();
        $rsItems = $obSearch->search($arListSearchParams);
        while ($arItem = $obSearch->fetch($rsItems)) {
            $arList[] = $arItem["name"] . ' [<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=2&type=catalog&ID=' . $arItem["id"] . '&lang=ru&find_section_section=0&WF=Y">' . $arItem["id"] . ']</a>';
        }


        echo '<div class="filter-results js-filter-results">' . implode('<br>', $arList) . '</div>';

        $strListHrml = ob_get_contents();
        ob_end_clean();
    }
}

if ($arParams["IS_AJAX"] == 'Y') {
    $arResponse = array();

    if (strlen($strFilterHrml) > 0) {
        \ig\CRegistry::add('catalog-filter_html', $strFilterHrml);
    }

//	if(strlen($strListHrml)>0) {
//		$arResponse["results_html"] = $strListHrml;
//	}

    if (strlen($arResult["RESULT_LINK"]) > 0) {
        \ig\CRegistry::add('catalog-page_url', $arResult["RESULT_LINK"]);
    }

    if (strlen($strSearchHtml) > 0) {
        //$arResponse["html"] = $strSearchHtml;
        \ig\CRegistry::add('catalog-html', $strSearchHtml);
    }

    //echo json_encode($arResponse);
} else {
    echo $strFilterHrml;
//	echo $strListHrml;
}
