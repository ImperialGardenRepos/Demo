<?php


namespace ig\Datasource\Highload;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use ig\Datasource\FilterProperty;

class PeriodPriceTable extends DataManager implements FilterProperty
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return PERIOD_PRICE_HL_BLOCK_TABLE;
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
            new FloatField('UF_VALUE_FROM'),
            new FloatField('UF_VALUE_TO'),
            new StringField('UF_NAME'),
            new StringField('UF_XML_ID'),
            new IntegerField('UF_ACTIVE'),
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
        $valuesEntity = static::getList([
            'select' => [
                'UF_NAME',
                'UF_XML_ID',
            ],
            'filter' => [
                'UF_ACTIVE' => 1
            ],
        ]);
        $values = [];
        while ($valuesRow = $valuesEntity->fetch()) {
            $values[$valuesRow['UF_XML_ID']] = [
                'NAME' => $valuesRow['UF_NAME'],
                'VALUE' => $valuesRow['UF_XML_ID']
            ];
        }

        $result = [];
        foreach ($propertyCodes as $propertyCode) {
            $result[$propertyCode]['VALUES'] = $values;
        }
        return $result;
    }
}