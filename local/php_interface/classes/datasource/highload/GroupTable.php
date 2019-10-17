<?php


namespace ig\Datasource\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use ig\Datasource\FilterProperty;

class GroupTable extends DataManager implements FilterProperty
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return GROUP_HL_BLOCK_TABLE;
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
            new StringField('UF_NAME'),
            new IntegerField('UF_SORT'),
            new StringField('UF_CODE'),
            new StringField('UF_NAME_SHORT'),
            new IntegerField('UF_PHOTO'),
            new StringField('UF_DESCRIPTION'),
            new StringField('UF_OLD_URL'),
            new StringField('UF_VIDEO'),
            new StringField('UF_FULL_DESCRIPTION'),
            new StringField('UF_XML_ID'),
            new IntegerField('UF_ACTIVE'),
            new StringField('UF_ICON'),
            (new ManyToMany(
                'UF_MAIN_PROP',
                PropertyGroupTable::class)
            )->configureTableName(PROPERTY_GROUP_HL_BLOCK_TABLE)
        ];
    }


    /**
     * @param array $propertyCodes
     * @param array $params
     * @return array
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    public function getValues(array $propertyCodes, array $params = []): array
    {
        $valuesArray = static::getList([
            'select' => [
                'UF_NAME',
                'UF_XML_ID',
                'UF_ICON'
            ],
            'filter' => [
                'UF_ACTIVE' => 1
            ],
        ])->fetchAll();
        $values = [];
        foreach ($valuesArray as $value) {
            $values[$value['UF_XML_ID']] = [
                'NAME' => $value['UF_NAME'],
                'ICON' => $value['UF_ICON'],
                'VALUE' => $value['UF_XML_ID'],
            ];
        }
        $result = [];
        foreach ($propertyCodes as $propertyCode) {
            $result[$propertyCode]['VALUES'] = $values;
        }
        return $result;
    }


    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getNames(): array
    {
        $groups = static::getList(
            [
                'select' => [
                    'UF_NAME',
                    'UF_XML_ID',
                ],
            ]
        );
        $result = [];
        while ($group = $groups->fetch()) {
            $result[$group['UF_XML_ID']] = $group['UF_NAME'];
        }
        return $result;
    }
}