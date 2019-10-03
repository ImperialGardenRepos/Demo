<?php

use Bitrix\Main\Loader;
use ig\CFormat;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

Loader::includeModule('iblock');

$plantModel = CIBlockElement::GetList(
    [
        'ID' => 'ASC'
    ],
    [
        'IBLOCK_ID' => 1,
        'ACTIVE' => 'Y'
    ],
    false,
    false,
    [
        'ID',
        'NAME',
        'IBLOCK_ID',
        'DETAIL_PICTURE',
        'PROPERTY_PHOTO_10YEARS',
        'PROPERTY_PHOTO_WINTER',
        'PROPERTY_PHOTO_AUTUMN',
        'PROPERTY_PHOTO_SUMMER',
        'PROPERTY_PHOTO_FLOWER',
        'PROPERTY_PHOTO_FRUIT',
        'PROPERTY_IS_RUSSIAN',
        'PROPERTY_NAME_LAT',
    ]
);
?>
<table>
    <tr>
        <td>ID товара в каталоге</td>
        <td>Название</td>
        <td>Фото &ndash; общее</td>
        <td>Фото &ndash; зима</td>
        <td>Фото &ndash; осень</td>
        <td>Фото &ndash; лето</td>
        <td>Фото &ndash; цветок</td>
        <td>Фото &ndash; плод</td>
        <td>Фото &ndash; 10 лет</td>
    </tr>
    <?php while ($plant = $plantModel->Fetch()): ?>
        <?php
        if ($plant['PROPERTY_IS_RUSSIAN_VALUE'] === null) {
            $name = $plant['NAME'];
            $nameAlt = $plant['PROPERTY_NAME_LAT_VALUE'];
        } else {
            $name = $plant['PROPERTY_NAME_LAT_VALUE'];
            $nameAlt = $plant['NAME'];
        }
        $name = $name ?? $nameAlt;
        $nameFull = CFormat::formatPlantTitle($name);
        $photoExist =
            $plant['DETAIL_PICTURE']
            || $plant['PROPERTY_PHOTO_WINTER_VALUE']
            || $plant['PROPERTY_PHOTO_AUTUMN_VALUE']
            || $plant['PROPERTY_PHOTO_SUMMER_VALUE']
            || $plant['PROPERTY_PHOTO_FLOWER_VALUE']
            || $plant['PROPERTY_PHOTO_FRUIT_VALUE']
            || $plant['PROPERTY_PHOTO_10YEARS_VALUE'];
        ?>
        <tr style="<?= $photoExist === true ? '' : 'background:red' ?>">
            <td><?= $plant['ID'] ?></td>
            <td><?= $nameFull ?></td>
            <td><?= $plant['DETAIL_PICTURE'] === null ? 'Нет' : 'Есть' ?></td>
            <td><?= $plant['PROPERTY_PHOTO_WINTER_VALUE'] === null ? 'Нет' : 'Есть' ?></td>
            <td><?= $plant['PROPERTY_PHOTO_AUTUMN_VALUE'] === null ? 'Нет' : 'Есть' ?></td>
            <td><?= $plant['PROPERTY_PHOTO_SUMMER_VALUE'] === null ? 'Нет' : 'Есть' ?></td>
            <td><?= $plant['PROPERTY_PHOTO_FLOWER_VALUE'] === null ? 'Нет' : 'Есть' ?></td>
            <td><?= $plant['PROPERTY_PHOTO_FRUIT_VALUE'] === null ? 'Нет' : 'Есть' ?></td>
            <td><?= $plant['PROPERTY_PHOTO_10YEARS_VALUE'] === null ? 'Нет' : 'Есть' ?></td>
        </tr>
    <?php endwhile; ?>
</table>