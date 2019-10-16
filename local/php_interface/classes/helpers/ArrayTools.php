<?php
/**
 * @package ArrayTools
 * @author Oleg Makeev
 * @license MIT
 */

namespace ig\Helpers;


class ArrayTools
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
}