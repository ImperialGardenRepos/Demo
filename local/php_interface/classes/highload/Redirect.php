<?php

namespace ig\Highload;

use ig\Highload\Base;

class Redirect extends Base
{
    static $intHLID = 0;

    public static function setHLID()
    {
        static::$intHLID = \ig\Highload\Base::getHighloadBlockIDByName("Redirect");
    }

    public static function checkReferer()
    {
        $arHost = explode(":", $_SERVER["HTTP_HOST"]);

        if (strpos($_SERVER["HTTP_REFERER"], $arHost[0]) === false)
            return false;
        else return true;
    }

    /**
     * операция обратная http://php.net/manual/ru/function.parse-url.php
     * @param $parsed_url
     *
     * @return string
     */
    private static function unparseUrl($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    public static function checkRedirect($strUrl = '')
    {
        if (empty($strUrl)) $strUrl = $_SERVER["REQUEST_URI"];

        if (strpos($strUrl, '//') !== false) {
            $strRedirectUrl = str_replace('//', '/', $strUrl);
            self::doRedirect($strRedirectUrl);
        }

        if (!empty($strUrl)) {
            $strUrl = self::prepareUrl($strUrl);

            $strWorkUrl = $strUrl;

            do {
                if ($arRedirect = self::getFirst(false, array("=UF_OLD_URL" => $strWorkUrl, "UF_ACTIVE" => 1))) {
                    $strWorkUrl = $arRedirect["UF_NEW_URL"];
                }
            } while ($arRedirect["ID"] > 0);

            if ($strWorkUrl != $strUrl)
                self::doRedirect($strWorkUrl);

            $arUrl = parse_url($strUrl);
            if (substr($arUrl["path"], -1) != '/' && substr($arUrl["path"], -4) != '.php') {
                $arUrl["path"] .= '/';
                self::doRedirect(self::unparseUrl($arUrl));
            }

            // редиректа не произошло - нет кастомных правил, проверяем общие правила
            if (strpos($strUrl, '/catalogue/') === 0) {
                self::doRedirect('/katalog/rasteniya/');
            }
        }
    }

    public static function doRedirect($strUrl)
    {
        if (!empty($strUrl)) LocalRedirect($strUrl, false, "301 Moved Permanently");
    }

    public static function getRedirect($obFilter)
    {
        if (!is_array($obFilter)) {
            if (is_numeric($obFilter))
                $arFilter = array("ID" => $obFilter, "UF_ACTIVE" => 1);
            else $arFilter = array("=UF_OLD_URL" => $obFilter, "UF_ACTIVE" => 1);
        } else $arFilter = $obFilter;

        return self::getList(self::getHLID(), $arFilter);
    }

    public static function prepareUrl($strUrl)
    {
        $arUrl = parse_url($strUrl);

        $arTmp = array($arUrl["path"]);
        if (!empty($arUrl["query"])) $arTmp[] = $arUrl["query"];

        return implode("?", $arTmp);
    }

    public static function rebuildRedirectList()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        self::rebuildSectionRedirectList();
        self::rebuildElementRedirectList();
    }

    private static function rebuildElementRedirectList()
    {
        $intIblockID = \ig\CHelper::getIblockIdByCode('catalog');

        $rsI = \CIBlockElement::GetList(Array(), array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $intIblockID,
            "!PROPERTY_OLD_URL" => false
        ), false, false, array(
            "ID", "IBLOCK_ID", "PROPERTY_OLD_URL", "DETAIL_PAGE_URL"
        ));
        while ($arI = $rsI->GetNext()) {
            $strOldLink = $arI["PROPERTY_OLD_URL_VALUE"];
            $strNewLink = $arI["DETAIL_PAGE_URL"];

            $arUrl = parse_url($strOldLink);
            $strStoreUrl = $arUrl["path"];
            if (!empty($arUrl["query"])) {
                $strStoreUrl .= '?' . $arUrl["query"];
            }

            $arExistUrl = self::getFirst(self::getHLID(), array("UF_OLD_URL" => $strStoreUrl));
            if (empty($arExistUrl)) {
                $arLoadFields = array(
                    "UF_OLD_URL" => $strStoreUrl,
                    "UF_NEW_URL" => $strNewLink,
                    "UF_ACTIVE" => 1,
                    "UF_SOURCE" => "IBLOCK",
                    "UF_SOURCE_TYPE" => "ELEMENT",
                    "UF_SOURCE_ID" => $arI["ID"]
                );

                $intNewredirectID = self::add(false, $arLoadFields);
                if (empty($intNewredirectID)) {
                    echo 'Error adding url<br>';
                    echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($arLoadFields, true) . '</pre>';
                    die();
                }
            } else {
                if ($arExistUrl["UF_NEW_URL"] != $strNewLink) {
                    $arUpdateFields = array(
                        "UF_NEW_URL" => $strNewLink
                    );

                    $bUpdate = self::update(false, $arExistUrl["ID"], $arUpdateFields);
                    if ($bUpdate !== true) {
                        echo 'Error updating url<br>';
                        echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($arUpdateFields, true) . '</pre>';
                        die();
                    }
                }
            }
        }
    }

    private static function rebuildSectionRedirectList()
    {
        $intIblockID = \ig\CHelper::getIblockIdByCode('catalog');

        $rsSec = \CIBlockSection::GetList(Array(), array("ACTIVE" => "Y", "IBLOCK_ID" => $intIblockID, "!UF_OLD_URL" => false), false, array("ID", "IBLOCK_ID", "UF_OLD_URL", "SECTION_PAGE_URL"));
        while ($arSec = $rsSec->GetNext()) {
            $strOldLink = $arSec["UF_OLD_URL"];
            $strNewLink = $arSec["SECTION_PAGE_URL"];

            $arUrl = parse_url($strOldLink);
            $strStoreUrl = $arUrl["path"];
            if (!empty($arUrl["query"])) {
                $strStoreUrl .= '?' . $arUrl["query"];
            }

            $arExistUrl = self::getFirst(self::getHLID(), array("UF_OLD_URL" => $strStoreUrl));
            if (empty($arExistUrl)) {
                $arLoadFields = array(
                    "UF_OLD_URL" => $strStoreUrl,
                    "UF_NEW_URL" => $strNewLink,
                    "UF_ACTIVE" => 1,
                    "UF_SOURCE" => "IBLOCK",
                    "UF_SOURCE_TYPE" => "SECTION",
                    "UF_SOURCE_ID" => $arSec["ID"]
                );

                $intNewredirectID = self::add(false, $arLoadFields);
                if (empty($intNewredirectID)) {
                    echo 'Error adding url<br>';
                    echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($arLoadFields, true) . '</pre>';
                    die();
                }
            } else {
                if ($arExistUrl["UF_NEW_URL"] != $strNewLink) {
                    $arUpdateFields = array(
                        "UF_NEW_URL" => $strNewLink
                    );

                    $bUpdate = self::update(false, $arExistUrl["ID"], $arUpdateFields);
                    if ($bUpdate !== true) {
                        echo 'Error updating url<br>';
                        echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($arUpdateFields, true) . '</pre>';
                        die();
                    }
                }
            }
        }
    }
}