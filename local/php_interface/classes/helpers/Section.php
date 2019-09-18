<?php

namespace ig\Helpers;

use CCatalogSku;
use CIBlockElement;
use CIBlockSection;

class Section
{
    public static function getSectionName(int $sectionId): ?string
    {
        $model = CIBlockSection::GetList(
            ['SORT' => 'ASC'],
            ['ID' => $sectionId],
            false,
            ['NAME']
        );
        if ($model->SelectedRowsCount() !== 1) {
            return null;
        }
        $section = $model->Fetch();
        return $section['NAME'];
    }

    public static function getMinPrice(int $iBlockId, int $sectionId): float
    {
        $productModel = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $iBlockId,
                'SECTION_ID' => $sectionId,
                'INCLUDE_SUBSECTIONS' => 'Y'
            ],
            false,
            false,
            ['ID']
        );
        if ($productModel->SelectedRowsCount() === 0) {
            return 0;
        }
        $productIDs = [];
        while ($row = $productModel->Fetch()) {
            $productIDs[] = $row['ID'];
        }

        $skuIBlock = CCatalogSku::GetInfoByProductIBlock($iBlockId);
        if (!$skuIBlock) {
            return 0;
        }
        $skuIBlockId = $skuIBlock['IBLOCK_ID'];
        $skuPropId = $skuIBlock['SKU_PROPERTY_ID'];

        $skuModel = CIBlockElement::GetList(
            ['CATALOG_PRICE_2' => 'ASC'],
            [
                'IBLOCK_ID' => $skuIBlockId,
                'PROPERTY_' . $skuPropId => $productIDs
            ],
            false,
            ['nTopCount' => 1],
            [
                'CATALOG_PRICE_2',
            ]
        );

        if($skuModel->SelectedRowsCount() !== 1) {
            return 0;
        }
        $sku = $skuModel->Fetch();
        return (float)$sku['CATALOG_PRICE_2'];
    }

}