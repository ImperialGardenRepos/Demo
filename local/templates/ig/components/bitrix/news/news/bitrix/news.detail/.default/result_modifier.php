<?php

use ig\CHelper;
use ig\CImageResize;
use ig\Helpers\TextHelper;
use ig\Seo\Meta;

Meta::getInstance()->setBaseTitle($arResult['NAME']);
Meta::getInstance()->setBaseDescription(TextHelper::truncateByStatement($arResult['DETAIL_TEXT']));

if (!empty($arResult['PROPERTIES']['SLIDER_IMAGES']['VALUE'])) {
    foreach ($arResult['PROPERTIES']['SLIDER_IMAGES']['VALUE'] as $intID) {
        $arResult['SLIDER_IMAGES'][] = CHelper::getSliderImageData($intID, array('RESIZE' => array('WIDTH' => 600, 'HEIGHT' => 495)));
    }
} elseif ($arResult['DETAIL_PICTURE']['ID'] > 0) {
    $obImageProcessor = new CImageResize;

    $arResult['TILE']['src'] = $obImageProcessor->getResizedImgOrPlaceholder($arResult['DETAIL_PICTURE']['ID'], 600, 495, array('WATERMARK' => 'N'));
    $arResult['DETAIL_PICTURE']['SRC'] = $obImageProcessor->getResizedImgOrPlaceholder($arResult['DETAIL_PICTURE']['ID'], $arResult['DETAIL_PICTURE']['WIDTH']);
}

// еще новости
$rsI = CIBlockElement::GetList(
    Array('DATE_ACTIVE_FROM' => 'DESC'),
    array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arResult['IBLOCK_ID'])
    , false, array('nElementID' => $arResult['ID'], 'nPageSize' => 3), array(
    'ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL'
));
while ($arI = $rsI->GetNext()) {
    if ($arI['ID'] == $arResult['ID']) {
        continue;
    }

    $intImage = ($arI['PREVIEW_PICTURE'] > 0 ? $arI['PREVIEW_PICTURE'] : $arI['DETAIL_PICTURE']);

    if ($intImage > 0) {
        $arFileTmp = CFile::ResizeImageGet($intImage, array(
            'width' => 386,
            'height' => 218
        ), BX_RESIZE_IMAGE_EXACT, false);

        $arI['IMAGE'] = $arFileTmp['src'];
    }

    $arResult['MORE_NEWS'][] = $arI;

    if (count($arResult['MORE_NEWS']) > 2) {
        break;
    }
}