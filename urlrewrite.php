<?php
$arUrlRewrite = [
    [
        'CONDITION' => '#^/examples/my-components/news/#',
        'RULE' => NULL,
        'ID' => 'demo:news',
        'PATH' => '/examples/my-components/news_sef.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/katalog/tovary-dlya-sada(.*)/page-([0-9]+)/#',
        'RULE' => 'PAGEN_1=$2&',
        'ID' => 'ig:catalog-garden',
        'PATH' => '/katalog/tovary-dlya-sada/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/katalog/tovary-dlya-sada/#',
        'RULE' => '',
        'ID' => 'ig:catalog-garden',
        'PATH' => '/katalog/tovary-dlya-sada/index.php',
        'SORT' => 200,
    ],
    [
        'CONDITION' => '#^/e-store/books/reviews/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/e-store/books/reviews/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/e-store/xml_catalog/#',
        'RULE' => '',
        'ID' => 'bitrix:catalog',
        'PATH' => '/e-store/xml_catalog/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/katalog/rasteniya(.*)/page-([0-9]+)/#',
        'RULE' => 'PAGEN_1=$2&',
        'ID' => 'ig:catalog',
        'PATH' => '/katalog/rasteniya/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/katalog/rasteniya/#',
        'RULE' => '',
        'ID' => 'ig:catalog',
        'PATH' => '/katalog/rasteniya/index.php',
        'SORT' => 200,
    ],
    [
        'CONDITION' => '#^/content/articles/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/content/articles/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/personal/lists/#',
        'RULE' => '',
        'ID' => 'bitrix:lists',
        'PATH' => '/personal/lists/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/e-store/books/#',
        'RULE' => '',
        'ID' => 'bitrix:catalog',
        'PATH' => '/e-store/books/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/content/news/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/content/news/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/info/novosti/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/info/novosti/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/o-nas/otzyvy/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/o-nas/otzyvy/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/content/faq/#',
        'RULE' => '',
        'ID' => 'bitrix:support.faq',
        'PATH' => '/content/faq/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/o-nas/dosug/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/o-nas/dosug/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/portfolio/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/portfolio/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/services/#',
        'RULE' => '',
        'ID' => 'bitrix:catalog',
        'PATH' => '/services/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/products/#',
        'RULE' => '',
        'ID' => 'bitrix:catalog',
        'PATH' => '/products/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/uslugi/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/uslugi/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/news/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/news/index.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/promo/(.*)/#',
        'RULE' => 'CODE=$1',
        'ID' => 'ig:page.constructor',
        'PATH' => '/promo/detail.php',
        'SORT' => 100,
    ],
    [
        'CONDITION' => '#^/promo/#',
        'RULE' => '',
        'ID' => 'bitrix:catalog.section.list',
        'PATH' => '/promo/index.php',
        'SORT' => 100,
    ],
];
