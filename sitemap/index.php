<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Карта сайта');

$APPLICATION->IncludeComponent(
    'ig:html.sitemap',
    '',
    [],
    false
);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
