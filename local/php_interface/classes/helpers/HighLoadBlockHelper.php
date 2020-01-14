<?php


namespace ig\Helpers;


use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\SystemException;

class HighLoadBlockHelper
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

    /**
     * @param string $tableName
     * @return string|null
     * @throws LoaderException
     * @throws SystemException
     */
    public static function getClassNameByTableName(string $tableName): ?string
    {
        Loader::includeModule('highloadblock');
        if (!empty($tableName)) {
            $model = HighloadBlockTable::getList([
                'select' => [
                    'ID'
                ],
                'filter' => [
                    'TABLE_NAME' => $tableName
                ]
            ]);
            if($model->getSelectedRowsCount() !== 1) {
                return null;
            }
            $row = $model->fetch();
            $id = $row['ID'];

            /** @var Entity $entity */
            $entity = HighloadBlockTable::compileEntity($id);

            return $entity->getDataClass();
        }
        return null;
    }
}