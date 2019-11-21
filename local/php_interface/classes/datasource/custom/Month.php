<?php


namespace ig\Datasource\Custom;


use ig\Datasource\FilterProperty;
use ig\Helpers\DateTimeHelper;

class Month implements FilterProperty
{
    /**
     * @param array $propertyCodes
     * @param array $params
     * @return array
     */
    public function getValues(array $propertyCodes, array $params = []): array
    {
        $monthNames = DateTimeHelper::getMonthNames();
        $monthArray = [];
        foreach ($monthNames as $key => $monthName) {
            $monthArray[$key] = [
                'NAME' => $monthName,
                'VALUE' => $key,
            ];
        }
        $result = [];
        foreach ($propertyCodes as $propertyCode) {
            $result[$propertyCode]['VALUES']['FROM'] = $monthArray;
            $result[$propertyCode]['VALUES']['TO'] = $monthArray;
        }
        return $result;
    }
}