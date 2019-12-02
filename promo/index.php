<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Спецпредложения');

$APPLICATION->IncludeComponent(
    'bitrix:catalog.section.list',
    'landings',
    [
        'IBLOCK_TYPE' => SERVICE_IBLOCK_TYPE,
        'IBLOCK_ID' => CONSTRUCTOR_IBLOCK_ID,
        'SECTION_CODE' => 'promo',
        'SEF_FOLDER' => '/promo/',
        'ADD_SECTIONS_CHAIN' => 'N'
    ],
    false
);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';