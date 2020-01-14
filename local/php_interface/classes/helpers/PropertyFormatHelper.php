<?php


namespace ig\Helpers;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\InvalidOperationException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\SystemException;
use ig\Datasource\Highload\ColorTable;

class PropertyFormatHelper
{
    private $params = [];

    public const VALUE_TYPE_UNSPECIFIED = 0;
    public const VALUE_TYPE_MONTH = 1;
    public const VALUE_TYPE_COLOR = 2;
    public const VALUE_TYPE_HL_BLOCK = 3;
    public const VALUE_TYPE_MULTI = 3;

    public const MONTH_RANGE_PROPERTIES = [
        'RIPENING',
        'FLOWERING',
    ];

    /** Formatter parameters must be unique integers */
    public const PARAM_POST_PROCESS_RANGE_DASH_REPLACE = 101;

    public const PARAM_POST_PROCESS_MAP = [
        self::PARAM_POST_PROCESS_RANGE_DASH_REPLACE,
    ];

    public function setFormatterParams(int ...$params): void
    {
        foreach ($params as $param) {
            if (!in_array($param, self::PARAM_POST_PROCESS_MAP, true)) {
                throw new ArgumentException("No parameter {$param} exist");
            }
        }
        $this->params = array_merge($params);
    }

    /**
     * @param array $property
     * @return int
     * @throws ArgumentNullException
     */
    public function getPropertyValueType(array $property): int
    {
        if (!isset($property['CODE']) || (bool)$property['CODE'] === false) {
            throw new ArgumentNullException('Property array wrong structure: required field CODE is absent');
        }
        if (in_array($property['CODE'], self::MONTH_RANGE_PROPERTIES, true)) {
            return static::VALUE_TYPE_MONTH;
        }
        if (strpos($property['CODE'], 'COLOR') !== false) {
            return static::VALUE_TYPE_COLOR;
        }
        if (isset($property['USER_TYPE']) && $property['USER_TYPE'] === 'directory') {
            return static::VALUE_TYPE_HL_BLOCK;
        }
        if (is_array($property['VALUE'])) {
            return static::VALUE_TYPE_MULTI;
        }
        return static::VALUE_TYPE_UNSPECIFIED;

    }

    /**
     * @param array $property
     * @return string|null
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws InvalidOperationException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function formatRandomTypeValue(array $property): ?string
    {
        $valueType = $this->getPropertyValueType($property);
        $value = $this->formatValueByType($property, $valueType);
        if ($value === null) {
            return null;
        }
        $this->postProcess($value);
        return $value;
    }

    public function postProcess(string &$value): void
    {
        if (in_array(self::PARAM_POST_PROCESS_RANGE_DASH_REPLACE, $this->params, true)) {
            //todo implement
        }
    }

    /**
     * @param array $property
     * @param int $valueType
     * @return string|null
     * @throws ArgumentException
     * @throws InvalidOperationException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function formatValueByType(array $property, int $valueType): ?string
    {

        switch ($valueType) {
            case static::VALUE_TYPE_MONTH:
                return $this->formatMonthValue($property['VALUE']);
            case static::VALUE_TYPE_COLOR:
                return $this->formatColorValue($property['VALUE']);
            case static::VALUE_TYPE_HL_BLOCK:
                return $this->formatHighLoadBlockValue($property['VALUE'], $property['USER_TYPE_SETTINGS']['TABLE_NAME']);
            case static::VALUE_TYPE_MULTI:
                return $this->formatMultipleValue($property['VALUE']);
            case static::VALUE_TYPE_UNSPECIFIED:
                return $property['VALUE'];
        }
    }

    /**
     * @param array $value
     * @return string|null
     */
    public function formatMonthValue(array $value): ?string
    {
        if (count($value) === 1) {
            return DateTimeHelper::getMonthName((int)reset($value));
        }
        sort($value);
        $monthNameMin = DateTimeHelper::getMonthName((int)min($value));
        $monthNameMax = DateTimeHelper::getMonthName((int)max($value));
        if ($monthNameMax === null || $monthNameMin === null) {
            return null;
        }
        return "{$monthNameMin}—{$monthNameMax}";
    }

    /**
     * @param array $value
     * @return string|null
     * @throws ArgumentException
     * @throws InvalidOperationException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function formatColorValue(array $value): ?string
    {
        $color = ColorTable::getByXmlId($value);
        if ($color === null) {
            return null;
        }
        return implode(' / ', array_column($color, 'NAME'));
    }

    /**
     * @param $value
     * @param string $tableName
     * @return string|null
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function formatHighLoadBlockValue($value, string $tableName): ?string
    {
        $className = null;

        if (isset(TABLE_DATA_CLASS_MAP[$tableName])) {
            $className = TABLE_DATA_CLASS_MAP[$tableName];
        }
        if ($className === null) {
            $className = HighLoadBlockHelper::getClassNameByTableName($tableName);
        }
        if ($className === null || !class_exists($className)) {
            throw new ArgumentException("No data class found for table {$tableName}");
        }

        /** @var DataManager $dataClass */
        $dataClass = new $className;
        $model = $dataClass::getList([
            'select' => ['UF_NAME'],
            'filter' => [
                'UF_XML_ID' => $value,
            ],
        ]);
        if ($model->getSelectedRowsCount() === 0) {
            return null;
        }
        return implode(' , ', array_column($model->fetchAll(), 'UF_NAME'));
    }

    /**
     * @param array $value
     * @return string|null
     */
    public function formatMultipleValue(array $value): ?string
    {
        return implode(',', $value);
    }
}