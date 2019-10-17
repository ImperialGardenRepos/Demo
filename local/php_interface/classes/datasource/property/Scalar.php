<?php

namespace ig\Datasource\Property;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CIBlockElement as CIBlockElementAlias;
use ig\Datasource\FilterProperty;

class Scalar implements FilterProperty
{
    /**
     * @param array $propertyCodes
     * @param array $params
     * @return array
     * @throws LoaderException
     */
    public function getValues(array $propertyCodes, array $params = []): array
    {
        $properties = [];
        foreach ($propertyCodes as $propertyCode) {
            $properties[] = "PROPERTY_{$propertyCode}";
        }
        $filter['IBLOCK_ID'] = $params['IBLOCK_ID'];

        Loader::includeModule('iblock');
        $propertyValueModel = CIBlockElementAlias::GetList(
            [],
            $filter,
            $properties,
            false,
            $properties
        );

        $result = [];
        while ($propertyValue = $propertyValueModel->Fetch()) {
            foreach ($propertyCodes as $propertyCode) {
                if ($propertyValue["PROPERTY_{$propertyCode}_VALUE"] === null) {
                    continue;
                }
                $result[$propertyCode]['VALUES'][$propertyValue["PROPERTY_{$propertyCode}_ENUM_ID"]] = [
                    'VALUE' => $propertyValue["PROPERTY_{$propertyCode}_ENUM_ID"],
                    'NAME' => $propertyValue["PROPERTY_{$propertyCode}_VALUE"],
                ];
            }
        }
        return $result;
    }
}