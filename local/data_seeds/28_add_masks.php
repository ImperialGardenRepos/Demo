<?php

use ig\Datasource\Highload\VirtualPageTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;

if (!$USER->IsAdmin()) {
    exit();
}

$data = [
    [
        'UF_NAME' => 'Товары для сада - верхний уровень',
        'UF_URL' => '/katalog/tovary-dlya-sada/',
        'UF_DESCRIPTION' => 'В продаже {baseTitle.lc} по выгодной цене от {minPrice} рублей в наличии в каталоге товаров для сада на сайте садового центра Imperial Garden',
        'UF_H1' => '{baseTitle.lc.ucfirst}',
        'UF_TITLE' => '{baseTitle.lc.ucfirst} в наличии на сайте Imperial Garden',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Товары для сада - раздел',
        'UF_URL' => '/katalog/tovary-dlya-sada/#/',
        'UF_DESCRIPTION' => 'В продаже {baseTitle.lc} по выгодной цене от {minPrice} рублей в наличии в каталоге товаров для сада на сайте садового центра Imperial Garden',
        'UF_TITLE' => '{baseTitle.lc.ucfirst} в наличии на сайте Imperial Garden',
        'UF_H1' => '{baseTitle.lc.ucfirst}',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Товары для сада - подраздел',
        'UF_URL' => '/katalog/tovary-dlya-sada/#/#/',
        'UF_DESCRIPTION' => 'В продаже {baseTitle.lc} по выгодной цене от {minPrice} рублей в наличии в каталоге товаров для сада на сайте садового центра Imperial Garden',
        'UF_H1' => '{baseTitle.lc.ucfirst}',
        'UF_TITLE' => '{baseTitle.lc.ucfirst} в наличии на сайте Imperial Garden',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Товары для сада - детально',
        'UF_URL' => '/katalog/tovary-dlya-sada/#/#/#/',
        'UF_DESCRIPTION' => 'В продаже {baseTitle.lc} по выгодной цене от {minPrice} рублей в наличии в каталоге товаров для сада на сайте садового центра Imperial Garden',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Многолетние растения',
        'UF_URL' => '/katalog/rasteniya/mnogoletnie-rasteniya/',
        'UF_DESCRIPTION' => 'В продаже многолетние растения по выгодной стоимости от 300 рублей в садовом центре Imperial Garden на Новой Риге, Московская область. Многолетники для посадки по лучшей цене!',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Однолетние растения',
        'UF_URL' => '/katalog/rasteniya/odnoletnie-rasteniya/',
        'UF_DESCRIPTION' => 'В продаже однолетние растения по выгодной стоимости от 300 рублей в садовом центре Imperial Garden на Новой Риге, Московская область. Однолетники для посадки по лучшей цене!',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Парки в Москве',
        'UF_URL' => '/portfolio/obekty-v-moskve/parki/',
        'UF_DESCRIPTION' => 'Парки в Москве',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Парки в регионах',
        'UF_URL' => '/portfolio/regionalnye-obekty/parki_2/',
        'UF_DESCRIPTION' => 'Парки в регионах',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Пляж Солнечный',
        'UF_URL' => '/info/novosti/2017/06/plyazh-solnechnyy-otkryt-dlya-posetiteley/',
        'UF_DESCRIPTION' => 'Совсем недавно мы рассказывали вам о том, что Imperial Garden выпала честь возвратить к жизни парк имени Анны Ахматовой в Севастополе, пляж Солнечный и всю находящуюся на этой территории инфраструктуру. (июнь) Подробнее на сайте…',
        'UF_ACTIVE' => 1,
    ],
    [
        'UF_NAME' => 'Реконструкция парка Ахматовой',
        'UF_URL' => '/info/novosti/2017/09/rekonstruktsiya-parka-akhmatovoy-v-sevastopole/',
        'UF_DESCRIPTION' => 'Совсем недавно мы рассказывали вам о том, что Imperial Garden выпала честь возвратить к жизни парк имени Анны Ахматовой в Севастополе, пляж Солнечный и всю находящуюся на этой территории инфраструктуру. (сентябрь) Подробнее на сайте…',
        'UF_ACTIVE' => 1,
    ],
];


echo '<pre>';
foreach ($data as $item) {
    $page = VirtualPageTable::getList([
        'select' => ['ID'],
        'filter' => [
            'UF_URL' => $item['UF_URL'],
        ],
    ])->fetch();

    if ($page === false) {
        $result = VirtualPageTable::add($item);

        if ($result->isSuccess()) {
            echo 'OK' . PHP_EOL;
            continue;
        }
        exit();
    }

    unset($item['UF_NAME'], $item['UF_URL']);
    $result = VirtualPageTable::update($page['ID'], $item);
    if ($result->isSuccess()) {
        echo 'OK' . PHP_EOL;
        continue;
    }
    exit();
}
echo '</pre>';