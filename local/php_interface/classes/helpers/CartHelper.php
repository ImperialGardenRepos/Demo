<?php

namespace ig\Helpers;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;

class CartHelper
{
    /**
     * Method removes deactivated SKUs from user's basket
     * Important notice, do not mix Product IDs and Basket Item IDs
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function actualize(): void
    {
        Loader::includeModule('sale');
        Loader::includeModule('iblock');

        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        $basketItems = $basket->getBasketItems();

        if ($basketItems === []) {
            return;
        }

        $basketProductIds = [];
        foreach ($basketItems as $basketItem) {
            /** @var BasketItem $basketItem */
            $basketProductIds[$basketItem->getField('ID')] = $basketItem->getField('PRODUCT_ID');
        }

        $deactivatedBasketProductIds = ElementTable::query()
            ->setSelect(['ID'])
            ->where('ACTIVE', '!=', 'Y')
            ->whereIn('ID', array_values($basketProductIds))
            ->exec()
            ->fetchAll();
        if ($deactivatedBasketProductIds === []) {
            return;
        }

        $basketItemIds = array_flip($basketProductIds);
        foreach ($deactivatedBasketProductIds as $product) {
            $basketItemId = $basketItemIds[(string)$product['ID']];
            $basketItem = $basket->getItemById($basketItemId);
            if ($basketItem !== null) {
                $basketItem->delete();
            }
        }
        $basket->save();
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function get(): array
    {
        static::actualize();

        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        $basketItems = $basket->getBasketItems();
        $result = [];
        foreach ($basketItems as $basketItem) {
            /** @var BasketItem $basketItem */
            $result['ITEMS'][$basketItem->getField('PRODUCT_ID')] = [
                'QUANTITY' => $basketItem->getQuantity(),
                'PRICE' => $basketItem->getPrice(),
                'SUM' => $basketItem->getFinalPrice(),
            ];
        }
        $result['SUM'] = $basket->getBasePrice();
        return $result;
    }
}