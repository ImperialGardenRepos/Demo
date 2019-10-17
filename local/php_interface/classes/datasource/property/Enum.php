<?php

namespace ig\Datasource\Property;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CIBlockPropertyEnum;
use ig\Datasource\FilterProperty;

class Enum implements FilterProperty
{
    /**
     * @param array $propertyCodes
     * @param array $params
     * @return array
     * @throws LoaderException
     */
    public function getValues(array $propertyCodes, array $params = []): array
    {
        $filter = ['PROPERTY_ID' => $propertyCodes];

        if ($params['IBLOCK_ID']) {
            $filter['IBLOCK_ID'] = $params['IBLOCK_ID'];
        }
        Loader::includeModule('iblock');
        $propertyValueModel = CIBlockPropertyEnum::GetList(
            [],
            $filter
        );
        $result = [];
        while ($propertyValue = $propertyValueModel->Fetch()) {
            $result[$propertyValue['PROPERTY_CODE']]['VALUES'][] = [
                'NAME' => $propertyValue['VALUE'],
                'VALUE' => $propertyValue['ID'],
            ];
        }
        return $result;
    }
}