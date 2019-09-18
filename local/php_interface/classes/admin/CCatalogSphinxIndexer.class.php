<?php

namespace ig;

use ig\sphinx\CatalogGardenOffers;
use ig\sphinx\CatalogOffers;

class CCatalogSphinxIndexer
{
    public function reindexCatalog($bFull = false, $intMaxExecutionItems = 1000, $NS = [])
    {
        @set_time_limit(0);
        if (!is_array($NS)) $NS = [];

        $obCatalogOffersIndex = new CatalogOffers();
        $obCatalogGardenOffersIndex = new CatalogGardenOffers();


        $NS["ENTITY"] = trim($NS["ENTITY"]);
        if (empty($NS["ENTITY"])) $NS["ENTITY"] = "IE";
        $NS["FULL_REINDEX"] = $NS["FULL_REINDEX"] == "Y" ? 'Y' : 'N';

        $NS["CNT"] = IntVal($NS["CNT"]);
        if (strlen($NS["SESS_ID"]) != 32) $NS["SESS_ID"] = md5(uniqid(""));
        $intMaxExecutionItems = intval($intMaxExecutionItems);
        if ($intMaxExecutionItems <= 0) $intMaxExecutionItems = 1000;

        if ($NS["CLEAR"] != "Y") { // еще не очищали
            if ($bFull) { // требуется полная очистка
                $NS["FULL_REINDEX"] = 'Y';
                $obCatalogOffersIndex->truncate();
                $obCatalogGardenOffersIndex->truncate();
            }
        }

        $NS["CLEAR"] = "Y"; // более очистка не требуется

        if ($NS["ENTITY"] == 'IE') {
            $intOffersIblockID = \ig\CHelper::getIblockIdByCode('offers');
            $intOffersGardenIblockID = \ig\CHelper::getIblockIdByCode('offers-garden');

            $arCatalogProductsFilter = [
                "ACTIVE" => "Y",
                "IBLOCK_ID" => [
                    $intOffersIblockID,
                    $intOffersGardenIblockID,
                ],
                ">CATALOG_PRICE_" . CRegistry::get("CATALOG_BASE_PRICE_ID") => 0,
                ">ID" => $NS["ID"],
            ];

            $mtime = microtime();        //Считываем текущее время
            $mtime = explode(" ", $mtime);    //Разделяем секунды и миллисекунды
            $tstart = $mtime[1] + $mtime[0];

            $rsI = \CIBlockElement::GetList(["ID" => "ASC"], $arCatalogProductsFilter, false, [
                "nTopCount" => $intMaxExecutionItems,
            ], [
                "ID", "IBLOCK_ID",
            ]);

            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $mtime = $mtime[1] + $mtime[0];

//			file_put_contents($_SERVER["DOCUMENT_ROOT"].'/skock_/sphinx_reindex.txt', date("d.m.Y H:i:s").' '.sprintf ("Список товаров получен за %f секунд", ($mtime - $tstart))."\r\n", FILE_APPEND);

            if ($rsI->SelectedRowsCount() > 0) {
                while ($arI = $rsI->Fetch()) {
                    if ($arI["IBLOCK_ID"] == $intOffersIblockID) {

                        $indexData = $obCatalogOffersIndex->indexItem($arI["ID"], [
                            "BATCH" => "Y",
                            "FULL_REINDEX" => $NS["FULL_REINDEX"],
                        ]);

                        if (is_array($indexData)) {
                            $obCatalogOffersIndex->batchProcess($indexData);
                        }
                    } else if ($arI["IBLOCK_ID"] == $intOffersGardenIblockID) {
                        $indexData = $obCatalogGardenOffersIndex->indexItem($arI["ID"], [
                            "BATCH" => "Y",
                            "FULL_REINDEX" => $NS["FULL_REINDEX"],
                        ]);

                        if (is_array($indexData)) {
                            $obCatalogGardenOffersIndex->batchProcess($indexData);
                        }
                    }

                    $NS["CNT"]++;
                    $NS["ID"] = $arI["ID"];
                }

                $obCatalogOffersIndex->batchProcess(["TYPE" => "INSERT"], ["FLUSH" => "Y"]);
                $obCatalogOffersIndex->batchProcess(["TYPE" => "REPLACE"], ["FLUSH" => "Y"]);

                $obCatalogGardenOffersIndex->batchProcess(["TYPE" => "INSERT"], ["FLUSH" => "Y"]);
                $obCatalogGardenOffersIndex->batchProcess(["TYPE" => "REPLACE"], ["FLUSH" => "Y"]);

                $mtime = microtime();
                $mtime = explode(" ", $mtime);
                $mtime = $mtime[1] + $mtime[0];

//				file_put_contents($_SERVER["DOCUMENT_ROOT"].'/skock_/sphinx_reindex.txt', sprintf ("Список товаров обработан за %f секунд", ($mtime - $tstart))."\r\n", FILE_APPEND);

                return [
                    "CNT" => $NS["CNT"],
                    "ID" => $NS["ID"],
                    "CLEAR" => $NS["CLEAR"],
                    "FULL_REINDEX" => $NS["FULL_REINDEX"],
                    "SESS_ID" => $NS["SESS_ID"],
                    "ENTITY" => $NS["ENTITY"],
                ];
            }
        }

        return $NS["CNT"];
    }
}
