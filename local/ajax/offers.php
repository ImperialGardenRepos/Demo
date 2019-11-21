<?php

use Bitrix\Main\Application;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$request = Application::getInstance()->getContext()->getRequest();
$productId = $request->get('productID');
$tpl = $request->get('tpl');
if ($productId < 1) {
    exit();
}
if ($tpl === null) {
    $tpl = '.default';
}

$html = [];
global $catalogFilter;
$catalogFilter = [
    '=ID' => $productId,
];
$componentName = 'ig:catalog.section';
CBitrixComponent::includeComponentClass($componentName);
$section = new CatalogSection();
$section->arParams = ['FILTER_NAME' => 'catalogFilter'];
$section->setItems();
$section->setOffers();

$item = reset($section->arResult['ITEMS']);
unset($section);

$item['OFFERS'] = array_values($item['OFFERS']);

$result = [];
$galleryHtml = '';
$paramsHtml = '';
$actionsHtml = '';
$index = 1;
$componentTemplatePath = __DIR__ .
    DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'components' .
    DIRECTORY_SEPARATOR . 'ig' . DIRECTORY_SEPARATOR . 'catalog.section' .
    DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $tpl . DIRECTORY_SEPARATOR;

while (isset($item['OFFERS'][$index])) {
    $offersIndex = $index;
    ob_start();
    include $componentTemplatePath . '_gallery.php';
    $galleryHtml .= ob_get_clean();
    ob_start();
    include $componentTemplatePath . '_params.php';
    $paramsHtml .= ob_get_clean();
    ob_start();
    include $componentTemplatePath . '_price.php';
    $actionsHtml .= ob_get_clean();
    $index++;
}
$result['html'] = [
    'images' => $galleryHtml,
    'params' => $paramsHtml,
    'actions' => $actionsHtml,
];

echo json_encode($result);