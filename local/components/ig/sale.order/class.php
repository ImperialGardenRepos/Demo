<?php

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Web\Json,
    Bitrix\Main\Localization\Loc,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Sale\PersonType,
    Bitrix\Sale\Shipment,
    Bitrix\Sale\PaySystem,
    Bitrix\Sale\Payment,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\Location\LocationTable,
    Bitrix\Sale\Result,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Sale\Services\Company,
    Bitrix\Sale\Location\GeoIp;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\CBitrixComponent::includeComponentClass('bitrix:sale.order.ajax');

class SaleOrder extends SaleOrderAjax
{
    /**
     * Set basket items data from iblocks (basket column properties, sku, preview pictures, etc) to $this->arResult
     */
    protected function obtainPropertiesForIbElements()
    {
        if (empty($this->arElementId)) {
            return;
        }

        $arResult =& $this->arResult;

        \ig\CHelper::preparePriceData(\ig\CHelper::getIblockIdByCode('offers'), $arResult);
        \ig\CHelper::prepareGroupData($arResult);

        $arOffersSelect = array(
            "*", "PROPERTY_*"
        );

        \ig\CHelper::addSelectFields($arOffersSelect);

        $arProductID = array();
        // get offers
        $rsO = CIBlockElement::GetList(array(), array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => array(\ig\CHelper::getIblockIdByCode('offers'), \ig\CHelper::getIblockIdByCode('offers-garden')),
            "ID" => $this->arElementId
        ), false, false, $arOffersSelect);


        while ($obO = $rsO->GetNextElement()) {
            $arO = $obO->GetFields();
            $arO["PROPERTIES"] = $obO->GetProperties();

            \ig\CHelper::prepareItemPrices($arO);

            if (empty($arO["PROPERTIES"]["AVAILABLE"]["VALUE"])) {
                $arO["PROPERTIES"]["AVAILABLE"]["VALUE"] = 'Под заказ';
            }

            if ($arO["PROPERTIES"]["CML2_LINK"]["VALUE"] > 0) {
                $arProductID[] = $arO["PROPERTIES"]["CML2_LINK"]["VALUE"];
            }

            $arResult["IBLOCK"]["OFFERS"][$arO["ID"]] = $arO;


            $intCnt++;
        }

        if (!empty($arProductID)) {
            $arProductFilter = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => array(\ig\CHelper::getIblockIdByCode('catalog'), \ig\CHelper::getIblockIdByCode('catalog-garden')),
                "ID" => $arProductID
            );

