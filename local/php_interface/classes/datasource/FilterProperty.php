<?php


namespace ig\Datasource;


interface FilterProperty
{
    /**
     * @param array $propertyCodes
     * @param array $params
     * @return array
     */
    public function getValues(array $propertyCodes, array $params = []): array;
}