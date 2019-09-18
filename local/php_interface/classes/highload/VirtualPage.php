<?php

namespace ig\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class VirtualPage extends Base
{
    protected const BLOCK_NAME = 'VirtualPages';

    public static $intHLID = 0;

    //ToDo:: get rid of this

    public static function setHLID(): void
    {
        static::$intHLID = static::getHighloadBlockIDByName(static::BLOCK_NAME);
    }

    /**
     * @param $url
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getMetaByUrl($url): array
    {
        if (is_scalar($url)) {
            $url = [$url];
        }
        $entity = static::getEntity();
        $model = $entity::query()
            ->where('UF_ACTIVE', '=', '1')
            ->whereIn('UF_URL', $url)
            ->setSelect([
                'UF_TEXT',
                'UF_DESCRIPTION',
                'UF_TITLE',
                'UF_H1',
                'UF_URL'
            ])
            ->exec();
        $result = [];
        while ($row = $model->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
}