<?php

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Main\Type\DateTime as BxDateTime;
use ig\CImageResize;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['MORE_OBJECT'] = [];

/**
 * Get featured objects of similar type
 */
if ($arResult['PROPERTIES']['MORE_PROJECTS']['VALUE_XML_ID'] !== null) {
    $featuredObjectModel = CIBlockElement::GetList(
        ['SORT' => 'ASC'],
        [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            '=IBLOCK_SECTION_ID' => (int)$arResult['IBLOCK_SECTION_ID'],
            '!ID' => $arResult['ID'],
            '=PROPERTY_MORE_PROJECTS' => (int)$arResult['PROPERTIES']['MORE_PROJECTS']['VALUE_ENUM_ID']
        ],
        false,
        ['nTopCount' => 2]
    );

    while ($featuredObject = $featuredObjectModel->GetNextElement()) {
        $item = $featuredObject->GetFields();
        $item['PROPERTIES'] = $featuredObject->GetProperties();

        if ($arParams['PREVIEW_TRUNCATE_LEN'] > 0) {
            $item['PREVIEW_TEXT'] = TruncateText($item['PREVIEW_TEXT'], $arParams['PREVIEW_TRUNCATE_LEN']);
        }

        if (strlen($item['ACTIVE_TO']) > 0) {
            $item['DISPLAY_ACTIVE_TO'] = CIBlockFormatProperties::DateFormat('Y', MakeTimeStamp($item['ACTIVE_FROM'], CSite::GetDateFormat()));
        }

        ElementValues::queue($item['IBLOCK_ID'], $item['ID']);

        $item['FIELDS'] = [];

        /**
         * Set Last Modified Header based on featured objects
         */
        if ($arParams['SET_LAST_MODIFIED']) {
            $time = BxDateTime::createFromUserTime($item['TIMESTAMP_X']);
            /**@var DateTime|null $timestampX */
            $timestampX = $arResult['ITEMS_TIMESTAMP_X'] ?? null;
            if (
                $timestampX === null
                || $time->getTimestamp() > $timestampX->getTimestamp()
            ) {
                $arResult['ITEMS_TIMESTAMP_X'] = $time;
            }
        }

        /**
         * Resize image, set finish year
         */
        $item['IMAGE'] = null;
        if ($item['PREVIEW_PICTURE'] !== null) {
            $resizedImage = CFile::ResizeImageGet(
                $item['PREVIEW_PICTURE'],
                [
                    'width' => 600,
                    'height' => 525
                ],
                BX_RESIZE_IMAGE_EXACT
            );
            $item['IMAGE'] = $resizedImage['src'];
        }

        $arDateTo = explode('.', substr($item['DATE_ACTIVE_TO'], 0, 10));
        $item['YEAR_FINISH'] = $arDateTo[2] ?? null;

        $arResult['MORE_OBJECT'][] = $item;
    }
}
/**
 * Set image ids array
 */
$imageIDs = [];

if ($arResult['DETAIL_PICTURE'] !== null && isset($arResult['DETAIL_PICTURE']['ID'])) {
    $imageIDs[] = $arResult['DETAIL_PICTURE']['ID'];
}

if ($arResult['PROPERTIES']['PHOTO']['VALUE'] !== false && is_array($arResult['PROPERTIES']['PHOTO']['VALUE'])) {
    foreach ($arResult['PROPERTIES']['PHOTO']['VALUE'] as $additionalPhotoId) {
        $imageIDs[] = $additionalPhotoId;
    }
}

/**
 * Format images output
 */

$resizeObject = new CImageResize();

$strImages = '';
$strStartImage = '';

$images = [];
foreach ($imageIDs as $imageID) {
    $images[] = [
        'PREVIEW' => $resizeObject->getResizedImgOrPlaceholder($imageID, 1200, 505),
        'FULL' => $resizeObject->getResizedImgOrPlaceholder($imageID, 5000),
    ];
}
$arResult['IMAGES'] = $images;

/**
 * Visual editor links - main item
 */

$buttons = CIBlock::GetPanelButtons(
    $arResult['IBLOCK_ID'],
    $arResult['ID'],
    $arResult['IBLOCK_SECTION_ID'],
    [
        'SECTION_BUTTONS' => false,
        'SESSID' => false

    ]
);
$arResult['ADD_LINK'] = $buttons['edit']['add_element']['ACTION_URL'];
$arResult['EDIT_LINK'] = $buttons['edit']['edit_element']['ACTION_URL'];
$arResult['DELETE_LINK'] = $buttons['edit']['delete_element']['ACTION_URL'];

/**
 * Finish year
 */

$arDateTo = explode('.', substr($arResult['DATE_ACTIVE_TO'], 0, 10));
$arResult['YEAR_FINISH'] = $arDateTo[2] ?? null;
