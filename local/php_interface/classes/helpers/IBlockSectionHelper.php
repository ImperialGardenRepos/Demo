<?php

namespace ig\Helpers;

use Bitrix\Iblock\InheritedProperty\SectionValues;
use Bitrix\Iblock\Model\Section;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CIBlockElement;
use CIBlockSection;

class IBlockSectionHelper
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
     * ToDo:: refactor using price codes from section parameters
     * @param int $iBlockId
     * @param int $sectionId
     * @return float
     */
    public static function getMinPrice(int $iBlockId, int $sectionId): float
    {
        $cache = Cache::createInstance();
        if ($cache->initCache(432000, "min_price_{$iBlockId}_{$sectionId}"))
        {
            $result = $cache->getVars();
            return $result['price'];
        }
        $cache->startDataCache();
        $result = [];
        $productFilter = [];
        if ($sectionId > 0) {
            $productFilter = [
                'SECTION_ID' => $sectionId,
                'INCLUDE_SUBSECTIONS' => 'Y',
            ];
        }

        $productFilter += [
            'IBLOCK_ID' => $iBlockId,
            'ACTIVE' => 'Y',
        ];

        $productModel = CIBlockElement::GetList(
            [],
            $productFilter,
            false,
            false,
            ['ID']
        );
        if ($productModel->SelectedRowsCount() === 0) {
            $result['price'] = 0;
            $cache->endDataCache($result);
            return 0;
        }
        $productIDs = [];
        while ($row = $productModel->Fetch()) {
            $productIDs[] = $row['ID'];
        }

        $skuIBlock = IBlockHelper::getSKUIBlock($iBlockId);
        if($skuIBlock === null) {
            $result['price'] = 0;
            $cache->endDataCache($result);
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
                'CATALOG_PRICE_2',
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
                'CATALOG_PRICE_3',
            ]
        );
        if ($skuModel->SelectedRowsCount() !== 1 && $skuModelDiscount->SelectedRowsCount() !== 1) {
            $result['price'] = 0;
            $cache->endDataCache($result);
            return 0;
        }
        $sku = $skuModel->Fetch();
        $skuPriceMin = (float)$sku['CATALOG_PRICE_2'];
        $skuDiscount = $skuModelDiscount->Fetch();
        $skuDiscountPriceMin = (float)$skuDiscount['CATALOG_PRICE_3'];
        if($skuDiscountPriceMin <= 0) {
            $result['price'] = $skuPriceMin;
            $cache->endDataCache($result);
            return $skuPriceMin;
        }
        $price = $skuPriceMin > $skuDiscountPriceMin ? $skuDiscountPriceMin : $skuPriceMin;
        $result['price'] = $price;
        return $price;
    }

    /**
     * @param int $iBlockId
     * @param int $sectionId
     * @param array $fields
     * @return array|null
     */
    public static function get(int $iBlockId, int $sectionId, array $fields = ['*', 'UF_*']): ?array
    {
        $sectionModel = CIBlockSection::GetList(
            [
                'ID' => 'ASC',
            ],
            [
                'ACTIVE' => 'Y',
                'ID' => $sectionId,
                'IBLOCK_ID' => $iBlockId,
                'CNT_ACTIVE' => 'Y',
            ],
            true,
            $fields,
            [
                'nTopCount' => 1,
            ]
        );

        if ((int)$sectionModel->SelectedRowsCount() !== 1) {
            return null;
        }
        return $sectionModel->GetNext(true, false);
    }

    /**
     * Gets parent sections
     * @param array $section
     * @param array $urlTemplates
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getPath(array $section, array $urlTemplates): array
    {
        $result = [];

        $pathModel = CIBlockSection::GetNavChain($section['IBLOCK_ID'], $section['IBLOCK_SECTION_ID']);
        $pathModel->SetUrlTemplates($urlTemplates['element'], $urlTemplates['section'], $urlTemplates['sections']);

        $entity = Section::compileEntityByIblock($section['IBLOCK_ID']);
        while ($path = $pathModel->GetNext(true, false)) {
            /** @var DataManager $entity */
            $userFields = $entity::query()
                ->setSelect(['UF_*'])
                ->where('ID', '=', $path['ID'])
                ->exec()
                ->fetch();
            foreach ($userFields as $key => $value) {
                $path[$key] = $value;
            }
            $result[] = $path;
        }
        return $result;
    }

    /**
     * @param int $iBlockId
     * @param int $sectionId
     * @return array
     */
    public static function getInheritedProperties(int $iBlockId, int $sectionId): array
    {
        $ipropValues = new SectionValues($iBlockId, $sectionId);
        return $ipropValues->getValues();
    }
}