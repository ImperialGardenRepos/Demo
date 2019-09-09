<?php

use Bitrix\Main\Loader as LoaderAlias;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

const IBLOCK_PARAMS = [
    'ACTIVE' => 'Y',
    'NAME' => 'Конструктор',
    'CODE' => 'constructor',
    'LIST_PAGE_URL' => '/promo/',
    'DETAIL_PAGE_URL' => '/promo/#ELEMENT_CODE#/',
    'IBLOCK_TYPE_ID' => 'services',
    'SITE_ID' => 's1',
    'SORT' => 100,
    'VERSION' => 2
];

if ($USER->GetID() !== '1') {
    die();
}

LoaderAlias::includeModule('iblock');

$iBlock = new CIBlock();

$iBlockId = $iBlock->Add(IBLOCK_PARAMS);

if (!is_int($iBlockId) || $iBlockId < 0) {
    die('Failed to migrate up');
}
echo "IBlock with ID {$iBlockId} successfully created";