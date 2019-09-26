<?php

namespace ig\Events;

use ig\CHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Html;

class IBlock
{
    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if (
            $arFields["RESULT"] && $arFields["ID"] > 0
        ) {
            if (
                $arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("offers")
                ||
                $arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")
            ) {
                CHelper::setHeightFilterPropertyValue($arFields["ID"]);
            }
        }
    }

    function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if (
            $arFields["ID"] > 0
        ) {
            if (
                $arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("offers")
                ||
                $arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")
            ) {
                CHelper::setHeightFilterPropertyValue($arFields["ID"]);
            }
        }
    }

    function OnBeforeIBlockElementDeleteHandler($ID)
    {
        $rsI = \CIBlockElement::GetList(false, array(
            "ID" => $ID
        ), false, false, array(
            "ID", "IBLOCK_ID"
        ));
        if ($arI = $rsI->Fetch()) {
            if (
                $arI["IBLOCK_ID"] == CHelper::getIblockIdByCode("offers")
                ||
                $arI["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")
            ) {
                $arGroups = $GLOBALS["USER"]->GetUserGroupArray();
                if (in_array(12, $arGroups)) {
                    global $APPLICATION;
                    $APPLICATION->throwException("Запрещено удалять товары и товарные предложения");
                    return false;
                }
            } elseif (
                $arI["IBLOCK_ID"] == CHelper::getIblockIdByCode("offers-garden")
                ||
                $arI["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog-garden")
            ) {
                $arGroups = $GLOBALS["USER"]->GetUserGroupArray();
                if (in_array(13, $arGroups)) {
                    global $APPLICATION;
                    $APPLICATION->throwException("Запрещено удалять товары и товарные предложения");
                    return false;
                }
            }
        }
    }

    function onBeforeIBlockSectionUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")) {
            if (!$GLOBALS["USER"]->IsAdmin()) {
                $intID = $arFields["ID"];
                $rsSec = \CIBlockSection::GetList(false, array("ID" => $intID), false, array("IBLOCK_SECTION_ID"));
                if ($arSec = $rsSec->Fetch()) {
                    if ($arFields["IBLOCK_SECTION_ID"] != $arSec["IBLOCK_SECTION_ID"]) {
                        global $APPLICATION;
                        $APPLICATION->throwException("Вы не можете переносить разделы каталога. Обратитесь к администратору.");

                        return false;
                    }
                }
            }
        }
    }

    function onBeforeIBlockSectionAddOrUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")) {
            if (strlen($arFields["NAME"]) > 0) {
                $arFilter = array(
                    "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                    "NAME" => $arFields["NAME"],
                );

                if (isset($arFields["IBLOCK_SECTION_ID"])) {
                    $arFilter["SECTION_ID"] = $arFields["IBLOCK_SECTION_ID"];
                }

                $rsSec = \CIBlockSection::GetList(false, $arFilter, false, array("ID"));
                if ($arSec = $rsSec->Fetch()) {
                    if ($arSec["ID"] != $arFields["ID"]) {
                        global $APPLICATION;
                        echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($arFilter, true) . '</pre>';
                        $APPLICATION->throwException("Нельзя сохранять разделы с одинаковым названием");

                        return false;
                    }
                }
            }
        }
    }


    function RemoveYandexDirectTab(&$TabControl)
    {
        if ($GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/iblock_element_edit.php') {
            foreach ($TabControl->tabs as $Key => $arTab) {
                if ($arTab['DIV'] == 'seo_adv_seo_adv') {
                    unset($TabControl->tabs[$Key]);
                }
            }
        }
    }

    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if (
            $arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")
        ) {
            if (CHelper::isUserContentManager() && strpos($_SERVER["REQUEST_URI"], '/bitrix/admin/iblock_list_admin.php') !== false) {
                global $APPLICATION;
                $APPLICATION->throwException("Вы не можете сохранять сорта в списке. Воспользуйтеь страницей редактированя элементеов.");
                return false;
            }
        }

        /**
         * Catching Spreadsheet file update to parse it as HTML to appropriate field
         */
        echo '<pre>';

        if ($arFields['IBLOCK_ID'] === CONSTRUCTOR_IBLOCK_ID) {
            $filePropIDs = CHelper::getIBlockElementPropertyByCodeMask('TABLE_INPUT_FILE_%', CONSTRUCTOR_IBLOCK_ID);
            foreach ($filePropIDs as $filePropID) {
                $editedFileArray = $arFields['PROPERTY_VALUES'][$filePropID['ID']][(string)$arFields['ID'] . ':' . $filePropID['ID']];
                $newFileArray = $arFields['PROPERTY_VALUES'][$filePropID['ID']]['n0'];

                $fileArray = $newFileArray ? $newFileArray : $editedFileArray;
                if ($fileArray === null) {
                    continue;
                }

                if ($fileArray['VALUE']['size'] === 0) {
                    continue;
                }

                if ($fileArray['VALUE']['error'] !== 0) {
                    continue;
                }

                try {
                    $reader = IOFactory::createReaderForFile($fileArray['VALUE']['tmp_name']);
                    $reader->setReadEmptyCells(false);
//                    $reader->
                    $reader->setIncludeCharts(false);

                    $spreadsheet = $reader->load($fileArray['VALUE']['tmp_name']);
                } catch (Exception $e) {
                    /**
                     * Look like an invalid or too complicated file
                     */
                    continue;
                }
                $activeSheet = $spreadsheet->getActiveSheet();

                $highestDataCol = $activeSheet->getHighestDataColumn();
                $highestDataRow = $activeSheet->getHighestDataRow();

                $highestCol = $activeSheet->getHighestColumn();
                $highestRow = $activeSheet->getHighestRow();

                while ($highestRow > $highestDataRow) {
                    $activeSheet->removeRow($highestRow);
                    $highestRow--;
                }


                while ($highestCol > $highestDataCol) {
                    $activeSheet->removeColumnByIndex($highestCol);
                    $highestCol--;
                }

                $codeName = $activeSheet->getCodeName();

                $dataArray = $activeSheet->toArray();


                $spreadsheet->discardMacros();
                $spreadsheet->setMinimized(true);

                $writer = new Html($spreadsheet);
                $writer->setUseInlineCss(true);
                $writer->setGenerateSheetNavigationBlock(false);

                $htmlTable = $writer->generateSheetData();

                $textBlockCode = 'TABLE_TEXT_' . str_replace('TABLE_INPUT_FILE_', '', $filePropID['CODE']);
                $curTextBlockId = CHelper::getIBlockElementPropertyByCodeMask($textBlockCode, CONSTRUCTOR_IBLOCK_ID);
                $curTextBlockId = (int)$curTextBlockId[0]['ID'];
                $key = 'n0';
                if (array_key_exists($arFields['ID'] . ':' . $curTextBlockId, $arFields['PROPERTY_VALUES'][$curTextBlockId])) {
                    $key = $arFields['ID'] . ':' . $curTextBlockId;
                }
                $arFields['PROPERTY_VALUES'][$curTextBlockId][$key]['VALUE'] = [
                    'TEXT' => $htmlTable,
                    'TYPE' => 'html',
                ];
            }
        }
    }

    function OnBeforeIBlockElementAddHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")) {
            if (!$GLOBALS["USER"]->IsAdmin()) {
                global $APPLICATION;
                $APPLICATION->throwException("Вы не можете создавать сорта. Обратитесь к администратору.");
                return false;
            }
        }
    }

    function OnBeforeIBlockElementAddOrUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("news")) {
            \ig\Etc\CNews::setItemSection($arFields);
        } elseif ($arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("catalog")) {
            $intSectionID = CHelper::getFirst($arFields["IBLOCK_SECTION"]);
            if ($intSectionID > 0) {
                $rsSec = \CIBlockSection::GetList(false, array("IBLOCK_ID" => $arFields["IBLOCK_ID"], "ID" => $intSectionID), false, array("NAME", "UF_NAME_LAT"));
                if ($arSec = $rsSec->Fetch()) {
                    if (strpos($arSec["NAME"], "hybr") !== false || strpos($arSec["UF_NAME_LAT"], "hybr") !== false) {
                        $intIsView = CHelper::getFirst($arFields["PROPERTY_VALUES"][CHelper::getPropertyIDByCode("IS_VIEW")])["VALUE"];
                        if ($intIsView > 0) {
                            global $APPLICATION;
                            $APPLICATION->throwException("Гибридный вид не может быть самостоятельным видом");
                            return false;
                        }
                    }
                }
            }

            // проверяем на дубль
            $arFilter = array(
                "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                'NAME' => $arFields["NAME"],
                "CODE" => $arFields["CODE"],
                "SECTION_ID" => $intSectionID,
                "PROPERTY_NAME_LAT" => CHelper::getFirst($arFields["PROPERTY_VALUES"][CHelper::getPropertyIDByCode("NAME_LAT")])["VALUE"]
            );

            $rsI = \CIBlockElement::GetList(false, $arFilter, false, array("nTopCount" => 1), array("ID"));
            if ($arI = $rsI->Fetch()) {
                if ($arFields["ID"] != $arI["ID"]) {
                    $arError[] = 'Запрещено сохранять дубли элементов';
                }
            }
        } elseif ($arFields["IBLOCK_ID"] == CHelper::getIblockIdByCode("offers")) {
            $bSkipEvent = false;

            $oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

            $intOffersIblockID = CHelper::getIblockIdByCode('offers');
            if (
                $oRequest->get('IBLOCK_ID') == $intOffersIblockID
                &&
                (
                    $oRequest->get('action_button_tbl_iblock_element_' . md5("catalog." . $intOffersIblockID)) == 'restore_default_name'
                    ||
                    $oRequest->get('action_button_tbl_iblock_list_' . md5("catalog." . $intOffersIblockID)) == 'restore_default_name'

                )
            ) {
                $bSkipEvent = true;
            }

            if (!$bSkipEvent && false) {
                $arError = array();

                $arErrorText = array(
                    23 => 'Обхват ствола не может быть 0',
                    24 => 'Ширина кроны не может быть 0',
                    25 => 'Высота штамба до начала кроны не может быть 0',
                    26 => 'Высота штамба до места прививки не может быть 0'
                );

                foreach ($arErrorText as $intPropertyID => $strErrorText) {
                    foreach ($arFields["PROPERTY_VALUES"][$intPropertyID] as $intCnt => $arValue) {
                        $strValue = $arValue["VALUE"];

                        if (strlen($strValue) > 0) {
                            $intValue = intval($strValue);
                            if ($intValue == 0) {
                                $arError[] = $arErrorText[$intPropertyID];
                            }
                        }
                    }
                }

                // проверяем на дубль
                $arFilter = array(
                    "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                    'NAME' => $arFields["NAME"]
                );

                foreach ($arFields["PROPERTY_VALUES"] as $intPropertyID => $propValues) {
                    foreach ($propValues as $strValue) {
                        $arFilter["PROPERTY_" . $intPropertyID] = $strValue;
                    }
                }

                $rsI = \CIBlockElement::GetList(false, $arFilter, false, array("nTopCount" => 1), array("ID"));
                if ($arI = $rsI->Fetch()) {
                    if ($arFields["ID"] != $arI["ID"]) {
                        $arError[] = 'Запрещено сохранять товарные позиции с полностью идентичными свойствами';
                    }
                }

                if (!empty($arError)) {
                    global $APPLICATION;
                    $APPLICATION->throwException(implode("<br>", $arError));

                    return false;
                }
            }
        }
    }
}