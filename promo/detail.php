<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$APPLICATION->IncludeComponent(
    'ig:page.constructor',
    '',
    [
        'IBLOCK_ID' => 35
    ],
    false
);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
