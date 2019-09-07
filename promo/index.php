<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Специальные предложения');

$APPLICATION->IncludeComponent(
    'bitrix:catalog.section.list',
    'landings',
    [
        'IBLOCK_TYPE' => SERVICE_IBLOCK_TYPE,
        'IBLOCK_ID' => CONSTRUCTOR_IBLOCK_ID,
        'SEF_FOLDER' => '/promo/'
    ],
    false
);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
