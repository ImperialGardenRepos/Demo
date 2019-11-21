<?php

use ig\Datasource\Custom\Month;
use ig\Datasource\Highload\ColorTable;
use ig\Datasource\Highload\GroupTable;
use ig\Datasource\Highload\PeriodHeightNowTable;
use ig\Datasource\Highload\PeriodHeightTable;
use ig\Datasource\Highload\PeriodPriceTable;
use ig\Datasource\Highload\PropertyValueTable;
use ig\Datasource\Property\Enum;
use ig\Datasource\Sphinx\CatalogOffer;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->IncludeComponent(
    'ig:catalog',
    '',
    [
        'PAGE_ELEMENT_COUNT' => 12,
        'CACHE_FILTER' => 'Y',
        'CACHE_TIME' => '36000000',
        'CACHE_TYPE' => 'A',
        'IBLOCK_ID' => CATALOG_IBLOCK_ID,
        'IBLOCK_TYPE' => CATALOG_IBLOCK_TYPE,
        'SEF_FOLDER' => '/katalog/rasteniya/',
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
        'SPHINX_INDEX' => CatalogOffer::class,
        'SPHINX_INDEX_EXCLUDE' => [
            'GROUP',
            'USAGE',
        ],
        'CUSTOM_DATA_CONVERTER' => '',
        /** Structure is ClassName=>[...values]. */
        'FILTER_SRC' => [
            PropertyValueTable::class => [
                'USAGE',
                'CROWN',
                'HAIRCUT_SHAPE',
                'LIGHT',
                'WATER',
                'GROUND',
                'TASTE',
                'ADDITIONAL',
                'ZONA_POSADKI',
            ],
            ColorTable::class => [
                'COLOR_FLOWER',
                'COLOR_FRUIT',
                'COLOR_LEAF',
                'COLOR_LEAF_AUTUMN',
                'COLOR_BARK',
            ],
            GroupTable::class => [
                'GROUP',
            ],
            PeriodHeightNowTable::class => [
                'HEIGHT_NOW',
            ],
            PeriodHeightTable::class => [
                'HEIGHT_10',
            ],
            PeriodPriceTable::class => [
                'CATALOG_PRICE_LIST',
            ],
            Enum::class => [
                'AVAILABLE',
                'RECOMMENDED',
                'MULTISTEMMED',
            ],
            Month::class => [
                'FLOWERING',
                'RIPENING',
            ],
        ],
        'FILTER_DISPLAY_PARAMS' => [
            'HEADER' => [
                'GROUP' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Группа растений',
                    'ALL' => 'Любая',
                    'RESETS' => '#ddbox-usage, #ddbox-query, .js-filter-section-inputs',
                ],
                'USAGE' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Использование',
                    'ALL' => 'Любое',
                    'RESETS' => '',
                ],

            ],
            'COMMON' => [
                'CATALOG_PRICE_LIST' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Цена',
                    'ALL' => 'Любая',
                    'CLASSES' => ' ddbox--wide',
                ],
                'AVAILABLE' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Наличие',
                    'ALL' => 'Любое',
                    'CLASSES' => ' ddbox--wide',
                ],
                'ADDITIONAL' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Дополнительно',
                    'ALL' => 'Выберите значение',
                    'CLASSES' => ' ddbox--wide',
                    'TYPE' => 'checkbox',
                ],
                'MULTISTEMMED' => [
                    'TEMPLATE' => 'checkbox',
                    'NAME' => 'Многоствольное',
                    'CLASSES' => ' checkboxes--multistem',
                ],
            ],
            'OUTFIT' => [
                'HEIGHT_NOW' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Высота сейчас (м)',
                    'ALL' => 'Любая',
                ],
                'HEIGHT_10' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Высота взрослого растения (м)',
                    'ALL' => 'Любая',
                ],
                'CROWN' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Форма кроны',
                    'ALL' => 'Любая',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
                'HAIRCUT_SHAPE' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Форма стрижки',
                    'ALL' => 'Любая',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
            ],
            'ECOLOGY' => [
                'LIGHT' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Свет',
                    'ALL' => 'Любой',
                ],
                'WATER' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Влага',
                    'ALL' => 'Любая',
                    'CLASSES' => ' ddbox--wide',
                ],
                'GROUND' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Почва',
                    'ALL' => 'Любая',
                ],
                'ZONA_POSADKI' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Зона посадки водных',
                    'ALL' => 'Любая',
                ],
            ],
            'RIPENING_1' => [
                'COLOR_FLOWER' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Цвет цветков (шишки)',
                    'ALL' => 'Любой',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
                'COLOR_LEAF' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Цвет листьев (хвои)',
                    'ALL' => 'Любой',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
                'COLOR_FRUIT' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Цвет плодов',
                    'ALL' => 'Любой',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
                'COLOR_LEAF_AUTUMN' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Осенняя окраска',
                    'ALL' => 'Любая',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
            ],
            'RIPENING_2' => [
                'COLOR_BARK' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Цвет коры',
                    'ALL' => 'Любой',
                    'TYPE' => 'checkbox',
                    'CLASSES' => ' ddbox--wide',
                ],
                'FLOWERING' => [
                    'TEMPLATE' => 'range',
                    'NAME' => 'Период цветения',
                ],
                'RIPENING' => [
                    'TEMPLATE' => 'range',
                    'NAME' => 'Период созревания плодов',
                ],
                'TASTE' => [
                    'TEMPLATE' => 'dropdown',
                    'NAME' => 'Вкус плодов',
                    'ALL' => 'Любой',
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