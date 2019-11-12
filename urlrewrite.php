<?php
$arUrlRewrite=array (
  1 => 
  array (
    'CONDITION' => '#^/katalog/tovary-dlya-sada(.*)/page-([0-9]+)/#',
    'RULE' => 'PAGEN_1=$2&',
    'ID' => 'ig:catalog-garden',
    'PATH' => '/katalog/tovary-dlya-sada/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/katalog/rasteniya(.*)/page-([0-9]+)/#',
    'RULE' => 'PAGEN_1=$2&',
    'ID' => 'ig:catalog',
    'PATH' => '/katalog/rasteniya/index.php',
    'SORT' => 100,
  ),
  21 => 
  array (
    'CONDITION' => '#^/o-nas/partnyery/page-([0-9]+)/#',
    'RULE' => 'PAGEN_1=$1&',
    'ID' => '',
    'PATH' => '/o-nas/partnyery/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/examples/my-components/news/#',
    'RULE' => NULL,
    'ID' => 'demo:news',
    'PATH' => '/examples/my-components/news_sef.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/e-store/books/reviews/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/e-store/books/reviews/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/e-store/xml_catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/e-store/xml_catalog/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/content/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/content/articles/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/personal/lists/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/personal/lists/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/e-store/books/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/e-store/books/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/o-nas/otzyvy/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/o-nas/otzyvy/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/info/novosti/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/novosti/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/content/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/content/news/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/o-nas/dosug/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/o-nas/dosug/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/content/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:support.faq',
    'PATH' => '/content/faq/index.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^/promo/(.*)/#',
    'RULE' => 'CODE=$1',
    'ID' => 'ig:page.constructor',
    'PATH' => '/promo/detail.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/portfolio/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/portfolio/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/services/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^/products/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/products/index.php',
    'SORT' => 100,
  ),
  18 => 
  array (
    'CONDITION' => '#^/uslugi/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/uslugi/index.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/katalog/tovary-dlya-sada/#',
    'RULE' => '',
    'ID' => 'ig:catalog-garden',
    'PATH' => '/katalog/tovary-dlya-sada/index.php',
    'SORT' => 200,
  ),
  6 => 
  array (
    'CONDITION' => '#^/katalog/rasteniya/#',
    'RULE' => '',
    'ID' => 'ig:catalog',
    'PATH' => '/katalog/rasteniya/index.php',
    'SORT' => 200,
  ),
);
