<?php

namespace ig;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CIBlockElement;

/**
 * Class CRegistry
 * @package ig
 */
class CRegistry
{
    /*
    $sk = skRegistry::getInstance();
    skRegistry::getInstance()->add("s1", 1);
    echo skRegistry::getInstance()->get("s1");
    skRegistry::getInstance()->add("s1", 2);
    echo skRegistry::getInstance()->get("s1");
    skRegistry::getInstance()->update("s1", 3);
    echo $sk->get("s1");
    $sk->remove("s1");
    echo '-'.$sk->get("s1");
     */

    /**
     * @var null|static
     */
    private static $_instance = null;

    /**
     * @var array
     */
    private $_registry = array();

    /**
     * @return null|static
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getInstance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();

            if (Loader::includeModule("iblock")) {
                $arIblock = IblockTable::getList(
                    array("filter" => array("CODE" => 'registry-params'), "select" => array("ID"))
                )->fetch();
                if ($arIblock["ID"] > 0) {
                    $rsI = CIBlockElement::GetList(Array(), array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => $arIblock["ID"]
                    ), false, false, array(
                        "ID", "IBLOCK_ID", "PROPERTY_COORD", "PROPERTY_FILE", "PREVIEW_TEXT", "CODE"
                    ));
                    while ($arI = $rsI->Fetch()) {
                        if (!empty($arI["PROPERTY_FILE_VALUE"])) {
                            $strValue = $arI["PROPERTY_FILE_VALUE"];
                        } elseif (!empty($arI["PROPERTY_COORD_VALUE"])) {
                            $strValue = $arI["PROPERTY_COORD_VALUE"];
                        } else {
                            $strValue = $arI["PREVIEW_TEXT"];
                        }

                        static::$_instance->_registry[$arI["CODE"]] = $strValue;
                    }
                }
            }
        }

        return static::$_instance;
    }

    /**
     * @param $key
     * @param $object
     * @param $rewrite
     * @return bool
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private static function set($key, $object, $rewrite)
    {
        if ($rewrite || !isset(static::getInstance()->_registry[$key])) {
            static::getInstance()->_registry[$key] = $object;
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function exists($key)
    {
        return isset(static::getInstance()->_registry[$key]);
    }

    /**
     * Adds value of specified key
     *
     * @param $key
     * @param $object
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function add($key, $object)
    {
        static::getInstance()->set($key, $object, false);
    }

    /**
     * Updates value of specified key
     *
     * @param $key
     * @param $object
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function update($key, $object)
    {
        static::getInstance()->set($key, $object, true);
    }

    /**
     * Returns value from registry for specified key
     *
     * @param $key
     * @param bool|false $default
     * @return bool
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function get($key, $default = false)
    {
        if (isset(static::getInstance()->_registry[$key])) {
            return static::getInstance()->_registry[$key];
        }
        return $default;
    }


    /**
     * Removes specified key from registry
     * @param $key
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function remove($key)
    {
        if (array_key_exists($key, static::getInstance()->_registry)) {
            unset(static::getInstance()->_registry[$key]);
        }
    }

    /**
     * CRegistry constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
    }
}