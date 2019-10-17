<?php

namespace ig\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class FilterAlias extends Base
{
    protected const BLOCK_NAME = 'CatalogFilterAlias';

    /**
     * @param string $alias
     * @return bool
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function isUniqueAliasExist(string $alias): bool
    {
        $entity = static::getEntity();
        $model = $entity::query()
            ->where('UF_XML_ID', '=', $alias)
            ->setSelect([
                'COUNT(*)'
            ])
            ->exec();
        $result = [];
        $row = $model->fetch();
        $row = array_shift($row);
        if($row === '1') {
            return true;
        }
        return false;
    }
}