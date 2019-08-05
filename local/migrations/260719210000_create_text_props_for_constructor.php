<?php

use Bitrix\Main\Loader as LoaderAlias;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if ($USER->GetID() !== '1') {
    die();
}

LoaderAlias::includeModule('iblock');

$constructorIBlock = CIBlock::GetList(['ID' => 'ASC'], ['CODE' => 'constructor'])->Fetch();
$iBlockId = $constructorIBlock['ID'];

$sortPrefix = 2000;

$iBlockProperty = new CIBlockProperty();


$properties = [

    [
        'NAME' => 'Сортировка блока текста',
        "ACTIVE" => "Y",
        "SORT" => 10,
        "CODE" => "TEXT_SORT",
        "DEFAULT_VALUE" => 100,
        "PROPERTY_TYPE" => "N",
        "IBLOCK_ID" => $iBlockId
    ],
    [
        'NAME' => 'Активность блока текста',
        "ACTIVE" => "Y",
        "SORT" => 20,
        "CODE" => "TEXT_ACTIVE",
        "PROPERTY_TYPE" => "N",
        "DEFAULT_VALUE" => 0,
        "IBLOCK_ID" => $iBlockId,
        "USER_TYPE" => 'SASDCheckboxNum'
    ],
    [
        'NAME' => 'Текстовый блок',
        "ACTIVE" => "Y",
        "SORT" => 30,
        "CODE" => "TEXT_TEXT",
        "PROPERTY_TYPE" => "S",
        "IBLOCK_ID" => $iBlockId,
        "USER_TYPE" => 'HTML'
    ],
    [
        'NAME' => 'Изображение для текстового блока',
        "ACTIVE" => "Y",
        "SORT" => 40,
        "CODE" => "TEXT_IMAGE",
        "PROPERTY_TYPE" => "F",
        "IBLOCK_ID" => $iBlockId
    ],
    [
        'NAME' => 'Обтекание изображения в текстовом блоке',
        "ACTIVE" => "Y",
        "SORT" => 50,
        "CODE" => "TEXT_IMAGE_FLOW",
        "PROPERTY_TYPE" => "L",
        "LIST_TYPE" => "L",
        "VALUES" => [
            [
                'VALUE' => 'По центру',
                'DEF' => 'Y',
                'XML_ID' => 'flow-center',
                'SORT' => 100

            ],
            [
                'VALUE' => 'Слева',
                'DEF' => 'N',
                'XML_ID' => 'flow-left',
                'SORT' => 200

            ],
            [
                'VALUE' => 'Справа',
                'DEF' => 'N',
                'XML_ID' => 'flow-right',
                'SORT' => 300

            ],
            [
                'VALUE' => 'Растянуть',
                'DEF' => 'N',
                'XML_ID' => 'flow-stretch',
                'SORT' => 400

            ],
        ],
        "IBLOCK_ID" => $iBlockId
    ],
    [
        'NAME' => 'Максимальная ширина изображения (для обтекаемых изображений)',
        "ACTIVE" => "Y",
        "SORT" => 60,
        "CODE" => "TEXT_IMAGE_WIDTH",
        "PROPERTY_TYPE" => "S",
        "IBLOCK_ID" => $iBlockId,
    ],

];
for ($i = 1; $i <= 5; $i++) {
    foreach ($properties as $property) {
        $property['NAME'] .= ' ' . $i;
        $property['SORT'] += $sortPrefix + $i * 100;
        $property['CODE'] .= '_' . $i;
        $result = $iBlockProperty->Add($property);
        if ($result === false) {
            print_r($property);
            die('failed to create property');
        }
    }
}
echo 'Text properties created';