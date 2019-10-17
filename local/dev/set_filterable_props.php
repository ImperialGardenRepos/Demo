<?php

use Bitrix\Iblock\PropertyTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
$filterablePropsCatalog = [
    'ADDITIONAL',
    'AROMA',
    'COLOR_BARK',
    'COLOR_FLOWER',
    'COLOR_FRUIT',
    'COLOR_LEAF',
    'COLOR_LEAF_AUTUMN',
    'CROWN',
    'FLOWERING',
    'FLOWER_SIGN',
    'GROUND',
    'GROUP',
    'HAIRCUT_SHAPE',
    'HEIGHT_10',
    'HEIGHT_10_EXT',
    'LIGHT',
    'RIPENING',
    'SHIRINA_KRONY_OLD',
    'TASTE',
    'USAGE',
    'WATER',
    'WATER_DEPTH',
    'ZONA_POSADKI',
];

//'CATALOG_PRICE_LIST',
//'COLOR'
$filterablePropsCatalogSKU = [
    'AVAILABLE',
    'MULTISTEMMED',
    'HEIGHT_NOW',
    'HEIGHT_NOW_EXT',

];

$propertiesCatalog = PropertyTable::query()
    ->setSelect(['ID', 'CODE'])
    ->addFilter('IBLOCK_ID', CATALOG_IBLOCK_ID)
    ->exec()
    ->fetchAll();
foreach ($propertiesCatalog as $property) {
    if (in_array($property['CODE'], $filterablePropsCatalog, true)) {
        PropertyTable::update($property['ID'],
            ['FILTRABLE' => 'Y']
        );
        continue;
    }
    PropertyTable::update($property['ID'],
        ['FILTRABLE' => 'N']
    );
}

$propertiesSku = PropertyTable::query()
    ->setSelect(['ID', 'CODE'])
    ->addFilter('IBLOCK_ID', CATALOG_SKU_IBLOCK_ID)
    ->exec()
    ->fetchAll();
foreach ($propertiesSku as $property) {
    if (in_array($property['CODE'], $filterablePropsCatalogSKU, true)) {
        PropertyTable::update($property['ID'],
            ['FILTRABLE' => 'Y']
        );
        continue;
    }
    PropertyTable::update($property['ID'],
        ['FILTRABLE' => 'N']
    );
}
