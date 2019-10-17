<?php
/**
 * @package ArrayTools
 * @author Oleg Makeev
 * @license MIT
 */

namespace ig\Helpers;


use Bitrix\Main\InvalidOperationException;

class ArrayHelper
{
    /**
     * @param array $array
     * @param bool $skipZero
     * @return null|float
     */
    public static function getMinValue(array $array, $skipZero = true): ?float
    {
        if ($array === []) {
            return 0;
        }
        $array = array_values($array);
        sort($array, SORT_NUMERIC);
        if ($skipZero) {
            foreach ($array as $value) {
                if ((int)$value > 0) {
                    return $value;
                }
            }
            return null;
        }
        return (float)$array[0];
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     * @throws InvalidOperationException
     */
    public static function removeKeyPrefix(array $array, string $prefix = 'UF_'): array
    {
        $inputKeys = array_keys($array);
        $resultArray = [];
        foreach ($array as $key => $value) {
            $newKey = preg_replace("/^{$prefix}/", '', $key);
            if ($newKey !== $key && in_array($newKey, $inputKeys, true)) {
                throw new InvalidOperationException("Key {$key} already exists in array");
            }
            $resultArray[$newKey] = $value;
        }
        return $resultArray;
    }
}