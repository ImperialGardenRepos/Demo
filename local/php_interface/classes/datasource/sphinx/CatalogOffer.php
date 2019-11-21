<?php


namespace ig\Datasource\Sphinx;

class CatalogOffer extends Query
{
    protected static $indexName = CATALOG_OFFERS_INDEX;

    public static $fields = [
        'id' => [],
        'name' => ['MATCH' => 'Y'],
        'cat_section_1' => ['MATCH' => 'Y'],
        'cat_section_2' => ['MATCH' => 'Y'],
        'cat_id' => [],
        'p_name_lat' => ['MATCH' => 'Y'],
        'p_group' => ['FILTER' => 'Y', 'MATCH' => 'Y'],
        'p_usage' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y'],
        'p_crown' => ['FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_multistemmed' => ['FILTER' => 'Y'],
        'p_haircut_shape' => ['FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_light' => ['FILTER' => 'Y'],
        'p_water' => ['FILTER' => 'Y'],
        'p_ground' => ['FILTER' => 'Y'],
        'p_zona_posadki' => ['FILTER' => 'Y'],
        'p_flowering' => ['MULTIPLE' => 'Y', 'FILTER' => 'Y'],
        'p_ripening' => ['MULTIPLE' => 'Y', 'FILTER' => 'Y'],
        'p_taste' => ['FILTER' => 'Y'],
        'p_color_fruit' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_color_flower' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_color_leaf' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_color_leaf_autumn' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_color_bark' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_additional' => ['MULTIPLE' => 'Y', 'CRC32' => 'Y', 'FILTER' => 'Y', 'CONTROL' => 'CHECKBOX'],
        'p_height_now' => ['FILTER' => 'Y'],
        'p_height_10' => ['FILTER' => 'Y'],
        'p_recommended' => ['FILTER' => 'Y', 'INT' => true],
        'p_priority' => [],
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