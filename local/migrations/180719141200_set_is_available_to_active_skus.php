<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ($USER->GetID() !== '1') {
    die();
}

const PLANT_SKU_IBLOCK_ID = 2;
const PLANT_SKU_IBLOCK_PROPERTY_AVAILABLE_CODE = 'AVAILABLE';
const PLANT_SKU_IBLOCK_PROPERTY_AVAILABLE_IS_AVAILABLE_VALUE_ID = 3;

const GARDEN_SKU_IBLOCK_ID = 18;
const GARDEN_SKU_IBLOCK_PROPERTY_AVAILABLE_CODE = 'AVAILABLE';
const GARDEN_SKU_IBLOCK_PROPERTY_AVAILABLE_IS_AVAILABLE_VALUE_ID = 87;

\Bitrix\Main\Loader::includeModule('iblock');
$iBlockElement = new CIBlockElement();

/**
 * Get active SKU Ids from plant catalog and set them available
 */
$activeElementsRes = $iBlockElement->GetList(
    [],
    [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => PLANT_SKU_IBLOCK_ID
    ],
    false,
    false,
    [
        'ID',
        'IBLOCK_ID'
    ]
);
while ($activeElement = $activeElementsRes->Fetch()) {
    $iBlockElement->SetPropertyValues(
        $activeElement['ID'],
        PLANT_SKU_IBLOCK_ID,
        [PLANT_SKU_IBLOCK_PROPERTY_AVAILABLE_IS_AVAILABLE_VALUE_ID],
        PLANT_SKU_IBLOCK_PROPERTY_AVAILABLE_CODE
    );
}

/**
 * Get active SKU Ids from plant catalog and set them available
 */
$activeElementsRes = $iBlockElement->GetList(
    [],
    [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => GARDEN_SKU_IBLOCK_ID
    ],
    false,
    false,
    [
        'ID',
        'IBLOCK_ID'
    ]
);
while ($activeElement = $activeElementsRes->Fetch()) {
    $iBlockElement->SetPropertyValues(
        $activeElement['ID'],
        GARDEN_SKU_IBLOCK_ID,
        [GARDEN_SKU_IBLOCK_PROPERTY_AVAILABLE_IS_AVAILABLE_VALUE_ID],
        GARDEN_SKU_IBLOCK_PROPERTY_AVAILABLE_CODE
    );
}