<?php

use ig\Datasource\Highload\PeriodPriceTable;
use ig\Datasource\Highload\PropertyValueTable;
use ig\Datasource\Property\Enum;
use ig\Datasource\Property\Scalar;
use ig\Datasource\Sphinx\CatalogGardenOffer;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->IncludeComponent(
    'ig:catalog',
    'garden',
    [
        'PAGE_ELEMENT_COUNT' => 12,
        'CACHE_FILTER' => 'Y',
        'CACHE_TIME' => '36000000',
        'CACHE_TYPE' => 'A',
        'IBLOCK_ID' => CATALOG_GARDEN_IBLOCK_ID,
        'IBLOCK_TYPE' => CATALOG_IBLOCK_TYPE,
        'SEF_FOLDER' => '/katalog/tovary-dlya-sada/',
        'SEF_URL_TEMPLATES' => [
            'compare' => 'compare.php?action=#ACTION_CODE#',
            'element' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
            'section' => '#SECTION_CODE_PATH#/',
            'sections' => '',
            'smart_filter' => '#SECTION_ID#/filter/#SMART_FILTER_PATH#/apply/',
        ],
        'PRICE_CODE' => [
            CATALOG_BASE_PRICE_CODE,
            CATALOG_ACTION_PRICE_CODE,
        ],
        'SPHINX_INDEX' => CatalogGardenOffer::class,
        'SPHINX_INDEX_EXCLUDE' => [
            'GROUP',
            'USAGE',
        ],
        'CUSTOM_DATA_CONVERTER' => '',
        /** Structure is ClassName=>[...values]. */
        'FILTER_SRC' => [
            PeriodPriceTable::class => [
                'CATALOG_PRICE_LIST',
            ],
            Enum::class => [
                'AVAILABLE',
                'RECOMMENDED',
            ],
            Scalar::class => [
                'USAGE',
            ],
        ],
        'FILTER_DISPLAY_PARAMS' => [

            'COMMON' => [
                'CATALOG_PRICE_LIST' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Цена',
                    'ALL' => 'Любая',
                    'CLASSES' => '',
                ],
                'AVAILABLE' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Наличие',
                    'ALL' => 'Любое',
                    'CLASSES' => '',
                ],
                'USAGE' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Использование',
                    'ALL' => 'Любое',
                    'RESETS' => '',
                ],
            ],
            'FOOTER' => [
                'RECOMMENDED' => [
                    'TEMPLATE' => 'checkbox',
                    'NAME' => 'Хиты сезона',
                    'CLASSES' => ' checkboxes--recommend',
                ],
            ],
        ],
    ]
);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';