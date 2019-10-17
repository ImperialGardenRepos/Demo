<?php


namespace ig\Datasource\Sphinx;

class CatalogGardenOffer extends Query
{
    protected static $indexName = CATALOG_GARDEN_OFFERS_INDEX;

    public static $fields = [
        'id' => [],
        'name' => ['MATCH' => 'Y'],
        'cat_section_1' => ['MATCH' => 'Y'],
        'cat_section_2' => ['MATCH' => 'Y'],
        'cat_id' => [],
        'p_usage' => ['FILTER' => 'Y', 'INT' => true],
        'p_brand' => ['FILTER' => 'Y'],
        'p_recommended' => ['FILTER' => 'Y', 'INT' => true],
        'p_available' => ['FILTER' => 'Y'],
        'catalog_price_list' => ['FILTER' => 'Y'],
        'catalog_base_price' => [],
        'catalog_action_price' => [],
        'p_new_date_end' => [],
        'p_new' => [],
        'p_min_price' => [],
        'p_sort' => [],
        'p_full_name' => [],
        'p_link' => [],
    ];
}