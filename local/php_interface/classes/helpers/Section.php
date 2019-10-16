<?php

namespace ig\Helpers;

use CCatalogSku;
use CIBlockElement;
use CIBlockSection;

class Section
{
    /**
     * @param int $sectionId
     * @return string|null
     */
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

    /**
     * @param int $iBlockId
     * @param int $sectionId
     * @return float
     */
    public static function getMinPrice(int $iBlockId, int $sectionId): float
    {
        $productFilter = [];
        if ($sectionId > 0) {
            $productFilter = [
                'SECTION_ID' => $sectionId,
                'INCLUDE_SUBSECTIONS' => 'Y',
            ];
        }

        $productFilter += [
            'IBLOCK_ID' => $iBlockId,
            'ACTIVE' => 'Y'
        ];

        $productModel = CIBlockElement::GetList(
            [],
            $productFilter,
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
                'PROPERTY_' . $skuPropId => $productIDs,
                '>CATALOG_PRICE_2' => 0,
                'ACTIVE' => 'Y',
            ],
            false,
            ['nTopCount' => 1],
            [
                'CATALOG_PRICE_2'
            ]
        );
        $skuModelDiscount = CIBlockElement::GetList(
            ['CATALOG_PRICE_3' => 'ASC'],
            [
                'IBLOCK_ID' => $skuIBlockId,
                'PROPERTY_' . $skuPropId => $productIDs,
                '>CATALOG_PRICE_3' => 0,
                'ACTIVE' => 'Y',
            ],
            false,
            ['nTopCount' => 1],
            [
                'CATALOG_PRICE_3'
            ]
        );
        if ($skuModel->SelectedRowsCount() !== 1 || $skuModelDiscount->SelectedRowsCount() !== 1) {
            return 0;
        }
        $sku = $skuModel->Fetch();
        $skuPriceMin = (float)$sku['CATALOG_PRICE_2'];
        $skuDiscount = $skuModelDiscount->Fetch();
        $skuDiscountPriceMin = (float)$skuDiscount['CATALOG_PRICE_3'];

        return $skuPriceMin > $skuDiscountPriceMin ? $skuDiscountPriceMin : $skuPriceMin;
    }

}