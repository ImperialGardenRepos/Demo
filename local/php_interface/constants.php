<?php
/**
 * Paths
 * Notice: always use trailing slash here except site template
 */

use ig\Datasource\Highload\ColorTable;
use ig\Datasource\Highload\FilterAliasTable;
use ig\Datasource\Highload\GroupTable;
use ig\Datasource\Highload\PeriodHeightExt;
use ig\Datasource\Highload\PeriodHeightNowExt;
use ig\Datasource\Highload\PeriodHeightNowTable;
use ig\Datasource\Highload\PeriodHeightTable;
use ig\Datasource\Highload\PeriodPriceTable;
use ig\Datasource\Highload\PropertyGroupTable;
use ig\Datasource\Highload\PropertyValueTable;
use ig\Datasource\Highload\Redirect;
use ig\Datasource\Highload\VirtualPageTable;

defined('SITE_TEMPLATE_PATH') or define('SITE_TEMPLATE_PATH', '/local/templates/ig');
defined('SITE_BASE_DIR') or define('SITE_BASE_DIR', '/var/www/imperialgarden.ru/');
defined('CATALOG_BASE_PATH') or define('CATALOG_BASE_PATH', '/katalog/rasteniya/');
defined('CATALOG_GARDEN_BASE_PATH') or define('CATALOG_GARDEN_BASE_PATH', '/katalog/tovary-dlya-sada/');
defined('CATALOG_PRICE_LIST_PATH') or define('CATALOG_PRICE_LIST_PATH', '/price-lists/');
/**''
 * Prices
 */
defined('CATALOG_BASE_PRICE_CODE') or define('CATALOG_BASE_PRICE_CODE', 'BASE');
defined('CATALOG_ACTION_PRICE_CODE') or define('CATALOG_ACTION_PRICE_CODE', 'ACTION');
defined('CATALOG_BASE_PRICE_ID') or define('CATALOG_BASE_PRICE_ID', 2);
defined('CATALOG_ACTION_PRICE_ID') or define('CATALOG_ACTION_PRICE_ID', 3);
/**
 * IBlock types
 */
defined('SERVICE_IBLOCK_TYPE') or define('SERVICE_IBLOCK_TYPE', 'services');
defined('CATALOG_IBLOCK_TYPE') or define('CATALOG_IBLOCK_TYPE', 'catalog');
/**
 * IBlock IDs
 */
defined('CATALOG_IBLOCK_ID') or define('CATALOG_IBLOCK_ID', 1);
defined('CATALOG_SKU_IBLOCK_ID') or define('CATALOG_SKU_IBLOCK_ID', 2);
defined('NEWS_IBLOCK_ID') or define('NEWS_IBLOCK_ID', 5);
defined('SERVICES_IBLOCK_ID') or define('SERVICES_IBLOCK_ID', 6);
defined('FAQ_IBLOCK_ID') or define('FAQ_IBLOCK_ID', 14);
defined('CATALOG_GARDEN_IBLOCK_ID') or define('CATALOG_GARDEN_IBLOCK_ID', 17);
defined('ENTERTAINMENT_IBLOCK_ID') or define('ENTERTAINMENT_IBLOCK_ID', 20);
defined('TEAM_IBLOCK_ID') or define('TEAM_IBLOCK_ID', 21);
defined('GRATITUDE_IBLOCK_ID') or define('GRATITUDE_IBLOCK_ID', 22);
defined('REVIEWS_IBLOCK_ID') or define('REVIEWS_IBLOCK_ID', 23);
defined('PARTNERS_IBLOCK_ID') or define('PARTNERS_IBLOCK_ID', 24);
defined('PORTFOLIO_IBLOCK_ID') or define('PORTFOLIO_IBLOCK_ID', 25);
defined('VEHICLE_RENT_IBLOCK_ID') or define('VEHICLE_RENT_IBLOCK_ID', 33);
defined('CONSTRUCTOR_IBLOCK_ID') or define('CONSTRUCTOR_IBLOCK_ID', 34);
/**
 * HighLoad block tables
 */
