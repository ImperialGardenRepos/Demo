<?php

namespace IG\Sphinx;

use mysqli;

abstract class Engine
{
    /**
     * @var bool|mysqli
     */
    private static $db = false;

    private
        $indexName = '',
        $connectionIndex = '';

    function __construct()
    {
        \Bitrix\Main\Loader::includeModule("iblock");
        $this->connectionIndex = \COption::GetOptionString("search", "sphinx_connection");
    }

    public function getConnectionDB()
    {
        return self::$db;
    }

    public function setIndexName($strIndexName)
    {
        $this->indexName = $strIndexName;
    }

    protected function getIndexName()
    {
        return $this->indexName;
    }

    protected function canConnect()
    {
        return function_exists("mysql_connect") || function_exists("mysqli_connect");
    }

    public function connect($ignoreErrors = false)
    {
        $connectionIndex = $this->connectionIndex;
        $indexName = $this->indexName;

        global $APPLICATION;

        if (!preg_match("/^[a-zA-Z0-9_]+\$/", $indexName)) {
            if ($ignoreErrors)
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_ERROR_INDEX_NAME"));
            else
                throw new \Bitrix\Main\Db\ConnectionException('Sphinx connect error', "Sphinx connect error");
            return false;
        }

        if (!$this->canConnect()) {
            if ($ignoreErrors)
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_EXT_IS_MISSING"));
            else
                throw new \Bitrix\Main\Db\ConnectionException('Sphinx connect error', GetMessage("SEARCH_SPHINX_CONN_EXT_IS_MISSING"));
            return false;
        }

        $error = "";
        self::$db = $this->internalConnect($connectionIndex, $error);
        if (!self::$db) {
            if ($ignoreErrors)
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_ERROR", ["#ERRSTR#" => $error]));
            else
                throw new \Bitrix\Main\Db\ConnectionException('Sphinx connect error', GetMessage("SEARCH_SPHINX_CONN_ERROR", ["#ERRSTR#" => $error]));
            return false;
        }

        if ($ignoreErrors) {
            $result = $this->query("SHOW TABLES");
            if (!$result) {
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_ERROR", ["#ERRSTR#" => $this->getError()]));
                return false;
            }

            if ($indexName == "") {
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_NO_INDEX"));
                return false;
            }

            $indexType = "";
            while ($res = $this->fetch($result)) {
                if ($indexName === $res["Index"])
                    $indexType = $res["Type"];
            }

            if ($indexType == "") {
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_INDEX_NOT_FOUND"));
                return false;
            }

            if ($indexType != "rt") {
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_CONN_INDEX_WRONG_TYPE"));
                return false;
            }

            $indexColumns = [];
            $result = $this->query("DESCRIBE `" . $indexName . "`");
            if (!$result) {
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_DESCR_ERROR", ["#ERRSTR#" => $this->getError()]));
                return false;
            }

            while ($res = $this->fetch($result)) {
                $indexColumns[$res["Field"]] = $res["Type"];
            }

            $missed = [];
            foreach (self::$fields as $name => $type) {
                if (!isset($indexColumns[$name]) || $indexColumns[$name] !== $type) {
                    $missed[] = self::$typesMap[$type] . " = " . $name;
                }
            }

            if (!empty($missed)) {
                $APPLICATION->ThrowException(GetMessage("SEARCH_SPHINX_NO_FIELDS", ["#FIELD_LIST#" => implode(", ", $missed)]));
                return false;
            }
        }

        //2.2.1 version test (they added HAVING support and moved to UTF8)
        if (
            !defined("BX_UTF")
            && $this->query("select id from " . $this->indexName . " where id=1 group by id having count(*) = 1")
        ) {
            $this->recodeToUtf = true;
        }

        return true;
    }

    protected function internalConnect($connectionIndex, &$error)
    {
        $error = "";
        if (function_exists("mysql_connect")) {
            $result = @mysql_connect($connectionIndex);
            if (!$result)
                $error = mysql_error();
        } else if (function_exists("mysqli_connect")) {
            $result = mysqli_init();

            if (strpos($connectionIndex, ":") !== false) {
                list($host, $port) = explode(":", $connectionIndex, 2);
            } else {
                $host = $connectionIndex;
                $port = '';
            }

            if (!$result->real_connect($host, '', '', '', $port)) {
                $error = mysqli_connect_error();
                $result = false;
            }
        } else {
            $result = false;
            $error = 'No MySql connection function has been found.';
        }

        return $result;
    }

