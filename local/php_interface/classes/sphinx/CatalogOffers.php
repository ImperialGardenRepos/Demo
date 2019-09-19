<?php

namespace IG\Sphinx;

use ig\CRegistry;

class CatalogOffers extends Engine
{
    private $arBatchData;

    private static $obCatalogOffers = false,
        $arPropToCrc = [],
        $arCache = [];

    public static function getIndexObject()
    {
        if (!self::$obCatalogOffers)
            self::$obCatalogOffers = new CatalogOffers();

        return self::$obCatalogOffers;

        return false;
    }

    function OnAfterIBlockElementDeleteHandler($intID)
    {
        if ($intID > 0) {
            CatalogOffers::getIndexObject()->deleteById($intID);
        }
    }

    function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if ($arFields["ID"] > 0 && $arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers")) {
            CatalogOffers::getIndexObject()->indexItem($arFields["ID"]);
        }
    }

    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields["RESULT"] && $arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers")) {
            CatalogOffers::getIndexObject()->indexItem($arFields["ID"]);
        } else if ($arFields["ID"] > 0 && $arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
            $rsI = \CIBlockElement::GetList(false, [
                "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("offers"), "PROPERTY_CML2_LINK" => $arFields["ID"]], false, false, [
                "ID",
            ]);
            while ($arI = $rsI->Fetch()) {
                CatalogOffers::getIndexObject()->indexItem($arI["ID"]);
            }
        }
    }

    public static function initEvents()
    {
        // элементы перехватываем только при апдейте из админки. импорт обрабатываем локально
        if (isset($GLOBALS["APPLICATION"])) {
            if (strpos($GLOBALS["APPLICATION"]->GetCurDir(), '/bitrix/') === 0) {
                AddEventHandler("iblock", "OnAfterIBlockElementDelete", [
                    "\ig\sphinx\CCatalogOffers",
                    "OnAfterIBlockElementDeleteHander",
                ], 1000);
                AddEventHandler("iblock", "OnAfterIBlockElementUpdate", [
                    "\ig\sphinx\CCatalogOffers",
                    "OnAfterIBlockElementUpdateHander",
                ], 1000);
                AddEventHandler("iblock", "OnAfterIBlockElementAdd", [
                    "\ig\sphinx\CCatalogOffers",
                    "OnAfterIBlockElementAddHander",
                ], 1000);
            }
        }
    }

    function __construct()
    {
        parent::__construct();
        parent::setIndexName('catalog_offers');
        parent::connect();
    }

    private static function getCatalogFields()
    {
        $arResult = [
            "ID",
            "IBLOCK_ID",
            "ACTIVE",
            "IBLOCK_SECTION_ID",
            "PROPERTY_NAME_LAT",
            "PROPERTY_GROUP",
            "PROPERTY_USAGE",
            "PROPERTY_CROWN",
            "PROPERTY_HAIRCUT_SHAPE",
            "PROPERTY_LIGHT",
            "PROPERTY_WATER",
            "PROPERTY_GROUND",
            "PROPERTY_ZONA_POSADKI",
            "PROPERTY_FLOWERING",
            "PROPERTY_RIPENING",
            "PROPERTY_TASTE",
            "PROPERTY_COLOR_FRUIT",
            "PROPERTY_COLOR_FLOWER",
            "PROPERTY_COLOR_LEAF",
            "PROPERTY_COLOR_LEAF_AUTUMN",
            "PROPERTY_COLOR_BARK",
            "PROPERTY_ADDITIONAL",
            "PROPERTY_HEIGHT_10",
            "PROPERTY_RECOMMENDED",
            "PROPERTY_PRIORITY",
        ];

        return $arResult;
    }

    private static function getOfferFields()
    {
        $arResult = [
            "NAME", "IBLOCK_ID", "ID", "IBLOCK_SECTION_ID", "ACTIVE",
            "PROPERTY_HEIGHT_NOW", "PROPERTY_CML2_LINK", "PROPERTY_MULTISTEMMED", "PROPERTY_AVAILABLE", "PROPERTY_NEW_DATE_END", "PROPERTY_NEW",
        ];

        \ig\CHelper::addSelectFields($arResult);

        return $arResult;
    }

    public function batchProcess($arData, $arParams = [])
    {
        static $arBatchData;
        if ($arData["TYPE"] == 'INSERT' || $arData["TYPE"] == 'REPLACE') {
            if ($arData['QUERY'] !== '') {
                $arBatchData[$arData['TYPE']][] = $arData['QUERY'];
            }

            if (is_array($arBatchData[$arData["TYPE"]]) && count($arBatchData[$arData["TYPE"]]) > 0) {

                $arBatchData[$arData["TYPE"]] = array_slice($arBatchData[$arData["TYPE"]], 2000);

                $mtime = microtime();        //Считываем текущее время
                $mtime = explode(" ", $mtime);    //Разделяем секунды и миллисекунды
                $tstart = $mtime[1] + $mtime[0];

                $strQuery = $this->getQueryHead($arData["TYPE"]) . ' ' . $arData['QUERY'];

                if (!$arData['QUERY']) {
                    return true;
                }

                $result = $this->query($strQuery);

                $mtime = microtime();
                $mtime = explode(" ", $mtime);
                $mtime = $mtime[1] + $mtime[0];
//					file_put_contents($_SERVER["DOCUMENT_ROOT"].'/skock_/sphinx_reindex.txt', date("d.m.Y H:i:s").' '.sprintf ($arData["TYPE"]." товаров (".count($arBatchData[$arData["TYPE"]]).") обработан за %f секунд", ($mtime - $tstart))."\r\n", FILE_APPEND);

                $arBatchData[$arData["TYPE"]] = [];

                if (!$result) {
                    throw new \Bitrix\Main\Db\SqlQueryException('Sphinx query error', $this->getError(), $strQuery);
                } else {
                    return true;
                }

            }
        }
    }

    private function getIndexAttributesForSelect()
    {
        return implode(",", $this->arIndexAttributes);
    }

    private function getQuery($arParams)
    {
        $arWhere = [];

        $bWeightOrder = isset($arParams["ORDER"]["weight"]);

        $arMatchFields = self::getMatchFields();

        if (!empty($arParams["FILTER"]) && !isset($arParams["MATCH_FILTER"]) && !isset($arParams["WHERE_FILTER"])) {
            $arParams["MATCH_FILTER"] = [];
            $arParams["WHERE_FILTER"] = [];

            foreach ($arParams["FILTER"] as $strCode => $value) {
                $strSphinxCode = self::convertFieldCode($strCode);
                if (isset($arMatchFields[$strSphinxCode])) {
                    $arParams["MATCH_FILTER"][$strSphinxCode] = $value;
                } else {
                    $arParams["WHERE_FILTER"][$strSphinxCode] = $value;
                }
            }
        }

        if (!empty($arParams["MATCH_FILTER"])) {
            if (is_array($arParams["MATCH_FILTER"])) { // пока только один уровень
                $arMatchTmp = [];
                foreach ($arParams["MATCH_FILTER"] as $strCode => $value) {
                    $arMatchTmp[] = '@' . $strCode . ' ' . (is_array($value) ? implode(' ', $value) : $value);
                }

                $arWhere[] = "MATCH('" . implode('', $arMatchTmp) . "')";
            } else {
                $arWhere[] = "MATCH('" . $arParams["MATCH_FILTER"] . "')";
            }
        }

        if (is_array($arParams["WHERE_FILTER"])) {
            $arWhere = array_merge($arWhere, $this->prepareFilter($arParams["WHERE_FILTER"], false));
        }

        if ($arParams["GET_COUNT"] == 'Y') {
            $strCountField = (strlen($arParams["COUNT_FIELD"]) > 0 ? $arParams["COUNT_FIELD"] : '*');

            $sql = "
				select count(" . ($strCountField == '*' ? $strCountField : 'distinct ' . $strCountField) . ") as cnt
					" . ($cond1 != "" ? ",$cond1 as cond1" : "") . "
					from " . parent::getIndexName() . "
					" . (!empty($arWhere) ? "where " . implode("\nand\t", $arWhere) : "");
        } else {
            if (!empty($arParams["SELECT"])) {
                $arSelect = $this->prepareSelect($arParams["SELECT"]);
            } else $arSelect = ["*"];

            if (is_array($arParams["GROUPS"])) {
                $arGroup = $this->prepareGroup($arParams["GROUP"]);
            }

            $intLimit = intval($arParams["LIMIT"]);
            if ($intLimit <= 0) {
                $intLimit = 20;
            }

            $intOffset = intval($arParams["OFFSET"]);
            if ($intOffset <= 0) {
                $intOffset = 0;
            }

            if (!empty($arWhere["cond1"])) {
                $cond1 = $arWhere["cond1"];
                $arWhere["cond1"] = 'cond1=1';
            }

            $sql = "
				select " . implode(", ", $arSelect) . "
					" . ($bWeightOrder ? ",weight() as w" : "") . "
					" . ($cond1 != "" ? ",$cond1 as cond1" : "") . "
					from " . parent::getIndexName() . "
					" . (!empty($arWhere) ? "where " . implode("\nand\t", $arWhere) : "");

            if (!empty($arGroup)) {
                $sql .= " GROUP BY " . implode(', ', $arGroup);
            }

            $sql .= $this->__PrepareSort($arParams["ORDER"]);

            $sql .= "
					limit " . intval($intOffset) . ", " . $intLimit . " option max_matches = 50000";
        }

        return $sql;

        return false;
    }

    public function searchFacet($arParams, $arFacet = [], $bReturnFacetOnly = false)
    {

        $strHash = 'FACET_' . md5(serialize($arParams) . '|' . serialize($arFacet) . '|' . intval($bReturnFacetOnly));
        //echo __FILE__.': '.__LINE__.'<pre>'.print_r(self::$arCache, true).'</pre>';
        if (isset(self::$arCache[$strHash])) {
            echo 'cache';
            $arResult = self::$arCache[$strHash];
        } else {
            $arParams["MAX_MATCHES"] = 500;
            $strQuery = $this->getQuery($arParams);

            if (empty($arFacet)) {
                $arFacet = self::getFilterFields();
            }

            foreach ($arFacet as $strFacetCode => $arF) {
                $strQuery .= " FACET " . $strFacetCode;

                if (!empty($arF["ORDER"])) {
                    if (is_array($arF["ORDER"])) {
                        $strQuery .= $this->__PrepareSort($arF["ORDER"]);
                    } else $strQuery .= 'ORDER BY ' . $arF["ORDER"];
                }
            }

            $arTmp = $this->queryFacet($strQuery, $bReturnFacetOnly);

            $arResult = [];
            if (!$bReturnFacetOnly) {
                $arResult["RESULT"] = array_shift($arTmp);
            }

            foreach ($arFacet as $strCode => $arFacetParams) {
                $arResult["FACET"][$strCode] = array_shift($arTmp);
            }

            self::$arCache[$strHash] = $arResult;
        }

        return $arResult;
    }

    public function getCount($arFilter, $strCountField)
    {
        $strHash = 'COUNT_' . md5(serialize($arFilter) . '|' . $strCountField);

        if (isset(self::$arCache[$strHash])) {
            $intResult = self::$arCache[$strHash];
        } else {
            $strCountField = $strCountField === 'id' ? '*' : $strCountField;
            $strQuery = $this->getQuery(["FILTER" => $arFilter,
                "GET_COUNT" => "Y",
                "COUNT_FIELD" => $strCountField,
            ]);
            $arTmp = array_shift($this->queryFetchAll($strQuery));

            $intResult = $arTmp["cnt"];
            self::$arCache[$strHash] = $intResult;
        }

        return $intResult;
    }

    public function search($arParams)
    {
        $strQuery = $this->getQuery($arParams);

        if ($strQuery) {
            $r = $this->query($strQuery);

            if (!$r) {
                throw new \Bitrix\Main\Db\SqlQueryException('Sphinx select error', $this->getError(), $sql);
            } else {
                $result = $r;
//				$result = $this -> fetchAll($r);
            }
        }

        return $result;
    }

    function prepareSelect($arSelect)
    {
        $arResult = [];

        foreach ($arSelect as $strGroup) {
            $arResult[] = self::convertFieldCode($strGroup);
        }

        return $arResult;
    }

    function prepareGroup($arGroup)
    {
        $arResult = [];

        foreach ($arGroup as $strGroup) {
            $arResult[] = self::convertFieldCode($strGroup);
        }

        return $arResult;
    }

    function __PrepareSort($aSort = [])
    {
        $arOrder = [];
        if (!is_array($aSort) && !empty($aSort))
            $aSort = [$aSort => "ASC"];

        foreach ($aSort as $key => $ord) {
            $ord = strtoupper($ord) <> "ASC" ? "DESC" : "ASC";

            if ($key == 'FACET')
                $key = 'FACET()';
            else if ($key == 'COUNT')
                $key = 'COUNT(*)';
            else $key = self::convertFieldCode($key);

            switch ($key) {
                case "weight":
                    $arOrder[] = 'w ' . $ord;
                    break;

                default:
                    $strSphinxField = self::convertFieldCode($key);
                    $arOrder[] = $strSphinxField . ' ' . $ord;
                    break;
            }
        }

        if (count($arOrder) == 0) {
            $arOrder[] = "id ASC";
        }

        return " ORDER BY " . implode(", ", $arOrder);
    }

    function filterField($field, $value, $inSelect, $bNegative = false)
    {
        $arWhere = [];
        if (is_array($value)) {
            if (!empty($value)) {
                $s = "";
                if ($inSelect) {
                    foreach ($value as $i => $v) {
//						$s .= ",".sprintf("%u", crc32($v));
                        $s .= "," . $v;
                    }
                    $arWhere[] = "in($field $s)";
                } else {
                    foreach ($value as $i => $v) {
                        $s .= ($s ? " or " : "") . "$field = " . $v;
//						$s .= ($s ? " or " : "")."$field = ".sprintf("%u", crc32($v));
                    }
                    $arWhere[] = "($s)";
                }
            }
        } else {
            if ($value !== false) {
                $strOperation = substr($field, 0, 1);

                if ($strOperation === '!') {
                    $field = substr($field, 1);
                    $arWhere[] = "$field != " . $value;
                } else if ($strOperation === '>') {
                    $field = substr($field, 1);
                    $arWhere[] = "$field > " . $value;
                } else if ($strOperation === '<') {
                    $field = substr($field, 1);
                    $arWhere[] = "$field < " . $value;
                } else {
                    $arWhere[] = "$field " . ($bNegative ? '!' : '') . "= " . $value;
                }
            }
        }
        return $arWhere;
    }

    function prepareFilter($arFilter, $inSelect = false)
    {
        $arWhere = [];
        if (!is_array($arFilter))
            $arFilter = [];

        $orLogic = false;
        if (array_key_exists("LOGIC", $arFilter)) {
            $orLogic = ($arFilter["LOGIC"] == "OR");
            unset($arFilter["LOGIC"]);
        }

        foreach ($arFilter as $field => $val) {
            $field = strtoupper($field);

            $bNegative = false;
            if (strpos($field, "NOT_") === 0) {
                $field = substr($field, 4);
                $bNegative = true;
            }

            if (
                is_array($val)
                && count($val) == 1
                && !is_numeric($field)
                && !isset($val["FROM"])
                && !isset($val["TO"])
            ) {
                $val = array_shift($val);
                //$val = $val[0];
            }

            $strSphinxField = self::convertFieldCode($field);

            if (is_numeric($strSphinxField) && is_array($val)) {
                $subFilter = $this->prepareFilter($val, true);
                if (!empty($subFilter)) {
                    if (isset($subFilter["cond1"]))
                        $arWhere["cond1"][] = "(" . implode(")and(", $subFilter) . ")";
                    else
                        $arWhere[] = "(" . implode(")and(", $subFilter) . ")";
                }
            } else {
                if (is_array($val)) {
//					$arWhere = array_merge($arWhere, $this->filterField($strSphinxField, $val, $inSelect));

                    if (isset($val["FROM"]) || isset($val["TO"])) {
                        if (isset($val["FROM"])) {
                            $val["FROM"] = intval($val["FROM"]);
                            $arWhere[] = $strSphinxField . '>=' . $val["FROM"];
                        }

                        if (isset($val["TO"])) {
                            $val["TO"] = intval($val["TO"]);
                            $arWhere[] = $strSphinxField . '<=' . $val["TO"];
                        }
                    } else {
                        $arTmp = [];
                        foreach ($val as $oneValItem) {
                            if (!is_numeric($oneValItem)) {
                                $arTmp[] = "'" . $oneValItem . "'";
                            } else {
                                $arTmp[] = $oneValItem;
                            }
                        }

                        $arWhere = array_merge($arWhere, [$strSphinxField . ($bNegative ? ' NOT' : '') . " IN (" . implode(',', $arTmp) . ")"]);
                    }
                } else {
                    if (!is_numeric($val)) {
                        $val = "'" . $val . "'";
                    }
//					$arWhere = array_merge($arWhere, array($strSphinxField."=".$val));
                    $arWhere = array_merge($arWhere, $this->filterField($strSphinxField, $val, $inSelect, $bNegative));
                }
            }
        }

        if (isset($arWhere["cond1"]))
            $arWhere["cond1"] = "(" . implode(")and(", $arWhere["cond1"]) . ")";

        if ($orLogic && !empty($arWhere)) {
            $arWhere = [
                "cond1" => "(" . implode(")or(", $arWhere) . ")",
            ];
        }
//        file_put_contents(__DIR__ . '/log.txt', serialize($arWhere), FILE_APPEND);
        return $arWhere;
    }

    public function indexItem($srcFilter, $arSrcParams = [])
    {
        $arParams = [];
        if ($arSrcParams["BATCH"] == 'Y') $arParams["BATCH"] = 'Y';
        if ($arSrcParams["FULL_REINDEX"] == 'Y') $arParams["FULL_REINDEX"] = 'Y';

        if (is_array($srcFilter)) {
            $arOfferFilter = $srcFilter;
        } else $arOfferFilter["ID"] = intval($srcFilter);

        $arOfferFilter["IBLOCK_ID"] = \ig\CHelper::getIblockIdByCode("offers");

        $rsOffer = \CIBlockElement::GetList(false, $arOfferFilter, false, $arParams["NAV"], self::getOfferFields());

        if ($arOffer = $rsOffer->Fetch()) {
            if ($arOffer["PROPERTY_CML2_LINK_VALUE"] > 0) {
                $rsCatalog = \CIBlockElement::GetList(false, [
                    "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("catalog"),
                    "ID" => $arOffer["PROPERTY_CML2_LINK_VALUE"],
                ], false, $arParams["NAV"], self::getCatalogFields());
                if ($arCatalog = $rsCatalog->Fetch()) {
                    return $this->indexItemArray($arOffer, $arCatalog, $arParams);
                }
            }

            return false;
        }
    }

    public static function prepareIndexData($arData)
    {
        $arResult = $arData;

//		$arPropToCrc = array("p_usage", "p_color_fruit", "p_color_flower", "p_color_leaf", "p_color_leaf_autumn", "p_color_bark", "p_additional", "cat_section_1", "cat_section_2");
        $arIndexConfig = self::getSphinxConfig();

        foreach ($arData as $strCode => $fieldValue) {
            if ($arIndexConfig[$strCode]["MULTIPLE"] == 'Y' && !is_array($fieldValue)) {
                $fieldValue = [];
            }

            if ($arIndexConfig[$strCode]["CRC32"] == 'Y') {
                if (!empty($fieldValue)) {
                    if (is_array($fieldValue)) {
                        $arTmp = [];
                        foreach ($fieldValue as $strUsageCode) {
                            if (strlen($strUsageCode) > 0) {
                                $arTmp[] = crc32($strUsageCode);
                            }
                        }

                        $arResult[$strCode] = $arTmp;
                    } else {
                        $arResult[$strCode] = crc32($fieldValue);
                    }
                }
            } else {
                if (is_array($fieldValue)) {
                    if (!empty($fieldValue)) {
                        $arResult[$strCode] = $fieldValue;
                    } else $arResult[$strCode] = [];
                } else {
                    $arResult[$strCode] = self::Escape($fieldValue);
                }
            }
        }


        return $arResult;
    }

    public function indexItemArray($arOffer, $arCatalog, $arSrcParams = [])
    {
        if ($arOffer["ACTIVE"] == 'N' || $arCatalog["ACTIVE"] == 'N') {
            if ($arOffer["ID"] > 0)
                $this->deleteByID($arOffer["ID"]);
        } else {
            $arParams = [];
            if ($arSrcParams["BATCH"] == 'Y') $arParams["BATCH"] = 'Y';
            if ($arSrcParams["FULL_REINDEX"] == 'Y') $arParams["FULL_REINDEX"] = 'Y';

            $arIblockSectionID = [];
            if ($arCatalog["IBLOCK_SECTION_ID"] > 0) {
                $rsNav = \CIBlockSection::GetNavChain(\ig\CHelper::getIblockIdByCode('catalog'), $arCatalog["IBLOCK_SECTION_ID"]);
                while ($arNav = $rsNav->Fetch()) {
                    $arIblockSectionID[] = $arNav["ID"];
                }
            }

            $intNewDateEnd = 0;
            if (!empty($arOffer["PROPERTY_NEW_DATE_END_VALUE"])) {
                $intNewDateEnd = MakeTimeStamp($arOffer["PROPERTY_NEW_DATE_END_VALUE"], "DD.MM.YYYY HH:MI:SS");
            }

            $arTmpData = [
                "id" => $arOffer["ID"],
                "name" => $arOffer["NAME"],
                "cat_section_1" => $arIblockSectionID[0],
                "cat_section_2" => $arIblockSectionID[1],
                "cat_id" => $arCatalog["ID"],
                "p_name_lat" => $arOffer["PROPERTY_NAME_LAT_VALUE"],
                "p_group" => $arCatalog["PROPERTY_GROUP_VALUE"],
                "p_usage" => $arCatalog["PROPERTY_USAGE_VALUE"],
                "p_crown" => $arCatalog["PROPERTY_CROWN_VALUE"],
                "p_multistemmed" => $arOffer["PROPERTY_MULTISTEMMED_ENUM_ID"],
                "p_haircut_shape" => $arCatalog["PROPERTY_HAIRCUT_SHAPE_VALUE"],
                "p_light" => $arCatalog["PROPERTY_LIGHT_VALUE"],
                "p_water" => $arCatalog["PROPERTY_WATER_VALUE"],
                "p_ground" => $arCatalog["PROPERTY_GROUND_VALUE"],
                "p_zona_posadki" => $arCatalog["PROPERTY_ZONA_POSADKI_VALUE"],
                "p_flowering" => $arCatalog["PROPERTY_FLOWERING_VALUE"],
                "p_ripening" => $arCatalog["PROPERTY_RIPENING_VALUE"],
                "p_taste" => $arCatalog["PROPERTY_TASTE_VALUE"],
                "p_color_fruit" => $arCatalog["PROPERTY_COLOR_FRUIT_VALUE"],
                "p_color_flower" => $arCatalog["PROPERTY_COLOR_FLOWER_VALUE"],
                "p_color_leaf" => $arCatalog["PROPERTY_COLOR_LEAF_VALUE"],
                "p_color_leaf_autumn" => $arCatalog["PROPERTY_COLOR_LEAF_AUTUMN_VALUE"],
                "p_color_bark" => $arCatalog["PROPERTY_COLOR_BARK_VALUE"],
                "p_additional" => $arCatalog["PROPERTY_ADDITIONAL_VALUE"],
                "p_height_now" => $arOffer["PROPERTY_HEIGHT_NOW_VALUE"],
                "p_height_10" => $arCatalog["PROPERTY_HEIGHT_10_VALUE"],
                "p_recommended" => intval($arCatalog["PROPERTY_RECOMMENDED_ENUM_ID"]),
                "p_available" => $arOffer["PROPERTY_AVAILABLE_ENUM_ID"],
                "p_priority" => intval($arCatalog["PROPERTY_PRIORITY_VALUE"]),
                "catalog_price_list" => \ig\CHelper::priceToPeriod($arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_BASE_PRICE_ID")]),
                "p_new_date_end" => $intNewDateEnd,
                "p_new" => intval($arOffer["PROPERTY_NEW_ENUM_ID"]),
                "catalog_base_price" => floatval($arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_BASE_PRICE_ID")]),
                "catalog_action_price" => floatval($arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_ACTION_PRICE_ID")]),
            ];

            $arPreparedIndexData = self::prepareIndexData($arTmpData);

            $indexResult = $this->setIndexRecord($arPreparedIndexData, $arParams);

            return $indexResult;
        }
    }

    public static function getPropToCrc()
    {
        if (empty(self::$arPropToCrc)) {

            $arResult = [];

            $arConfig = self::getSphinxConfig();
            foreach ($arConfig as $strCode => $arFieldConfig) {
                if ($arFieldConfig["CRC32"] == 'Y') {
                    $arResult[] = $strCode;
                }
            }

            self::$arPropToCrc = $arResult;
        }

        return self::$arPropToCrc;
    }

    public static function getMatchFields()
    {
        $arResult = [];

        foreach (self::getSphinxConfig() as $strCode => $arFieldData) {
            if ($arFieldData["MATCH"] == 'Y') {
                $arResult[$strCode] = $arFieldData;
            }
        }

        return $arResult;
    }

    public static function getFilterFields()
    {
        $arResult = [];

        foreach (self::getSphinxConfig() as $strCode => $arFieldData) {
            if ($arFieldData["FILTER"] == 'Y') {
                $arResult[$strCode] = $arFieldData;
            }
        }

        return $arResult;
    }

    /**
     * @param array $arParams
     * format ['INCLUDE','EXCLUDE']
     * if INCLUDE isset, return only fields from INCLUDE
     * if EXCLUDE isset, return all fields except EXCLUDE
     * @return array $arFacet fields used in filter to be selected from Sphinx
     */
    public static function getFacetFields($arParams = [])
    {
        $arFacet = self::getFilterFields();

        if (!empty($arParams["EXCLUDE"])) {
            if (!is_array($arParams["EXCLUDE"])) {
                $arParams["EXCLUDE"] = [$arParams["EXCLUDE"]];
            }

            foreach ($arParams["EXCLUDE"] as $strCode) {
                $strSphinxCode = self::convertFieldCode($strCode);
                unset($arFacet[$strSphinxCode]);
            }
        }

        if (!empty($arParams["INCLUDE"])) {
            $arTmp = [];

            if (!is_array($arParams["INCLUDE"])) {
                $arParams["INCLUDE"] = [$arParams["INCLUDE"]];
            }

            foreach ($arParams["INCLUDE"] as $strCode) {
                $strSphinxCode = self::convertFieldCode($strCode);
                $arTmp[$strSphinxCode] = $arFacet[$strSphinxCode];
            }

            $arFacet = $arTmp;
        }


        return $arFacet;
    }

    public static function getSphinxConfig($strCode = '')
    {
        $arResult = [
            "id" => [],
            "name" => ["MATCH" => "Y"],
            "cat_section_1" => ["MATCH" => "Y"],
            "cat_section_2" => ["MATCH" => "Y"],
            "cat_id" => [],
            "p_name_lat" => ["MATCH" => "Y"],
            "p_group" => ["FILTER" => "Y", "MATCH" => "Y"],
            "p_usage" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y"],
            "p_crown" => ["FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_multistemmed" => ["FILTER" => "Y"],
            "p_haircut_shape" => ["FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_light" => ["FILTER" => "Y"],
            "p_water" => ["FILTER" => "Y"],
            "p_ground" => ["FILTER" => "Y"],
            "p_zona_posadki" => ["FILTER" => "Y"],
            "p_flowering" => ["MULTIPLE" => "Y", "FILTER" => "Y"],
            "p_ripening" => ["MULTIPLE" => "Y", "FILTER" => "Y"],
            "p_taste" => ["FILTER" => "Y"],
            "p_color_fruit" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_color_flower" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_color_leaf" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_color_leaf_autumn" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_color_bark" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_additional" => ["MULTIPLE" => "Y", "CRC32" => "Y", "FILTER" => "Y", "CONTROL" => "CHECKBOX"],
            "p_height_now" => ["FILTER" => "Y"],
            "p_height_10" => ["FILTER" => "Y"],
            "p_recommended" => ["FILTER" => "Y"],
            "p_priority" => [],
            "p_available" => ["FILTER" => "Y"],
            "catalog_price_list" => ["FILTER" => "Y"],
            "catalog_base_price" => [],
            "catalog_action_price" => [],
            "p_new_date_end" => [],
            "p_new" => [],
        ];

        if (strlen($strCode) > 0) {
            return $arResult[$strCode];
        } else return $arResult;
    }

    public static function getSphinxFields()
    {
        return array_keys(self::getSphinxConfig());
    }

    private function getQueryHead($strType)
    {
        $strResult = $strType . " INTO " . parent::getIndexName() . " (" . implode(",", self::getSphinxFields()) . ") VALUES ";

        return $strResult;
    }

    public function setIndexRecord($arFields, $arParams = [])
    {
        // ищем уже существующую запись
        if ($arParams["FULL_REINDEX"] != 'Y') {
            $strQuery = 'SELECT id from ' . parent::getIndexName() . ' WHERE id=' . $arFields["id"];
            $arItem = $this->selectRow($strQuery);
        }

        if ($arItem["id"]) { // нашли - заменяем
            $strType = 'REPLACE';
        } else { // не нашли - создаем новую
            $strType = 'INSERT';
        }

        if ($arParams["BATCH"] != 'Y') {
            $strQuery = $this->getQueryHead($strType);
        } else $strQuery = '';

        $arTmp = [];
        foreach (self::getSphinxFields() as $strSphinxCode) {
            if (is_array($arFields[$strSphinxCode])) {
                if (!empty($arFields[$strSphinxCode])) {
                    $arTmp[] = "(" . implode(',', $arFields[$strSphinxCode]) . ")";
                } else $arTmp[] = "()";
            } else {
                $arTmp[] = "'" . $arFields[$strSphinxCode] . "'";
            }
        }
        $strQuery .= "(" . implode(',', $arTmp) . ")";

        if ($arParams["BATCH"] == 'Y') {
            return ["TYPE" => $strType, "QUERY" => $strQuery];
        } else {
            $result = $this->query($strQuery);
            if (!$result) {
                throw new \Bitrix\Main\Db\SqlQueryException('Sphinx insert error', $this->getError(), $strQuery);
            } else return true;
        }
    }
}
