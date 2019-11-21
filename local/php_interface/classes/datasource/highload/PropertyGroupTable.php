<?php


namespace ig\Datasource\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;

class PropertyGroupTable extends DataManager
{

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return PROPERTY_GROUP_HL_BLOCK_TABLE;
    }

    /**
     * @return array
     * @throws ArgumentException
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
            new StringField('UF_NAME'),
            new IntegerField('UF_SORT'),
            new StringField('UF_TYPE'),
            new StringField('UF_CODE'),
            new StringField('UF_NAME_SHORT'),
            new IntegerField('UF_SVG'),
            new StringField('UF_HINT'),
            new IntegerField('UF_ACTIVE'),
            new IntegerField('UF_REQUIRED'),
            (new ManyToMany(
                'UF_POINTER',
                GroupTable::class
            ))->configureTableName(GROUP_HL_BLOCK_TABLE),
            new IntegerField('UF_FILTER'),

            (new OneToMany(
                'PROPERTY_VALUE',
                PropertyValueTable::class,
                'PROPERTY_GROUP')
            )->configureJoinType('inner')
        ];
    }
}