<?php


namespace ig\Helpers;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class TableHelper
{

    /* @param string $table
     * @param string $md5
     * @param string $field
     * @return string
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getUniqueValue(string $table, string $md5, string $field = 'UF_XML_ID'): string
    {
        /** @var DataManager $table */
        $isUnique = $table::getList([
                'select' => [$field],
                'filter' => [$field => $md5],
            ])->fetch() === false;
        if ($isUnique !== true) {
            $md5 = static::getUniqueValue($table, md5($md5), $field);
        }
        return $md5;
    }
}