<?php

namespace ig\Helpers;

use ig\Datasource\Sphinx\CatalogOffer;
use ig\Sphinx\Query;

class SphinxHelper
{
    /**
     * Converts Bitrix array keys to Sphinx index rt_field or rt_attr
     * @param string $key
     * @return string
     */
    public static function convertKey(string $key): string
    {
        $key = mb_strtoupper($key);
        switch ($key) {
            case 'CATALOG_PRICE_' . CATALOG_ACTION_PRICE_ID:
                $key = str_replace('CATALOG_PRICE_' . CATALOG_ACTION_PRICE_ID, 'catalog_action_price', $key);
                break;
            case 'CATALOG_PRICE_' . CATALOG_BASE_PRICE_ID:
                $key = str_replace('CATALOG_PRICE_' . CATALOG_ACTION_PRICE_ID, 'catalog_base_price', $key);
                break;
            case 'CATALOG_PRICE_LIST':
            case 'CAT_SECTION_1':
            case 'CAT_SECTION_2':
                break;
            default:
                $key = 'P_' . $key;
                break;
        }
        return mb_strtolower($key);
    }

    /**
     * Converts returning Sphinx index keys to Bitrix array keys
     * @param string $key
     * @param bool $addPrefix
     * @return string
     */
    public static function revertKey(string $key, bool $addPrefix = false): string
    {
        switch ($key) {
            case 'catalog_action_price':
                $key = 'CATALOG_PRICE_' . CATALOG_ACTION_PRICE_ID;
                break;
            case 'catalog_base_price':
                $key = 'CATALOG_PRICE_' . CATALOG_BASE_PRICE_ID;
                break;
            default:
                if ($addPrefix === true) {
                    $key = str_replace('p_', 'property_', $key);
                    break;
                }
                $key = str_replace('p_', '', $key);
        }

        return mb_strtoupper($key);
    }


    /**
     * @param string $key
     * @param array|string $value
     * @param string $entityClass
     * @return array|bool|false|int
     */
    public static function convertValue(string $key, $value, $entityClass = CatalogOffer::class)
    {
        if (is_array($value)) {
            $convertedValueArray = [];
            foreach ($value as $subKey => $subValue) {
                $convertedValueArray[$subKey] = self::convertValue($key, $subValue);
            }
            return $convertedValueArray;
        }

        if (preg_match("#\d{2}\.\d{2}\.\d{4}#", $value)) {
            return MakeTimeStamp($value, 'DD.MM.YYYY HH:MI:SS');
        }

        /** @var CatalogOffer $entityClass */
        $fields = $entityClass::$fields;
        $convertedKey = self::convertKey($key);
        if (isset($fields[$convertedKey]['CRC32']) && $fields[$convertedKey]['CRC32'] === 'Y') {
            return crc32($value);
        }
        if(isset($fields[$convertedKey]['INT']) && $fields[$convertedKey]['INT'] === true) {
            return (int)$value;
        }

        return $value;
    }

    public function indexKeyExists(Query $index, string $key): bool
    {
        return isset($index::FIELDS[$key]);
    }

    /**
     * @param array $params
     * @return array
     */
    public static function convertArray(array $params): array
    {
        $paramsConverted = [];
        foreach ($params as $key => $values) {
            $keyConverted = static::revertKey(static::convertKey($key), true);
            if(is_scalar($values)) {
                $paramsConverted[$keyConverted] = $values;
                continue;
            }
            $valuesConverted = [];
            foreach ($values as $valueKey => $value) {
                if ($valueKey === 'FROM' || $valueKey === 'TO') {
                    $valuesConverted[$valueKey] = $value;
                    continue;
                }
                $valuesConverted[] = $value;
            }
            $paramsConverted[$keyConverted] = $valuesConverted;
        }
        return $paramsConverted;
    }
}