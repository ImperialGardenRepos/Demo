<?php


namespace ig\Datasource\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use ig\Datasource\FilterProperty;

class PropertyValueTable extends DataManager implements FilterProperty
{

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return PROPERTY_VALUE_HL_BLOCK_TABLE;
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
            new StringField('UF_PROPERTY'),
            new Reference(
                'PROPERTY_GROUP',
                PropertyGroupTable::class,
                Join::on('this.UF_PROPERTY', 'ref.ID')
            ),
            new IntegerField('UF_SORT'),
            new StringField('UF_DESCRIPTION'),
            new StringField('UF_FULL_DESCRIPTION'),
            new StringField('UF_XML_ID'),
            (new ManyToMany(
                'UF_POINTER',
                GroupTable::class
            ))->configureTableName(GROUP_HL_BLOCK_TABLE),
            new StringField('UF_ICON'),
            new IntegerField('UF_ACTIVE'),
        ];
    }

    /**
     * @param array $propertyCodes
     * @param array $params
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getValues(array $propertyCodes, array $params = []): array
    {
        $valuesArray = static::getList([
            'select' => [
                '*',
                'PROP_GROUP_' => 'PROPERTY_GROUP'
            ],
            'filter' => [
                '!UF_PROPERTY' => null
            ],
        ])->fetchAll();
        $result = [];
        foreach ($valuesArray as $value) {
            $result[$value['PROP_GROUP_UF_CODE']]['VALUES'][$value['UF_XML_ID']] = [
                'NAME' => $value['UF_NAME'],
                'ICON' => $value['UF_ICON'],
                'VALUE' => $value['UF_XML_ID'],
            ];
        }
        return $result;
    }
}