            $rsI = CIBlockElement::GetList(array(), $arProductFilter, false, false, array(
                "*", "PROPERTIES_*"
            ));
            while ($obI = $rsI->GetNextElement()) {
                $arI = $obI->GetFields();
                $arI["PROPERTIES"] = $obI->GetProperties();

                $arI["DETAIL_PAGE_URL"] = \ig\CHelper::prepareCatalogDetailUrl($arI);

                $arResult["IBLOCK"]["PRODUCTS"][$arI["ID"]] = $arI;

                $arResult["IBLOCK"]["SECTIONS"][$arI["IBLOCK_SECTION_ID"]] = array();
            }
        }

        if (!empty($arResult["IBLOCK"]["SECTIONS"])) {
            $rsSec = CIBlockSection::GetList(Array(), array("ACTIVE" => "Y", "IBLOCK_ID" => array(\ig\CHelper::getIblockIdByCode('catalog'), \ig\CHelper::getIblockIdByCode('catalog-offers')), "ID" => array_keys($arResult["IBLOCK"]["SECTIONS"])), false, array("NAME", "SECTION_PAGE_URL"));
            while ($arSec = $rsSec->GetNext()) {
                $rsNav = CIBlockSection::GetNavChain(\ig\CHelper::getIblockIdByCode('catalog'), $arSec["ID"]);
                while ($arNav = $rsNav->GetNext()) {
                    $arSec["NAV"][] = $arNav;
                }

                $arResult["IBLOCK"]["SECTIONS"][$arSec["ID"]] = $arSec;
            }
        }
    }

    private function updateBasket($intUserID)
    {
        if (empty($intUserID)) {
            $intUserID = \Bitrix\Sale\Fuser::getId();
        }

        $bSaveBasket = false;
        $arPostQuantity = $this->request->getPost("QUANTITY");

        $basket = \Bitrix\Sale\Basket::loadItemsForFUser($intUserID, \Bitrix\Main\Context::getCurrent()->getSite());
        foreach ($basket as $basketItem) {
            $intProductQuantity = intval($arPostQuantity[$basketItem->getField('PRODUCT_ID')]);

            if ($intProductQuantity > 0) {
                $intBasketQuantity = $basketItem->getField('QUANTITY');
                if ($intBasketQuantity != $intProductQuantity) {
                    $basketItem->setField('QUANTITY', $intProductQuantity);
                    $bSaveBasket = true;
                }
            } else {
                $basket->getItemById($basketItem->getField('ID'))->delete();
                $bSaveBasket = true;
            }
        }

        if ($bSaveBasket) {
            $basket->save();
        }
    }

    private function checkBasket()
    {
        if ($this->request->isPost() && $_POST["soa-action"] == "processOrder") {
            $intUserID = \Bitrix\Sale\Fuser::getId();
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser($intUserID, \Bitrix\Main\Context::getCurrent()->getSite());

            if (count($basket->getBasketItems()) === 0) {
                LocalRedirect($GLOBALS["APPLICATION"]->GetCurDir());
            }
        }
    }

    public function executeComponent()
    {
        global $APPLICATION;

        $this->setFrameMode(false);
        $this->context = Main\Application::getInstance()->getContext();
        $this->checkSession = $this->arParams["DELIVERY_NO_SESSION"] == "N" || check_bitrix_sessid();
        $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
        $isAjaxRequest = $this->request->isAjaxRequest(); //$this->request["is_ajax_post"] == "Y";

        $this->arParams["IS_AJAX"] = $isAjaxRequest;

        if ($isAjaxRequest)
            $APPLICATION->RestartBuffer();

        if ($this->request->isPost()) {
            global $USER;
            $this->updateBasket($USER->GetId());
        }

        $this->checkBasket();

        $this->action = $this->prepareAction();

        Sale\Compatible\DiscountCompatibility::stopUsageCompatible();

        $this->doAction($this->action);
        Sale\Compatible\DiscountCompatibility::revertUsageCompatible();

        if (!empty($this->arResult["ACCOUNT_NUMBER"]) && !isset($_REQUEST["ORDER_ID"])) {
            LocalRedirect($APPLICATION->GetCurDir() . '?ORDER_ID=' . $this->arResult["ACCOUNT_NUMBER"]);
        }

//		if (!$isAjaxRequest)
//		{
//			CJSCore::Init(array('fx', 'popup', 'window', 'ajax', 'date'));
//		}

        //is included in all cases for old template
        if (!empty($this->arResult["ACCOUNT_NUMBER"])) {
            $order = Sale\Order::loadByAccountNumber($this->arResult["ACCOUNT_NUMBER"]);
            $propertyCollection = $order->getPropertyCollection();

            $this->arResult["ORDER"]["USER_NAME"] = $propertyCollection->getPayerName()->getValue();

            $strTemplate = 'showOrder';
        } elseif ($_REQUEST["igs-action"] == 'getOrderFloatBar') {
            $strTemplate = 'floatBar';
        } elseif (empty($this->arResult["BASKET_ITEMS"])) {
            $strTemplate = 'emptyCart';
        } else $strTemplate = '';

        if ($isAjaxRequest) {
            $APPLICATION->RestartBuffer();
        }

        $this->includeComponentTemplate($strTemplate);

        if ($isAjaxRequest) {
//			$APPLICATION->FinalActions();
            die();
        }
    }
}