defined('FILTER_ALIAS_HL_BLOCK_TABLE') or define('FILTER_ALIAS_HL_BLOCK_TABLE', 'ig_catalog_filter_alias');
defined('COLOR_HL_BLOCK_TABLE') or define('COLOR_HL_BLOCK_TABLE', 'ig_colors');
defined('GROUP_HL_BLOCK_TABLE') or define('GROUP_HL_BLOCK_TABLE', 'ig_group');
defined('PERIOD_HEIGHT_HL_BLOCK_TABLE') or define('PERIOD_HEIGHT_HL_BLOCK_TABLE', 'ig_period_height');
defined('PERIOD_HEIGHT_EXT_HL_BLOCK_TABLE') or define('PERIOD_HEIGHT_EXT_HL_BLOCK_TABLE', 'ig_period_height_ext');
defined('PERIOD_HEIGHT_NOW_HL_BLOCK_TABLE') or define('PERIOD_HEIGHT_NOW_HL_BLOCK_TABLE', 'ig_period_height_now');
defined('PERIOD_HEIGHT_NOW_EXT_HL_BLOCK_TABLE') or define('PERIOD_HEIGHT_NOW_EXT_HL_BLOCK_TABLE', 'ig_period_height_now_ext');
defined('PERIOD_PRICE_HL_BLOCK_TABLE') or define('PERIOD_PRICE_HL_BLOCK_TABLE', 'ig_period_price');
defined('PROPERTY_GROUP_HL_BLOCK_TABLE') or define('PROPERTY_GROUP_HL_BLOCK_TABLE', 'ig_propertygroup');
defined('PROPERTY_VALUE_HL_BLOCK_TABLE') or define('PROPERTY_VALUE_HL_BLOCK_TABLE', 'ig_propertyvalues');
defined('REDIRECT_HL_BLOCK_TABLE') or define('REDIRECT_HL_BLOCK_TABLE', 'ig_redirect');
defined('VIRTUAL_PAGE_HL_BLOCK_TABLE') or define('VIRTUAL_PAGE_HL_BLOCK_TABLE', 'ig_virtula_pages');

defined('TABLE_DATA_CLASS_MAP') or define(
    'TABLE_DATA_CLASS_MAP',
    [
        FILTER_ALIAS_HL_BLOCK_TABLE => FilterAliasTable::class,
        COLOR_HL_BLOCK_TABLE => ColorTable::class,
        GROUP_HL_BLOCK_TABLE => GroupTable::class,
        PERIOD_HEIGHT_HL_BLOCK_TABLE => PeriodHeightTable::class,
        PERIOD_HEIGHT_EXT_HL_BLOCK_TABLE => PeriodHeightExt::class,
        PERIOD_HEIGHT_NOW_HL_BLOCK_TABLE => PeriodHeightNowTable::class,
        PERIOD_HEIGHT_NOW_EXT_HL_BLOCK_TABLE => PeriodHeightNowExt::class,
        PERIOD_PRICE_HL_BLOCK_TABLE => PeriodPriceTable::class,
        PROPERTY_GROUP_HL_BLOCK_TABLE => PropertyGroupTable::class,
        PROPERTY_VALUE_HL_BLOCK_TABLE => PropertyValueTable::class,
        REDIRECT_HL_BLOCK_TABLE => Redirect::class,
        VIRTUAL_PAGE_HL_BLOCK_TABLE => VirtualPageTable::class,
    ]
);
/**
 * Sphinx tables
 */
defined('CATALOG_OFFERS_INDEX') or define('CATALOG_OFFERS_INDEX', 'catalog_offers');
defined('CATALOG_GARDEN_OFFERS_INDEX') or define('CATALOG_GARDEN_OFFERS_INDEX', 'catalog_garden_offers');
/**
 * Property codes
 */
defined('CATALOG_PHOTO_PROPERTIES') or define(
    'CATALOG_PHOTO_PROPERTIES',
    ['PHOTO_WINTER', 'PHOTO_AUTUMN', 'PHOTO_SUMMER', 'PHOTO_10YEARS', 'PHOTO_FLOWER', 'PHOTO_FRUIT']
);
defined('CATALOG_DESCRIPTION_PROPERTIES') or define(
    'CATALOG_DESCRIPTION_PROPERTIES',
    ['HEIGHT_10', 'CROWN', 'FLOWERING', 'COLOR_LEAF', 'PERIOD_PRICE']
);