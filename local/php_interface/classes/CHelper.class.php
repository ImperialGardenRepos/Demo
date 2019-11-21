<?

namespace ig;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CCatalogSKU;
use CIBlockProperty;
use ig\Highload\Base;

class CHelper
{
    private static
        $arHeightNowConverter,
        $arHeight10Converter,
        $arGroupData = [],
        $arGroupProperties = [],
        $arGroupPropertyValues = [],
        $arIblockPrices = [];

    public static function addRecaptcha()
    {
        $GLOBALS["APPLICATION"]->AddHeadString('<script src="https://www.google.com/recaptcha/api.js" async defer></script>', true);
    }

    public static function getRecaptchaFields()
    {
        return '
			<div class="g-recaptcha" data-sitekey="6LfHihQTAAAAAKE8ezCGalHe57-iZnuAYBRbSy1E"></div>
			<input type="text" readonly class="google-recaptcha-validator" name="recaptcha_validator" data-rule-recaptcha="true" data-msg-recaptcha="Пройдите тест на робота">';
    }

    public static function getSliderImageData($intID, $arParams = [])
    {
        $intID = intval($intID);

        $arResult = [];
        if ($intID > 0) {
            $arImg = \CFile::GetFileArray($intID);

            $arResult["DESCRIPTION"] = $arImg["DESCRIPTION"];

            if ($arParams["RESIZE"]) {
                $obImageProcessor = new \ig\CImageResize;

                $arResult["SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($intID, $arParams["RESIZE"]["WIDTH"], $arParams["RESIZE"]["HEIGHT"]);
            } else {
                $arResult["SRC"] = $arImg["SRC"];
            }
        }

        return $arResult;
    }

    public static function getSlider($strFullSliderID, $arParams = [])
    {
        if (empty($arParams["IMAGES"])) {
            $strSliderID = str_replace(["#", "SLIDER_"], '', $strFullSliderID);
            $intSliderID = intval($strSliderID);

            $strResult = '';

            if ($intSliderID > 0) {
                if (\Bitrix\Main\Loader::includeModule("iblock")) {
                    $arImages = [];

                    $arFilter = [
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("slider"),
                        "IBLOCK_SECTION_ID" => $intSliderID,
                    ];
                    $rsImages = \Bitrix\Iblock\ElementTable::getList([
                        'filter' => $arFilter,
                        "order" => ["SORT" => "ASC", "ID" => "ASC"],
                        "select" => ["DETAIL_PICTURE"],
                    ]);
                    while ($arImage = $rsImages->Fetch()) {
                        $arImages[] = \ig\CHelper::getSliderImageData($arImage["DETAIL_PICTURE"]);
                    }
                }
            }
        } else {
            $arImages = $arParams["IMAGES"];
        }

        if (!empty($arImages)) {
            $strResult = \ig\CFormat::getSlider($arImages);
        }

        return $strResult;
    }

    public static function getDynamicData($arParams = [])
    {
        $arResult = [];

        if ($arParams["FILEDS"]) {
            $arKeys = $arParams["FILEDS"];
        }

        if ($GLOBALS["APPLICATION"]->GetCurDir() == '/personal/order/make/') {
            $arParams["DISCOUNT"] = 'Y';
        }

        $arPossibleKeys = ["FAVORITE", "CART"];
        if (empty($arKeys)) {
            $arKeys = $arPossibleKeys;
        }

        if (!is_array($arKeys)) {
            $arKeys = [$arKeys];
        }

        foreach ($arKeys as $strCode) {
            if ($strCode == 'FAVORITE') {
                $arResult['FAVORITE'] = \ig\CFavorite::getInstance()->getFavorite();
            } else if ($strCode == 'CART') {
                $arResult['CART'] = \ig\CHelper::getCartProductData($arParams);
            }
        }

        return $arResult;
    }

    public static function getCurrencyConvertParams()
    {
        return ["CURRENCY_ID" => "RUB"];
    }

    public static function getPriceTypes()
    {
        return [CATALOG_BASE_PRICE_CODE, CATALOG_ACTION_PRICE_CODE];
    }

    public static function getPriceTypesID()
    {
        return [CATALOG_BASE_PRICE_ID, CATALOG_ACTION_PRICE_ID];
    }

    public static function getPriceData($intIblockID, $strParam)
    {
        if (empty(self::$arIblockPrices[$intIblockID])) {
            self::$arIblockPrices[$intIblockID]["PRICES"] = \CIBlockPriceTools::GetCatalogPrices($intIblockID, \ig\CHelper::getPriceTypes());
            self::$arIblockPrices[$intIblockID]["PRICES_ALLOW"] = \CIBlockPriceTools::GetAllowCatalogPrices(self::$arIblockPrices[$intIblockID]["PRICES"]);
        }

        return self::$arIblockPrices[$intIblockID][$strParam];
    }

    public static function preparePriceData($intIblockID, &$arResult)
    {
        $arResult["PRICES"] = self::getPriceData($intIblockID, "PRICES");
        $arResult['PRICES_ALLOW'] = self::getPriceData($intIblockID, "PRICES_ALLOW");
    }

    public static function prepareItemPrices(&$arItem)
    {
        $arItem["PRICES"] = \CIBlockPriceTools::GetItemPrices($arItem["IBLOCK_ID"], self::getPriceData($intIblockID, "PRICES"), $arItem, true, \ig\CHelper::getCurrencyConvertParams());
        $arItem['MIN_PRICE'] = \CIBlockPriceTools::getMinPriceFromList($arItem['PRICES']);

        $arItem["MIN_PRICE_VALUE"] = self::getMinPrice($arItem);
        $arItem["BASE_PRICE_VALUE"] = self::getBasePrice($arItem);

        if ($arItem["MIN_PRICE_VALUE"] >= $arItem["BASE_PRICE_VALUE"]) {
            $arItem["MIN_PRICE_VALUE"] = $arItem["BASE_PRICE_VALUE"];
            unset($arItem["BASE_PRICE_VALUE"]);
        }
    }

    public static function addSelectFields(&$arSelect)
    {
        foreach (self::getPriceData($intIblockID, "PRICES") as $strPriceType => $arPriceType) {
            $arSelect[] = $arPriceType["SELECT"];
        }
    }

    public static function getMinPrice($arItem)
    {
        return $arItem["MIN_PRICE"]["DISCOUNT_VALUE"];
    }

    public static function getBasePrice($arItem)
    {
        return $arItem["PRICES"]["BASE"]["VALUE"];
    }

    public static function getImagesArray($arOffer, $arSort, $arParams = [])
    {
        $arSortProp = $arSort["PROPERTIES"];


        $arImages = [];

        if ($arOffer["DETAIL_PICTURE"] > 0) {
            $arImages[$arOffer["DETAIL_PICTURE"]] = $arParams["TITLE"]["OFFER_DETAIL_PICTURE"];
        } else if ($arSort["DETAIL_PICTURE"] > 0) {
            $arImages[$arSort["DETAIL_PICTURE"]] = $arParams["TITLE"]["SORT_DETAIL_PICTURE"];
        }

        if ($arSortProp["PHOTO_FRUIT"]["VALUE"] > 0) {
            $arImages[$arSortProp["PHOTO_FRUIT"]["VALUE"]] = 'Плод';
        }

        if ($arSortProp["PHOTO_FLOWER"]["VALUE"] > 0) {
            $arImages[$arSortProp["PHOTO_FLOWER"]["VALUE"]] = 'Цветок';
        }

        if ($arSortProp["PHOTO_10YEARS"]["VALUE"] > 0) {
            $arImages[$arSortProp["PHOTO_10YEARS"]["VALUE"]] = 'Взрослое растение';
        }

        if ($arSortProp["PHOTO_WINTER"]["VALUE"] > 0) {
            $arImages[$arSortProp["PHOTO_WINTER"]["VALUE"]] = 'Зима';
        }

        if ($arSortProp["PHOTO_SUMMER"]["VALUE"] > 0) {
            $arImages[$arSortProp["PHOTO_SUMMER"]["VALUE"]] = 'Лето';
        }

        if ($arSortProp["PHOTO_AUTUMN"]["VALUE"] > 0) {
            $arImages[$arSortProp["PHOTO_SUMMER"]["VALUE"]] = 'Осень';
        }

        $arResult = [];
        foreach ($arImages as $intImageID => $strTitle) {
            $arImage = \CFile::GetFileArray($intImageID);

            if (empty($arImage["DESCRIPTION"])) {
                $strTitle = $arImages[$arImage["ID"]];
            } else {
                $strTitle = $arImage["DESCRIPTION"];
            }

            if ($arImage["WIDTH"] > 0) {
                $obImageProcessor = new \ig\CImageResize;
                $arImage["SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($arImage["ID"], $arImage["WIDTH"]);

                if (isset($arParams["RESIZE"])) {
                    $arImage["RESIZE"]["src"] = $obImageProcessor->getResizedImgOrPlaceholder($arImage["ID"], $arParams["RESIZE"]["WIDTH"], $arParams["RESIZE"]["HEIGHT"]);
                }
            }

            $arImage["TITLE"] = $strTitle;

            $arResult[$intImageID] = $arImage;

            if (isset($arParams["CNT"]) && count($arResult) == $arParams["CNT"]) {
                break;
            }
        }

        return $arResult;
    }

    public static function intToMonth($intMonth)
    {
        $arMonth = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];

        return $arMonth[$intMonth];
    }

    #ToDo::get rid after refactor
    public static function prepareGroupData(&$arResult)
    {
        if (!is_array($arResult["OFFER_PARAMS"])) {
            $arResult["OFFER_PARAMS"] = [];
        }

        $rsGroup = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("Group"), ["UF_ACTIVE" => 1], [
            "UF_NAME",
            "ID",
            "UF_XML_ID",
            "UF_ICON",
            "UF_CODE",
        ], [], true);
        while ($arGroup = $rsGroup->Fetch()) {
            $arResult["OFFER_PARAMS"]["GROUP"][$arGroup["UF_XML_ID"]] = [
                "ID" => $arGroup["ID"],
                "NAME" => $arGroup["UF_NAME"],
                "UF_CODE" => $arGroup["UF_CODE"],
                "URL" => CATALOG_BASE_PATH . $arGroup['UF_CODE'] . '/',
                "VALUE" => $arGroup["UF_XML_ID"],
                "SPHINX_VALUE" => \ig\sphinx\CatalogOffers::convertValueToSphinx("PROPERTY_GROUP", $arGroup["UF_XML_ID"]),
                "COUNT" => 0,
                "ICON" => $arGroup["UF_ICON"],
                "DISABLED" => "N",
            ];
        }
    }


