<?php

use ig\Datasource\Highload\VirtualPageTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;

if (!$USER->IsAdmin()) {
    exit();
}

$virtualPages = VirtualPageTable::getList([
    'select' => ['*'],
]);

$all = [];

$duplicates = [];
while ($virtualPage = $virtualPages->fetch()) {
    if (!isset($all[$virtualPage['UF_URL']])) {
        $all[$virtualPage['UF_URL']] = $virtualPage;
        continue;
    }
    if (isset($duplicates[$virtualPage['UF_URL']])) {
        $duplicates[$virtualPage['UF_URL']][$virtualPage['ID']] = $virtualPage;
        continue;
    }
    $duplicates[$virtualPage['UF_URL']] = [
        $all[$virtualPage['UF_URL']],
        $virtualPage,
    ];
}
unset($all);

$toDelete = [];
$toUpdate = [];
foreach ($duplicates as $url => $items) {
    $resultItem = array_shift($items);
    foreach ($items as $item) {
        foreach ($field as $key => $value) {
            if ($key === 'ID') {
                continue;
            }
            $resultItem[$key] = mb_strlen($value) > mb_strlen($resultItem[$key]) ? $value : $resultItem[$key];
        }
        $toDelete[] = $item['ID'];
    }
    $resultItem['UF_ACTIVE'] = 1;
    $toUpdate[$resultItem['ID']] = $resultItem;
}
echo '<pre>';
foreach ($toUpdate as $id => $values) {
    $result = VirtualPageTable::update($id, $values);
    if($result->isSuccess()) {
        echo 'Update Ok'.PHP_EOL;
        continue;
    }
    /** @noinspection ForgottenDebugOutputInspection */
    var_dump($result->getErrorMessages());
    exit();
}

foreach ($toDelete as $id ) {
    $result = VirtualPageTable::delete($id);
    if($result->isSuccess()) {
        echo 'Delete Ok'.PHP_EOL;
        continue;
    }
    /** @noinspection ForgottenDebugOutputInspection */
    var_dump($result->getErrorMessages());
    exit();
}

echo '</pre>';