<?php

namespace ig\Datasource\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use Exception;
use ig\Helpers\TableHelper;

class FilterAliasTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return FILTER_ALIAS_HL_BLOCK_TABLE;
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new StringField('UF_XML_ID'),
            new StringField('UF_REQUEST'),
            new IntegerField('UF_USE_CNT'),
            new IntegerField('UF_ALIAS_USE_CNT'),
        ];
    }

    /**
     * @param string $alias
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function isUniqueAliasExist(string $alias): bool
    {
        return (int)static::getCount(['UF_XML_ID' => $alias]) === 1;
    }

    /**
     * @param string $alias
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getRequestByAlias(string $alias): array
    {
        $request = static::getList([
            'select' => [
                'UF_REQUEST',
            ],
            'filter' => [
                'UF_XML_ID' => $alias,
            ],
        ])->fetch();

        if ($request === false) {
            return [];
        }

        return unserialize($request['UF_REQUEST'], ['allowed_classes' => false]);
    }

    /**
     * @param array $filterParams
     * @return string|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getByFilterParams(array $filterParams = []): ?string
    {
        if ($filterParams === []) {
            return null;
        }
        $paramString = serialize($filterParams);
        $alias = static::getList([
            'select' => [
                'UF_XML_ID',
            ],
            'filter' => [
                'UF_REQUEST' => $paramString,
            ],
        ])->fetch();
        if ($alias !== false) {
            return $alias['UF_XML_ID'];
        }
        return static::setNewFilterAlias($filterParams);
    }

    /**
     * @param array $filterParams
     * @return string
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    protected static function setNewFilterAlias(array $filterParams): string
    {
        $serializedParams = serialize($filterParams);
        $md5 = TableHelper::getUniqueValue(static::class, md5($serializedParams));
        static::add(
            [
                'UF_XML_ID' => $md5,
                'UF_REQUEST' => $serializedParams,
            ]
        );
        return $md5;
    }
}