    public function getNextID()
    {
        $strSql = 'SELECT MAX(id) as next_id FROM ' . $this->indexName;
        $arResult = $this->selectRow($strSql);

        return intval($arResult["next_id"]) + 1;
    }

    public function selectRow($strSql)
    {
        $rsQuery = $this->query($strSql);
        return $this->fetch($rsQuery);
    }

    public function queryFetchAll($strSql)
    {
//        file_put_contents(__DIR__ .'/txt.log', $strSql, FILE_APPEND);
        $rs = $this->query($strSql);
        $arResult = $this->fetchAll($rs);

        return $arResult;
    }

    public function fetchAll($rs)
    {
        $arResult = [];

        while ($ar = $this->fetch($rs)) {
            $arTmp = [];
            foreach ($ar as $key => $str) {
                $arTmp["~" . $key] = $str;
                $arTmp[$key] = self::unEscape($str);
            }

            $arResult[] = $arTmp;
        }

        return $arResult;
    }

    public static function convertFieldCode($strCode, $bToSphinx = true)
    {
        if ($bToSphinx) {
            if (strpos($strCode, 'CATALOG_PRICE_' . \ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")) !== false) {
                $strResult = str_replace('CATALOG_PRICE_' . \ig\CRegistry::get("CATALOG_ACTION_PRICE_ID"), 'catalog_action_price', $strCode);
            } else if (strpos($strCode, 'CATALOG_PRICE_' . \ig\CRegistry::get("CATALOG_BASE_PRICE_ID")) !== false) {
                $strResult = str_replace('CATALOG_PRICE_' . \ig\CRegistry::get("CATALOG_BASE_PRICE_ID"), 'catalog_base_price', $strCode);
            } else {
                $strResult = str_replace("PROPERTY_", 'P_', $strCode);
                $strResult = ToLower($strResult);
            }
        } else {
            if ($strCode == 'catalog_action_price') {
                $strResult = 'CATALOG_PRICE_' . \ig\CRegistry::get("CATALOG_ACTION_PRICE_ID");
            } else if ($strCode == 'catalog_base_price') {
                $strResult = 'CATALOG_PRICE_' . \ig\CRegistry::get("CATALOG_BASE_PRICE_ID");
            } else {
                $strResult = str_replace("p_", 'property_', $strCode);
                $strResult = ToUpper($strResult);
            }
        }

        return $strResult;
    }

    public static function convertValueToSphinx($strCode, $value)
    {
        $convertedValue = $value;

        if (is_array($value)) {
            $arTmp = [];
            foreach ($value as $key => $val) {
                $arTmp[$key] = self::convertValueToSphinx($strCode, $val);
            }

            $convertedValue = $arTmp;
        } else {
            $strSphinxCode = self::convertFieldCode($strCode);
            $arFieldData = static::getSphinxConfig($strSphinxCode);

            if (preg_match("#\d{2}\.\d{2}\.\d{4}#", $value)) {
                $convertedValue = MakeTimeStamp($value, "DD.MM.YYYY HH:MI:SS");
            } else if ($arFieldData["CRC32"] == 'Y') {
                $convertedValue = crc32($value);
            }
        }

        return $convertedValue;
    }

    /*
     SELECT *, IN(brand_id,1,2,3,4) AS b FROM facetdemo WHERE MATCH('Product') AND b=1 LIMIT 0,10
FACET brand_name, brand_id BY brand_id ORDER BY brand_id ASC
FACET property ORDER BY COUNT(*) DESC
FACET INTERVAL(price,200,400,600,800) ORDER BY FACET() ASC
FACET categories ORDER BY FACET() ASC;
     */
    public function queryFacet($strQuery, $bFacetOnly = false)
    {
        $arResult = [];

        $intCnt = 0;
        $intStartPart = ($bFacetOnly ? 1 : 0);
        if (is_object(self::$db) || is_resource(self::$db)) {
            if (mysqli_multi_query(self::$db, $strQuery)) {
                do {
                    if ($rsQuery = mysqli_store_result(self::$db)) {
                        if ($intCnt >= $intStartPart) {
                            $arTmp = [];
                            while ($arFetchRow = $this->fetch($rsQuery)) {
                                if ($intCnt > 0) {
                                    $arFetchRow = self::prepareFacetResult($arFetchRow);
                                } else {
                                    $arFetchRow = self::prepareResult($arFetchRow);
                                }
                                $arTmp[] = $arFetchRow;
                            }

                            if ($bFacetOnly) {
                                $arResult[] = $arTmp;
                            } else {
                                if ($intCnt === 0) {
                                    $arResult['QUERY'] = $arTmp;
                                } else {
                                    $arResult['FACET'][] = $arTmp;
                                }
                            }
                        }
                        mysqli_free_result($rsQuery);
                    }

                    if (mysqli_more_results(self::$db)) {
                        $intCnt++;
                    }
                } while (mysqli_next_result(self::$db));

            }
        }
        return $arResult;
    }

    private static function prepareFacetResult($arData)
    {
        $arData["count"] = $arData["count(*)"];
        unset($arData["count(*)"]);

        return $arData;
    }

    private static function prepareResult($arData)
    {
        $arSphinxConfig = static::getSphinxConfig();
        foreach ($arSphinxConfig as $strFieldCode => $arFieldData) {
            if ($arFieldData["MULTIPLE"] == 'Y') {
                $arData[$strFieldCode] = explode(',', $arData[$strFieldCode]);
            }
        }

        return $arData;
    }

    public function query($query)
    {
        if (is_resource(self::$db)) {
            $result = mysql_query($query, self::$db);
        } else if (is_object(self::$db)) {
            $result = self::$db->query($query);
        } else {
            $result = false;
        }
        return $result;
    }

    public function fetch($queryResult)
    {
        if (is_resource(self::$db)) {
            $result = mysql_fetch_array($queryResult, MYSQL_ASSOC);
        } else if (is_object(self::$db)) {
            $result = mysqli_fetch_assoc($queryResult);
        } else {
            $result = false;
        }
        return $result;
    }

    public function truncate()
    {
        $this->query("truncate rtindex " . $this->indexName);
        $this->connect();
    }

    public function deleteById($ID)
    {
        $this->query("delete from " . $this->indexName . " where id = " . intval($ID));
    }

    public function getError()
    {
        if (is_resource(self::$db)) {
            $result = "[" . mysql_errno(self::$db) . "] " . mysql_error(self::$db);
        } else if (is_object(self::$db)) {
            $result = "[" . self::$db->errno . "] " . self::$db->error;
        } else {
            $result = '';
        }
        return $result;
    }

    public function recodeTo($text)
    {
        if ($this->recodeToUtf) {
            $error = "";
            $result = \Bitrix\Main\Text\Encoding::convertEncoding($text, SITE_CHARSET, "UTF-8", $error);
            if (!$result && !empty($error))
                #$this->ThrowException($error, "ERR_CHAR_BX_CONVERT");
                return $text;

            return $result;
        } else {
            return $text;
        }
    }

    public function unEscape($str)
    {
        static $replace = [
            "\\",
            "'",
            "/",
            ")",
            "(",
            "$",
            "~",
            "!",
            "@",
            "^",
            "-",
            "|",
            "<",
        ];
        static $search = [
            "\\",
            "\'",
            "\/",
            "\)",
            "\(",
            "\$",
            "\~",
            "\!",
            "\@",
            "\^",
            "\-",
            "\|",
            "\<",
        ];

        $str = str_replace($search, $replace, $str);

        $stat = count_chars($str, 1);
        if (isset($stat[ord('"')]) && $stat[ord('"')] % 2 === 1)
            $str = str_replace('"', '\\\"', $str);

        return $str;
    }

    public function Escape($str)
    {
        static $search = [
            "\\",
            "'",
            "/",
            ")",
            "(",
            "$",
            "~",
            "!",
            "@",
            "^",
            "-",
            "|",
            "<",
            "\x0",
            "=",
        ];

        static $replace = [
            "\\",
            "\'",
            "\/",
            "\)",
            "\(",
            "\$",
            "\~",
            "\!",
            "\@",
            "\^",
            "\-",
            "\|",
            "\<",
            " ",
            " ",
        ];

        $str = str_replace($search, $replace, $str);

        $stat = count_chars($str, 1);
        if (isset($stat[ord('"')]) && $stat[ord('"')] % 2 === 1)
            $str = str_replace('"', '\\\"', $str);

        return $str;
    }

    public function Escape2($str)
    {
        static $search = [
            //"\\",
            "'",
            "\"",
            "\x0",
        ];
        static $replace = [
            //"\\\\",
            "\'",
            "\\\\\"",
            " ",
        ];
        return str_replace($search, $replace, $str);
    }

    public function prepareSearchPhrase($strQuery)
    {
        $strResult = str_replace(['\\', '/'], ['', ' '], $strQuery);
        $strResult = preg_replace("#\s{2,}#ims", ' ', $strResult);

        return $strResult;
    }

    abstract static function getSphinxConfig();
}
