<?php

use Bitrix\Main\Loader as LoaderAlias;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if ($USER->GetID() !== '1') {
    die();
}

LoaderAlias::includeModule('iblock');

$constructorIBlock = CIBlock::GetList(['ID' => 'ASC'], ['CODE' => 'constructor'])->Fetch();
$iBlockId = $constructorIBlock['ID'];

$sortPrefix = 1000;

$iBlockProperty = new CIBlockProperty();


$properties = [

    [
        'NAME' => 'Сортировка блока',
        "ACTIVE" => "Y",
        "SORT" => 10,
        "CODE" => "BANNER_SORT",
        "DEFAULT_VALUE" => 100,
        "PROPERTY_TYPE" => "N",
        "IBLOCK_ID" => $iBlockId
    ],
    [
        'NAME' => 'Активность блока',
        "ACTIVE" => "Y",
        "SORT" => 20,
        "CODE" => "BANNER_ACTIVE",
        "PROPERTY_TYPE" => "N",
        "DEFAULT_VALUE" => 0,
        "IBLOCK_ID" => $iBlockId,
        "USER_TYPE" => 	'SASDCheckboxNum'
    ],
    [
        'NAME' => 'Заголовок на баннере',
        "ACTIVE" => "Y",
        "SORT" => 30,
        "CODE" => "BANNER_HEADING",
        "PROPERTY_TYPE" => "S",
        "IBLOCK_ID" => $iBlockId
    ],
    [
        'NAME' => 'Текст на баннере',
        "ACTIVE" => "Y",
        "SORT" => 40,
        "CODE" => "BANNER_TEXT",
        "PROPERTY_TYPE" => "S",
        "IBLOCK_ID" => $iBlockId,
        "USER_TYPE" => 'HTML'
    ],
    [
        'NAME' => 'Изображение (баннер)',
        "ACTIVE" => "Y",
        "SORT" => 50,
        "CODE" => "BANNER_IMAGE",
        "PROPERTY_TYPE" => "F",
        "IBLOCK_ID" => $iBlockId
    ],
//    [
//        'NAME' => 'Текст на кнопке баннера',
//        "ACTIVE" => "Y",
//        "SORT" => 60,
//        "CODE" => "BANNER_BUTTON_TEXT",
//        "PROPERTY_TYPE" => "S",
//        "IBLOCK_ID" => $iBlockId
//    ],
//    [
//        'NAME' => 'Ссылка с баннера',
//        "ACTIVE" => "Y",
//        "SORT" => 70,
//        "CODE" => "BANNER_LINK",
//        "PROPERTY_TYPE" => "S",
//        "IBLOCK_ID" => $iBlockId
//    ],
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
echo 'Banner properties created';