    public static function actualizeBasket()
    {
        if (\Bitrix\Main\Loader::includeModule('sale') && \Bitrix\Main\Loader::includeModule('iblock')) {
            $arTmp = [];

            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
            $basketItems = $basket->getBasketItems();
            foreach ($basketItems as $basketItem) {
                $arTmp[$basketItem->getField("PRODUCT_ID")] = $basketItem->getField("ID");
            }

            if (!empty($arTmp)) {
                $rsItems = \Bitrix\Iblock\ElementTable::getList([
                    'filter' => [
                        "=ACTIVE" => 'Y',
                        "ID" => array_keys($arTmp),
                    ],
                    'select' => ["ID"],
                ]);

                while ($arItem = $rsItems->Fetch()) {
                    unset($arTmp[$arItem["ID"]]);
                }

                // в корзине товары, которые деактивированы
                if (!empty($arTmp)) {
                    foreach ($arTmp as $intProductID => $intBasketProductID) {
                        $basket->getItemById($intBasketProductID)->delete();
                    }

                    $basket->save();
                }
            }
        }
    }

    public static function prepareCartData(&$arResult)
    {
        \ig\CHelper::actualizeBasket();

        if (\Bitrix\Main\Loader::includeModule('sale') && \Bitrix\Main\Loader::includeModule('iblock')) {
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
            $basketItems = $basket->getBasketItems();
            foreach ($basketItems as $basketItem) {
                $arResult["CART"][$basketItem->getField("PRODUCT_ID")] = [
                    "QUANTITY" => $basketItem->getQuantity(),
                    "SUMM" => $basketItem->getFinalPrice(),
                    "PRICE" => $basketItem->getPrice(),
                ];
            }

            $arResult["CART_SUMM"] = $basket->getBasePrice();
        }
    }

