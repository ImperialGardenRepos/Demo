<?php


namespace ig\Helpers;


use CCatalogSku;

class IBlockHelper
{
    /**
     * This is only to remember easier, no real need
     * @param int $iBlockId
     * @return array|null
     */
    public static function getSKUIBlock(int $iBlockId): ?array
    {
        $skuIBlock = CCatalogSku::GetInfoByProductIBlock($iBlockId);
        if (!$skuIBlock) {
            return null;
        }
        return $skuIBlock;
    }

}