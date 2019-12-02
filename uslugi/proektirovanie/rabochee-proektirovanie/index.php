<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$_REQUEST['CODE'] = 'rabochee-proektirovanie';
$APPLICATION->IncludeComponent(
    'ig:page.constructor',
    '',
    [
        'IBLOCK_ID' => CONSTRUCTOR_IBLOCK_ID
    ],
    false
);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
