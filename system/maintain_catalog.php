<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!$USER -> IsAdmin()) {
	$APPLICATION->AuthForm('Access denied');
}

$obCatalogMaintain = new \ig\CCatalogMaintain(\ig\CHelper::getIblockIdByCode('catalog'));
$obCatalogMaintain -> process();
echo 'eos';