    public static function prepareCatalogDetailUrl($arItem)
    {
        $arPath = explode('/', $arItem["DETAIL_PAGE_URL"]);

        if (strlen($arPath[count($arPath) - 3]) > 0 && $arPath[count($arPath) - 2] == $arPath[count($arPath) - 3]) {
            $strResult = implode('/', array_slice($arPath, 0, count($arPath) - 2)) . '/';
        } else {
            $strResult = $arItem["DETAIL_PAGE_URL"];
        }


        return $strResult;
    }

    public static function getCartProductData($arParams = [])
    {
        $arResult = [];

        if (\Bitrix\Main\Loader::includeModule('sale')) {
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
            $basketItems = $basket->getOrderableItems();

            foreach ($basketItems as $basketItem) {
//				$arResult[intval($basketItem->getField('PRODUCT_ID'))] = intval($basketItem->getQuantity());

                $arResult[intval($basketItem->getField('PRODUCT_ID'))] = [
                    'quantity' => $basketItem->getQuantity(),
                    'price' => $basketItem->getPrice(),
                    'summ' => $basketItem->getFinalPrice(),
                ];
            }

            // discounts
            if ($arParams["DISCOUNT"] == 'Y') {
                $obOrder = \Bitrix\Sale\Order::create(
                    \Bitrix\Main\Context::getCurrent()->getSite(),
                    \Bitrix\Sale\Fuser::getId()
                );
                $obOrder->setBasket($basket);
                $obDiscounts = $obOrder->getDiscount();
                $obDiscounts->calculate();

                foreach ($obDiscounts->getOrder()->getBasket() as $basketItem) {
                    $floatDiscountPrice = $basketItem->getPrice();

                    if ($floatDiscountPrice != $arResult[$basketItem->getField("PRODUCT_ID")]["price"]) {
                        $arResult[$basketItem->getField("PRODUCT_ID")]['price_discount'] = $floatDiscountPrice;
                        $arResult[$basketItem->getField("PRODUCT_ID")]['summ_discount'] = $floatDiscountPrice * $basketItem->getQuantity();
                    }
                }
            }
        }

        return $arResult;
    }

