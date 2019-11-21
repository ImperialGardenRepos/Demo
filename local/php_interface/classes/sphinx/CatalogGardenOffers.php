<?php

namespace ig\sphinx;

use ig\CRegistry;

class CatalogGardenOffers extends Engine
{
    private static $obCatalogGardenOffers = false,
        $arPropToCrc = [];

    public static function getIndexObject()
    {
        if (!self::$obCatalogGardenOffers)
            self::$obCatalogGardenOffers = new CatalogGardenOffers();

        return self::$obCatalogGardenOffers;

        return false;
    }

    function OnAfterIBlockElementDeleteHandler($intID)
    {
        if ($intID > 0) {
            CatalogGardenOffers::getIndexObject()->deleteById($intID);
        }
    }

    function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if ($arFields["ID"] > 0 && $arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers-garden")) {
            CatalogGardenOffers::getIndexObject()->indexItem($arFields["ID"]);
        }
    }

    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields["RESULT"] && $arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers-garden")) {
            CatalogGardenOffers::getIndexObject()->indexItem($arFields["ID"]);
        } else if ($arFields["ID"] > 0 && $arFields["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog-garden")) {
            $rsI = \CIBlockElement::GetList(false, [
                "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("offers-garden"), "PROPERTY_CML2_LINK" => $arFields["ID"]], false, false, [
                "ID",
            ]);
            while ($arI = $rsI->Fetch()) {
                CatalogGardenOffers::getIndexObject()->indexItem($arI["ID"]);
            }
        }
    }

    public static function initEvents()
    {
        // элементы перехватываем только при апдейте из админки. импорт обрабатываем локально
        if (isset($GLOBALS["APPLICATION"])) {
            if (strpos($GLOBALS["APPLICATION"]->GetCurDir(), '/bitrix/') === 0) {
                AddEventHandler("iblock", "OnAfterIBlockElementDelete", [
                    "\ig\sphinx\CCatalogGardenOffers",
                    "OnAfterIBlockElementDeleteHandler",
                ], 1000);
                AddEventHandler("iblock", "OnAfterIBlockElementUpdate", [
                    "\ig\sphinx\CCatalogGardenOffers",
                    "OnAfterIBlockElementUpdateHandler",
                ], 1000);
                AddEventHandler("iblock", "OnAfterIBlockElementAdd", [
                    "\ig\sphinx\CCatalogGardenOffers",
                    "OnAfterIBlockElementAddHandler",
                ], 1000);
            }
        }
    }

    function __construct()
    {
        parent::__construct();
        parent::setIndexName('catalog_garden_offers');
        parent::connect();
    }

    private static function getCatalogFields()
    {
        $arResult = [
            "ID",
            "IBLOCK_ID",
            "ACTIVE",
            "SORT",
            "DETAIL_PAGE_URL",
            "IBLOCK_SECTION_ID",
            "PROPERTY_BRAND",
            "PROPERTY_USAGE",
            "PROPERTY_SEASON",
            "PROPERTY_RECOMMENDED",
            "PROPERTY_FULL_NAME",
        ];

        return $arResult;
    }

    private static function getOfferFields()
    {
        $arResult = [
            "NAME", "IBLOCK_ID", "ID", "ACTIVE",
            "PROPERTY_CML2_LINK", "PROPERTY_AVAILABLE", "PROPERTY_NEW_DATE_END", "PROPERTY_NEW",
        ];

        \ig\CHelper::addSelectFields($arResult);

        return $arResult;
    }

    public function batchProcess($arData, $arParams = [])
    {
        if ($arData["TYPE"] == 'INSERT' || $arData["TYPE"] == 'REPLACE') {
            if (strlen($arData["QUERY"]) > 0) {
                $this->arBatchData[$arData["TYPE"]][] = $arData["QUERY"];
            }

            if (count($this->arBatchData[$arData["TYPE"]]) > 4000 || $arParams["FLUSH"] == 'Y') {
                if (is_array($this->arBatchData[$arData["TYPE"]]) && count($this->arBatchData[$arData["TYPE"]]) > 0) {
                    $mtime = microtime();        //Считываем текущее время
                    $mtime = explode(" ", $mtime);    //Разделяем секунды и миллисекунды
                    $tstart = $mtime[1] + $mtime[0];

                    $strQuery = $this->getQueryHead($arData["TYPE"]) . ' ' . implode(", ", $this->arBatchData[$arData["TYPE"]]);

                    $result = $this->query($strQuery);

                    $mtime = microtime();
                    $mtime = explode(" ", $mtime);
                    $mtime = $mtime[1] + $mtime[0];
//					file_put_contents($_SERVER["DOCUMENT_ROOT"].'/skock_/sphinx_reindex.txt', date("d.m.Y H:i:s").' '.sprintf ($arData["TYPE"]." товаров (".count($this->arBatchData[$arData["TYPE"]]).") обработан за %f секунд", ($mtime - $tstart))."\r\n", FILE_APPEND);

                    $this->arBatchData[$arData["TYPE"]] = [];

                    if (!$result) {
                        throw new \Bitrix\Main\Db\SqlQueryException('Sphinx query error', $this->getError(), $strQuery);
                    } else return true;
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
                    $strSphinxCode = self::convertFieldCode($strCode);
                    $arMatchTmp[] = '@' . $strSphinxCode . ' ' . (is_array($value) ? implode(' ', $value) : $value);
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
            $intLimit = intval($arParams["LIMIT"]);
            if ($intLimit <= 0) {
                $intLimit = 20;
            }

            $intOffset = intval($arParams["OFFSET"]);
            if ($intOffset <= 0) {
                $intOffset = 0;
            }

            if (!empty($arParams["SELECT"])) {
                $arTmp = [];
                foreach ($arParams["SELECT"] as $strCode) {
                    $strSphinxField = self::convertFieldCode($strCode);

                    if (!empty($strSphinxField)) {
                        $arTmp[] = $strSphinxField;
                    }
                }

                if (!empty($arTmp)) {
                    $strSelect = implode(", ", $arTmp);
                }
            } else {
                $strSelect = ' * ';
            }

            $sql = "
				select " . $strSelect . "
					" . ($bWeightOrder ? ",weight() as w" : "") . "
					" . ($cond1 != "" ? ",$cond1 as cond1" : "") . "
					from " . parent::getIndexName() . "
					" . (!empty($arWhere) ? "where " . implode("\nand\t", $arWhere) : "");

            if (!empty($arParams["GROUP"])) {
                $arTmp = [];
                foreach ($arParams["GROUP"] as $strCode) {
                    $strSphinxField = self::convertFieldCode($strCode);

                    if (!empty($strSphinxField)) {
                        $arTmp[] = $strSphinxField;
                    }
                }

                if (!empty($arTmp)) {
                    $sql .= ' GROUP BY ' . implode(", ", $arTmp);
                }
            }

            $sql .= $this->__PrepareSort($arParams["ORDER"]) . " limit " . intval($intOffset) . ", " . $intLimit . " option max_matches = 500";
        }
//		if($bWeightOrder) $sql .= ', field_weights=(title=10,title_1c=5,article=15)';
        return $sql;

        return false;
    }

    public function searchFacet($arParams, $arFacet = [], $bReturnFacetOnly = false)
    {
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

        return $arResult;
    }

    public function getCount($arFilter, $strCountField)
    {
        $strCountField = $strCountField === 'id' ? '*' : $strCountField;
        $strQuery = $this->getQuery(["FILTER" => $arFilter, "GET_COUNT" => "Y", "COUNT_FIELD" => $strCountField]);
        $arTmp = array_shift($this->queryFetchAll($strQuery));

        return $arTmp["cnt"];
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

        $arOfferFilter["IBLOCK_ID"] = \ig\CHelper::getIblockIdByCode("offers-garden");

        $rsOffer = \CIBlockElement::GetList(false, $arOfferFilter, false, $arParams["NAV"], self::getOfferFields());

        if ($arOffer = $rsOffer->Fetch()) {
            if ($arOffer["PROPERTY_CML2_LINK_VALUE"] > 0) {
                $rsCatalog = \CIBlockElement::GetList(false, [
                    "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("catalog-garden"),
                    "ID" => $arOffer["PROPERTY_CML2_LINK_VALUE"],
                ], false, $arParams["NAV"], self::getCatalogFields());
                if ($arCatalog = $rsCatalog->GetNext()) {
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
                $rsNav = \CIBlockSection::GetNavChain(\ig\CHelper::getIblockIdByCode('catalog-garden'), $arCatalog["IBLOCK_SECTION_ID"]);
                while ($arNav = $rsNav->Fetch()) {
                    $arIblockSectionID[] = $arNav["ID"];
                }
            }

            $intNewDateEnd = 0;
            if (!empty($arOffer["PROPERTY_NEW_DATE_END_VALUE"])) {
                $intNewDateEnd = MakeTimeStamp($arOffer["PROPERTY_NEW_DATE_END_VALUE"], "DD.MM.YYYY HH:MI:SS");
            }
            $minPrice = (float)$arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_BASE_PRICE_ID")];
            $minActionPrice = (float)$arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_ACTION_PRICE_ID")];
            if($minActionPrice > 0 && $minActionPrice < $minPrice) {
                $minPrice = $minActionPrice;
            }
            $arTmpData = [
                "id" => $arOffer["ID"],
                "name" => $arOffer["NAME"],
                "cat_section_1" => $arIblockSectionID[0],
                "cat_section_2" => $arIblockSectionID[1],
                "cat_id" => $arCatalog["ID"],
                "p_usage" => $arCatalog["PROPERTY_USAGE_ENUM_ID"],
                "p_brand" => $arCatalog["PROPERTY_BRAND_ENUM_ID"],
                "p_season" => $arCatalog["PROPERTY_SEASON_ENUM_ID"],
                "p_recommended" => intval($arCatalog["PROPERTY_RECOMMENDED_ENUM_ID"]),
                "p_available" => $arOffer["PROPERTY_AVAILABLE_ENUM_ID"],
                "catalog_price_list" => \ig\CHelper::priceToPeriod($arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_BASE_PRICE_ID")]),
                "p_new_date_end" => $intNewDateEnd,
                "p_new" => intval($arOffer["PROPERTY_NEW_ENUM_ID"]),
                "catalog_base_price" => floatval($arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_BASE_PRICE_ID")]),
                "catalog_action_price" => floatval($arOffer["CATALOG_PRICE_" . CRegistry::get("CATALOG_ACTION_PRICE_ID")]),
                'p_min_price' => $minPrice,
                'p_sort' => $arCatalog['SORT'],
                'p_full_name' => $arCatalog['PROPERTY_FULL_NAME_VALUE'],
                'p_link' => $arCatalog['DETAIL_PAGE_URL']
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
            "p_usage" => ["FILTER" => "Y"],
            "p_brand" => ["FILTER" => "Y"],
            "p_recommended" => ["FILTER" => "Y"],
            "p_available" => ["FILTER" => "Y"],
            "catalog_price_list" => ["FILTER" => "Y"],
            "catalog_base_price" => [],
            "catalog_action_price" => [],
            "p_new_date_end" => [],
            "p_new" => [],
            'p_min_price' => [],
            'p_sort' => [],
            'p_full_name' => [],
            'p_link' => [],
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
