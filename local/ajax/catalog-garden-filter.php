<?php

use Bitrix\Main\Application;
use ig\Datasource\Sphinx\CatalogGardenOffer;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$searchQuery = Application::getInstance()->getContext()->getRequest()->getQuery('searchQuery');
$offers = new CatalogGardenOffer();

$searchResult = $offers
    ->select(['p_full_name', 'p_link'])
    ->match('p_full_name', "{$searchQuery} | {$searchQuery}* | *{$searchQuery}*", true)
    ->groupBy('p_full_name')
    ->limit(0, 30)
    ->orderBy('p_full_name', 'asc')
    ->execute()
    ->fetchAllAssoc();
ob_start();
include 'html/search_result.php';
$result = ob_get_clean();
echo json_encode([
    'html' => $result,
]);