    public static function isAvailable($strText)
    {
        return $strText == 'В наличии';
    }

    public static function getGroupsData()
    {
        if (empty(self::$arGroupData)) {
            $rsGroup = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("Group"), ["UF_ACTIVE" => 1], ["*"], [], true);
            while ($arGroup = $rsGroup->Fetch()) {
                $arGroup["SPHINX_VALUE"] = \ig\sphinx\CatalogOffers::convertValueToSphinx("PROPERTY_GROUP", $arGroup["UF_XML_ID"]);
                self::$arGroupData[$arGroup["UF_XML_ID"]] = $arGroup;
            }
        }

        return self::$arGroupData;
    }

    public static function getGroupPropertiesData()
    {
        if (empty(self::$arGroupProperties)) {
            $rsGroupProperties = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("PropertyGroup"), ["UF_ACTIVE" => 1], ["*"], ["order" => ["UF_SORT" => "ASC", "UF_NAME" => "ASC"]], true);
            while ($arGroupProperty = $rsGroupProperties->Fetch()) {
                self::$arGroupProperties[$arGroupProperty["ID"]] = $arGroupProperty;
            }
        }

        return self::$arGroupProperties;
    }

    public static function getGroupPropertiesValues($strXmlID = false)
    {
        if (empty(self::$arGroupPropertyValues)) {
            $rsValues = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("PropertyValues"), ["UF_ACTIVE" => 1], ["*"], ["order" => ["UF_SORT" => "ASC", "UF_NAME" => "ASC"]], true);
            while ($arValue = $rsValues->Fetch()) {
                self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
            }

            $rsValues = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("Colors"), ["UF_ACTIVE" => 1], ["*"], ["order" => ["UF_SORT" => "ASC", "UF_NAME" => "ASC"]], true);
            while ($arValue = $rsValues->Fetch()) {
                self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
            }

            $rsValues = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("PeriodHeightExt"), ["UF_ACTIVE" => 1], ["*"], ["order" => ["UF_SORT" => "ASC", "UF_NAME" => "ASC"]], true);
            while ($arValue = $rsValues->Fetch()) {
                self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
            }

            $rsValues = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("PeriodHeightNowExt"), ["UF_ACTIVE" => 1], ["*"], ["order" => ["UF_SORT" => "ASC", "UF_NAME" => "ASC"]], true);
            while ($arValue = $rsValues->Fetch()) {
                self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
            }
        }

        if (empty($strXmlID)) {
            return self::$arGroupPropertyValues;
        } else return self::$arGroupPropertyValues[$strXmlID];
    }

    public static function getGroupData($strGroup)
    {
        $arGroups = \ig\CHelper::getGroupsData();

        if (is_int($strGroup)) {
            foreach ($arGroups as $arGroup) {
                if (!$arGroup["ID"] == $strGroup) {
                    return $arGroup;
                }
            }
        } else {
            return $arGroups[$strGroup];
        }
    }

    public static function getGroupMainProperties($strGroup)
    {
        $arResult = [];

        $arGroup = \ig\CHelper::getGroupData($strGroup);

        $arMainPropsID = $arGroup["UF_MAIN_PROP"];
        $arGroupProperties = \ig\CHelper::getGroupPropertiesData();
        foreach ($arGroupProperties as $arGroupProperty) {
            if (in_array($arGroupProperty["ID"], $arMainPropsID)) {
                $arResult[] = $arGroupProperty;
            }
        }

        return $arResult;
    }

    public static function setHeightFilterPropertyValue($intElementID)
    {
        $arElement = \Bitrix\Iblock\ElementTable::getList([
            "filter" => ["ID" => $intElementID],
            "select" => ["ID", "IBLOCK_ID"],
        ])->fetch();

        if ($arElement["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers")) {
            \ig\CHelper::setHeightNowFilterPropertyValue($arElement);
        } else if ($arElement["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
            \ig\CHelper::setHeight10FilterPropertyValue($arElement);
        }
    }

    public static function setHeightNowFilterPropertyValue($arElement)
    {
        $rsProp = \CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], ["sort" => "asc"], ["CODE" => "HEIGHT_NOW_EXT"]);
        if ($arProp = $rsProp->Fetch()) {
            if (strlen($arProp["VALUE"]) > 0) {
                $strFilterValue = \ig\CHelper::getConvertedHeightNow($arProp["VALUE"]);
                \CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], ["HEIGHT_NOW" => $strFilterValue]);
            } else {
                \CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], ["HEIGHT_NOW" => false]);
            }
        }
    }

    public static function getConvertedHeightNow($strHeightNowExt)
    {
        $strHeightNowExt = trim($strHeightNowExt);

        if (strlen($strHeightNowExt) > 0) {
            if (empty(self::$arHeightNowConverter)) {
                $arHeightNow = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName('PeriodHeightNow'));
                $arHeightNowExt = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName('PeriodHeightNowExt'));
                foreach ($arHeightNowExt as $arHExt) {
                    self::$arHeightNowConverter[$arHExt["UF_XML_ID"]] = $arHeightNow[$arHExt["UF_POINTER"]]["UF_XML_ID"];
                }
            }
        }

        return self::$arHeightNowConverter[$strHeightNowExt];
    }

    public static function setHeight10FilterPropertyValue($arElement)
    {
        $rsProp = \CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], ["sort" => "asc"], ["CODE" => "HEIGHT_10_EXT"]);
        if ($arProp = $rsProp->Fetch()) {
            if (strlen($arProp["VALUE"]) > 0) {
                $strFilterValue = \ig\CHelper::getConvertedHeight10($arProp["VALUE"]);
                \CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], ["HEIGHT_10" => $strFilterValue]);
            } else {
                \CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], ["HEIGHT_10" => false]);
            }
        }
    }

    public static function getConvertedHeight10($strHeight10Ext)
    {
        $strHeight10Ext = trim($strHeight10Ext);

        if (strlen($strHeight10Ext) > 0) {
            if (empty(self::$arHeight10Converter)) {
                $arHeight10 = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName('PeriodHeight'));
                $arHeight10Ext = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName('PeriodHeightExt'));
                foreach ($arHeight10Ext as $arHExt) {
                    self::$arHeight10Converter[$arHExt["UF_XML_ID"]] = $arHeight10[$arHExt["UF_POINTER"]]["UF_XML_ID"];
                }
            }
        }

        return self::$arHeight10Converter[$strHeight10Ext];
    }

    public static function priceToPeriod($floatPrice, $bReturnArray = false)
    {
        $arPricePeriods = self::getPricePediod();
        foreach ($arPricePeriods as $strCode => $arPricePeriod) {
            if ($floatPrice >= $arPricePeriod["FROM"] && $floatPrice < $arPricePeriod["TO"]) {
                if ($bReturnArray)
                    return $arPricePeriod;
                else return $strCode;
            }
        }

        return false;
    }

    public static function getPricePediod()
    {
        if (!CRegistry::exists("PERIOD_PRICE")) {
            $arPricePeriod = Base::getList(Base::getHighloadBlockIDByName("PeriodPrice"));
            $arTmp = [];
            foreach ($arPricePeriod as $arPediod) {
                $arTmp[$arPediod["UF_XML_ID"]] = [
                    "NAME" => $arPediod["UF_NAME"],
                    "FROM" => $arPediod["UF_VALUE_FROM"],
                    "TO" => $arPediod["UF_VALUE_TO"],
                ];
            }
            CRegistry::add("PERIOD_PRICE", $arTmp);
        }

        return CRegistry::get("PERIOD_PRICE");
    }

    public static function isUserContentManager()
    {
        $bResult = false;

        global $USER;

        $arUserGroups = $USER->GetUserGroupArray();
        if (in_array(12, $arUserGroups)) {
            $bResult = true;
        }

        return $bResult;
    }

    public static function formatNumber($intNumber)
    {
        return \ig\CFormat::formatNumber($intNumber);
    }

    public static function getHLGroupID()
    {
        if (!CRegistry::exists("HIGHLOAD_IBLOCK_ID__GROUP")) {
            CRegistry::add("HIGHLOAD_IBLOCK_ID__GROUP", Base::getHighloadBlockIDByName('Group'));
        }

        return CRegistry::get("HIGHLOAD_IBLOCK_ID__GROUP");
    }

    public static function getHLPropertyGroupID()
    {
        if (!CRegistry::exists("HIGHLOAD_IBLOCK_ID__PROPERTY_GROUP")) {
            CRegistry::add("HIGHLOAD_IBLOCK_ID__PROPERTY_GROUP", Base::getHighloadBlockIDByName('PropertyGroup'));
        }

        return CRegistry::get("HIGHLOAD_IBLOCK_ID__PROPERTY_GROUP");
    }

    public static function getFirst($value)
    {
        if (is_array($value))
            return array_shift($value);
        else return $value;
    }

    public static function getPropertyIDByCode($strCode, $intIblockID = false)
    {
        $strCode = trim($strCode);
        $intIblockID = intval($intIblockID);

        $arFilter = ["CODE" => $strCode];
        if ($intIblockID > 0) $arFilter["IBLOCK_ID"] = $intIblockID;

        $rsProp = CIBlockProperty::GetList(["sort" => "asc", "name" => "asc"], $arFilter);
        while ($arProp = $rsProp->GetNext()) {
            return $arProp["ID"];
        }

        return '';
    }

    public static function getProductByOfferID($intOfferID, $arParams)
    {
        $intOfferID = intval($intOfferID);

        $arResult = [];

        if ($intOfferID > 0) {
            if (!isset($arParams["SELECT"])) {
                $arParams["SELECT"] = ["*", "PROPERTY_*"];
            }

            echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($intOfferID, true) . '</pre>';
            $rsI = \CIBlockElement::GetList([], [
                "ID" => $intOfferID,
            ], false, false, $arParams["SELECT"]);
            if ($arI = $rsI->Fetch()) {
                echo __FILE__ . ': ' . __LINE__ . '<pre>' . print_r($arI, true) . '</pre>';
                $arResult = $arI;
            }
        }

        return $arResult;
    }

    public static function getIblockIdByCode($strCode, $arAdditionalFilter = [])
    {
        if (!CRegistry::exists("IBLOCK_ID__" . $strCode)) {
            $arFilter = ["CODE" => $strCode];
            if (!empty($arAdditionalFilter) && is_array($arAdditionalFilter))
                $arFilter = array_merge($arAdditionalFilter, $arFilter);

            $arIblock = \Bitrix\Iblock\IblockTable::getList(
                [
                    "filter" => $arFilter,
                ]
            )->fetch();

            CRegistry::add("IBLOCK_ID__" . $strCode, $arIblock["ID"]);
        }

        return CRegistry::get("IBLOCK_ID__" . $strCode);
    }

    public static function getEnumID($intIblockID, $strCode, $strName)
    {
        $rsEnum = \CIBlockPropertyEnum::GetList(["DEF" => "DESC", "SORT" => "ASC"], ["IBLOCK_ID" => $intIblockID, "CODE" => $strCode, "VALUE" => $strName]);
        $arEnum = $rsEnum->Fetch();

        return $arEnum["ID"];
    }

    public static function isPropertyEnabledForGroup($strPropertyCode, $varGroup)
    {
        $arPropertyTree = self::getPropertyTree();
        if (!is_int($varGroup) && strlen($varGroup) > 0) {
            $intGroup = \ig\Highload\Base::getIDByXmlID($varGroup, \ig\CHelper::getHLGroupID());
        } else {
            $intGroup = $varGroup;
        }

        return in_array($intGroup, $arPropertyTree[$strPropertyCode]["GROUP"]);
    }

    public static function getPropertyTree($arParams = [])
    {
        $strHash = 'propertyTree_' . md5(serialize($arParams));

        if (!\ig\CRegistry::exists($strHash)) {
            $arResult = [];

            $arPropertyIDToCode = [];

            $arResult["HEIGHT_NOW_EXT"] = [
                "NAME" => "Высота сейчас",
                "GROUP" => [1, 4, 5, 9, 10, 11, 12],
            ];

            $obProperty = Base::getList(Base::getHighloadBlockIDByName("PropertyGroup"), [], ["UF_NAME", "ID", "UF_CODE", "UF_POINTER"], [], true);
            while ($arProperty = $obProperty->Fetch()) {
                $arPropertyIDToCode[$arProperty["ID"]] = $arProperty["UF_CODE"];

                $arResult[$arProperty["UF_CODE"]] = [
                    "NAME" => $arProperty["UF_NAME"],
                    "CODE" => $arProperty["UF_CODE"],
                    "GROUP" => $arProperty["UF_POINTER"],
                    "VALUES" => [],
                ];
            }

            $arValuesFilter = [];
            if ($arParams["TYPE"] > 0) {
                $arValuesFilter["UF_POINTER"] = $arParams["TYPE"];
            }
            $obPropertyValue = Base::getList(Base::getHighloadBlockIDByName("PropertyValues"), $arValuesFilter, ["UF_NAME", "UF_POINTER", "UF_PROPERTY", "ID", "UF_XML_ID", "UF_ICON"], ["order" => ["UF_SORT" => "ASC"]], true);
            while ($arPropertyValue = $obPropertyValue->Fetch()) {
                $arTmp = [
                    "NAME" => $arPropertyValue["UF_NAME"],
                    "XML_ID" => $arPropertyValue["UF_XML_ID"],
                    "ID" => $arPropertyValue["ID"],
                    "LINK" => $arPropertyValue["UF_POINTER"],
                ];

                if (!empty($arPropertyValue["UF_ICON"])) {
                    if (strpos($arPropertyValue["UF_ICON"], "#") === 0) {
                        $strIcon = SITE_TEMPLATE_PATH . '/build/svg/symbol/svg/sprite.symbol.svg' . $arPropertyValue["UF_ICON"];
                    } else {
                        $strIcon = $arPropertyValue["UF_ICON"];
                    }

                    $arTmp["ICON"] = $strIcon;
                }

                if ($arParams["ENABLE_TYPE"]) {
                    if (is_array($arParams["ENABLE_TYPE"])) {
                        $arIntersect = array_intersect($arPropertyValue["UF_POINTER"], $arParams["ENABLE_TYPE"]);
                        if (empty($arIntersect)) $arTmp["DISABLED"] = 'Y';
                    } else {
                        if (!in_array($arParams["ENABLE_TYPE"], $arPropertyValue["UF_POINTER"])) {
                            $arTmp["DISABLED"] = 'Y';
                        }
                    }
                }

                $arResult[$arPropertyIDToCode[$arPropertyValue["UF_PROPERTY"]]]["VALUES"][] = $arTmp;
            }

            \ig\CRegistry::add($strHash, $arResult);
        }


        return \ig\CRegistry::get($strHash);
    }

    public static function getOfferName($intParentID)
    {
        $intParentID = intval($intParentID);
        $strResult = '';

        if ($intParentID > 0) {
            \Bitrix\Main\Loader::includeModule("iblock");

            $arTmp = [];

            $rsI = \CIBlockElement::GetList(false, [
                "IBLOCK_ID" => CHelper::getIblockIdByCode('catalog'),
                "ID" => $intParentID,
            ], false, false, [
                "ID", "IBLOCK_ID", "NAME", "PROPERTY_IS_RUSSIAN", "PROPERTY_IS_VIEW", "IBLOCK_SECTION_ID", "PROPERTY_NAME_LAT",
            ]);
            if ($arI = $rsI->GetNext()) {
                $rsNav = \CIBlockSection::GetNavChain($arI["IBLOCK_ID"], $arI["IBLOCK_SECTION_ID"], ["NAME"]);
                while ($arNav = $rsNav->Fetch()) {
                    $arTmp[] = $arNav["NAME"];
                }

                if (isset($arTmp[1])) $arTmp[1] = CUtil::lowercaseString($arTmp[1]);

                if (empty($arI["PROPERTY_IS_VIEW_VALUE"])) {
                    if (empty($arI["PROPERTY_IS_RUSSIAN_VALUE"])) {
                        $arTmp[] = "'" . $arI["PROPERTY_NAME_LAT_VALUE"] . "'";
                    } else {
                        $arTmp[] = "'" . $arI["NAME"] . "'";
                    }
                }

                $strResult = implode(' ', $arTmp);
            }
        }

        return $strResult;
    }

    public static function getGroupByCatalogID($intID)
    {
        $rsI = \CIBlockElement::GetList([], [
            "IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("catalog"),
            "ID" => $intID,
        ], false, false, [
            "ID", "IBLOCK_ID", "PROPERTY_GROUP",
        ]);
        if ($arI = $rsI->GetNext()) {
            if (!empty($arI["PROPERTY_GROUP_VALUE"])) {
                $arValue = array_shift(Base::getList(Base::getHighloadBlockIDByName("Group"), ["UF_XML_ID" => $arI["PROPERTY_GROUP_VALUE"]], ["ID"]));

                if ($arValue["ID"] > 0) {
                    return $arValue["ID"];
                }
            }
        }

        return false;
    }

    /**
     * Returns element property IDs by (partial) code
     * @param string $codeMask
     * @param int $iBlockId
     * @param array $order
     * @return array|null
     * @throws LoaderException
     */
    public static function getIBlockElementPropertyByCodeMask(string $codeMask, int $iBlockId, array $order = ['ID' => 'ASC']): ?array
    {
        Loader::includeModule('iblock');
        $propertyModel = CIBlockProperty::GetList($order, ['CODE' => $codeMask, 'IBLOCK_ID' => $iBlockId]);
        if ($propertyModel->SelectedRowsCount() === 0) {
            return null;
        }
        $result = [];
        while ($property = $propertyModel->Fetch()) {
            $result[] =
                [
                    'ID' => $property['ID'],
                    'CODE' => $property['CODE'],
                ];
        }
        return $result;
    }

    /**
     * Echoes/returns $text string and decodes entities if needed
     * @param string $text
     * @param string $textType
     * @param bool $straightOutput
     * @return string|null
     */
    public static function renderText(string $text, string $textType = 'HTML', $straightOutput = true): ?string
    {
        if (strtoupper($textType) === 'HTML') {
            $string = html_entity_decode($text);
        } else {
            $string = $text;
        }

        if ($straightOutput === true) {
            echo $string;
            return null;
        }
        return $string;
    }

    /**
     * @param int $catalogIBlockId
     * @return int|null
     */
    public static function getSkuIBlockId(int $catalogIBlockId): ?int
    {
        $skuBlockInfo = CCatalogSKU::GetInfoByProductIBlock($catalogIBlockId);
        return (int)$skuBlockInfo['IBLOCK_ID'];
    }
}