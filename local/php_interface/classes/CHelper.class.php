<?
namespace ig;

class CHelper {
	private static
			$arHeightNowConverter,
			$arHeight10Converter,
			$arGroupData = array(),
			$arGroupProperties = array(),
			$arGroupPropertyValues = array(),
			$arIblockPrices = array();
	
	public static function addRecaptcha() {
		$GLOBALS["APPLICATION"] -> AddHeadString('<script src="https://www.google.com/recaptcha/api.js" async defer></script>', true);
	}
	
	public static function getRecaptchaFields() {
		return '
			<div class="g-recaptcha" data-sitekey="6LfHihQTAAAAAKE8ezCGalHe57-iZnuAYBRbSy1E"></div>
			<input type="text" readonly class="google-recaptcha-validator" name="recaptcha_validator" data-rule-recaptcha="true" data-msg-recaptcha="Пройдите тест на робота">';
	}
	
	public static function getSliderImageData($intID, $arParams = array()) {
		$intID = intval($intID);
		
		$arResult = array();
		if($intID>0) {
			$arImg = \CFile::GetFileArray($intID);
			
			$arResult["DESCRIPTION"] = $arImg["DESCRIPTION"];
			
			if($arParams["RESIZE"]) {
				$obImageProcessor = new \ig\CImageResize;
				
				$arResult["SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($intID, $arParams["RESIZE"]["WIDTH"], $arParams["RESIZE"]["HEIGHT"]);
			} else {
				$arResult["SRC"] = $arImg["SRC"];
			}
		}
		
		return $arResult;
	}
	
	public static function getSlider($strFullSliderID, $arParams = array()) {
		if(empty($arParams["IMAGES"])) {
			$strSliderID = str_replace(array("#", "SLIDER_"), '', $strFullSliderID);
			$intSliderID = intval($strSliderID);
			
			$strResult = '';
			
			if($intSliderID>0) {
				if(\Bitrix\Main\Loader::includeModule("iblock")) {
					$arImages = array();
					
					$arFilter = array(
						"ACTIVE"    => "Y",
						"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("slider"),
						"IBLOCK_SECTION_ID" => $intSliderID
					);
					$rsImages = \Bitrix\Iblock\ElementTable::getList(array(
						'filter' => $arFilter,
						"order" => array("SORT" => "ASC", "ID" => "ASC"),
						"select" => array("DETAIL_PICTURE")
					));
					while($arImage = $rsImages -> Fetch()) {
						$arImages[] = \ig\CHelper::getSliderImageData($arImage["DETAIL_PICTURE"]);
					}
				}
			}
		} else {
			$arImages = $arParams["IMAGES"];
		}
		
		if(!empty($arImages)) {
			$strResult = \ig\CFormat::getSlider($arImages);
		}
		
		return $strResult;
	}
	
	public static function getDynamicData($arParams = array()) {
		$arResult = array();
		
		if($arParams["FILEDS"]) {
			$arKeys = $arParams["FILEDS"];
		}
		
		if($GLOBALS["APPLICATION"] -> GetCurDir() == '/personal/order/make/') {
			$arParams["DISCOUNT"] = 'Y';
		}
		
		$arPossibleKeys = array("FAVORITE", "CART");
		if(empty($arKeys)) {
			$arKeys = $arPossibleKeys;
		}
		
		if(!is_array($arKeys)) {
			$arKeys = array($arKeys);
		}
		
		foreach($arKeys as $strCode) {
			if($strCode == 'FAVORITE') {
				$arResult['FAVORITE'] = \ig\CFavorite::getInstance() -> getFavorite();
			} elseif($strCode == 'CART') {
				$arResult['CART'] = \ig\CHelper::getCartProductData($arParams);
			}
		}
		
		return $arResult;
	}
	
	public static function getCurrencyConvertParams() {
		return array("CURRENCY_ID" => "RUB");
	}
	
	public static function getPriceTypes() {
		return array("BASE", "ACTION");
	}
	
	public static function getPriceTypesID() {
		return array(CATALOG_BASE_PRICE_ID, CATALOG_ACTION_PRICE_ID);
	}
	
	public static function getPriceData($intIblockID, $strParam)
	{
		if(empty(self::$arIblockPrices[$intIblockID])) {
			self::$arIblockPrices[$intIblockID]["PRICES"] = \CIBlockPriceTools::GetCatalogPrices($intIblockID, \ig\CHelper::getPriceTypes());
			self::$arIblockPrices[$intIblockID]["PRICES_ALLOW"] = \CIBlockPriceTools::GetAllowCatalogPrices(self::$arIblockPrices[$intIblockID]["PRICES"]);
		}
		
		return self::$arIblockPrices[$intIblockID][$strParam];
	}
	
	public static function preparePriceData($intIblockID, &$arResult) {
		$arResult["PRICES"] = self::getPriceData($intIblockID, "PRICES");
		$arResult['PRICES_ALLOW'] = self::getPriceData($intIblockID, "PRICES_ALLOW");
	}
	
	public static function prepareItemPrices(&$arItem) {
		$arItem["PRICES"] = \CIBlockPriceTools::GetItemPrices($arItem["IBLOCK_ID"], self::getPriceData($intIblockID, "PRICES"), $arItem, true, \ig\CHelper::getCurrencyConvertParams());
		$arItem['MIN_PRICE'] = \CIBlockPriceTools::getMinPriceFromList($arItem['PRICES']);
		
		$arItem["MIN_PRICE_VALUE"] = self::getMinPrice($arItem);
		$arItem["BASE_PRICE_VALUE"] = self::getBasePrice($arItem);
		
		if($arItem["MIN_PRICE_VALUE"] >= $arItem["BASE_PRICE_VALUE"]) {
			$arItem["MIN_PRICE_VALUE"] = $arItem["BASE_PRICE_VALUE"];
			unset($arItem["BASE_PRICE_VALUE"]);
		}
	}
	
	public static function addSelectFields(&$arSelect) {
		foreach(self::getPriceData($intIblockID, "PRICES") as $strPriceType => $arPriceType) {
			$arSelect[] = $arPriceType["SELECT"];
		}
	}
	
	public static function getMinPrice($arItem) {
		return $arItem["MIN_PRICE"]["DISCOUNT_VALUE"];
	}

	public static function getBasePrice($arItem) {
		return $arItem["PRICES"]["BASE"]["VALUE"];
	}

	public static function getImagesArray($arOffer, $arSort, $arParams = array()) {
		$arSortProp = $arSort["PROPERTIES"];
		
		
		$arImages = array();
		
		if($arOffer["DETAIL_PICTURE"]>0) {
			$arImages[$arOffer["DETAIL_PICTURE"]] = $arParams["TITLE"]["OFFER_DETAIL_PICTURE"];
		} elseif($arSort["DETAIL_PICTURE"]>0) {
			$arImages[$arSort["DETAIL_PICTURE"]] = $arParams["TITLE"]["SORT_DETAIL_PICTURE"];
		}
		
		if($arSortProp["PHOTO_FRUIT"]["VALUE"]>0) {
			$arImages[$arSortProp["PHOTO_FRUIT"]["VALUE"]] = 'Плод';
		}
		
		if($arSortProp["PHOTO_FLOWER"]["VALUE"]>0) {
			$arImages[$arSortProp["PHOTO_FLOWER"]["VALUE"]] = 'Цветок';
		}
		
		if($arSortProp["PHOTO_10YEARS"]["VALUE"]>0) {
			$arImages[$arSortProp["PHOTO_10YEARS"]["VALUE"]] = 'Взрослое растение';
		}
		
		if($arSortProp["PHOTO_WINTER"]["VALUE"]>0) {
			$arImages[$arSortProp["PHOTO_WINTER"]["VALUE"]] = 'Зима';
		}
		
		if($arSortProp["PHOTO_SUMMER"]["VALUE"]>0) {
			$arImages[$arSortProp["PHOTO_SUMMER"]["VALUE"]] = 'Лето';
		}
		
		if($arSortProp["PHOTO_AUTUMN"]["VALUE"]>0) {
			$arImages[$arSortProp["PHOTO_SUMMER"]["VALUE"]] = 'Осень';
		}
		
		$arResult = array();
		foreach($arImages as $intImageID => $strTitle) {
			$arImage = \CFile::GetFileArray($intImageID);
			
			if(empty($arImage["DESCRIPTION"])) {
				$strTitle = $arImages[$arImage["ID"]];
			} else {
				$strTitle = $arImage["DESCRIPTION"];
			}
			
			if($arImage["WIDTH"]>0) {
				$obImageProcessor = new \ig\CImageResize;
				$arImage["SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($arImage["ID"], $arImage["WIDTH"]);
				
				if(isset($arParams["RESIZE"])) {
					$arImage["RESIZE"]["src"] = $obImageProcessor->getResizedImgOrPlaceholder($arImage["ID"], $arParams["RESIZE"]["WIDTH"], $arParams["RESIZE"]["HEIGHT"]);

//				$arFilters[] = [
//					"name"     => "watermark",
//					"position" => "center",
//					"size"     => "real",
//					"file"     => $watermarkResized = $_SERVER['DOCUMENT_ROOT']."/upload/watermark/watermark_original.png"
//				];
//
//				$arImage["RESIZE"] = \CFile::ResizeImageGet($arImage["ID"], array(
//					"width"  => $arParams["WIDTH"],
//					'height' => $arParams["HEIGHT"]
//				), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters);
				}
			}
			
			$arImage["TITLE"] = $strTitle;
			
			$arResult[$intImageID] = $arImage;
			
			if(isset($arParams["CNT"]) && count($arResult)==$arParams["CNT"]) {
				break;
			}
		}
		
		return $arResult;
	}
	
	public static function intToMonth($intMonth) {
		$arMonth = array(
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
			12 => 'Декабрь'
		);
		
		return $arMonth[$intMonth];
	}
	
	public static function prepareGroupData(&$arResult) {
		if(!is_array($arResult["OFFER_PARAMS"])) {
			$arResult["OFFER_PARAMS"] = array();
		}
		
		$rsGroup = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("Group"), array("UF_ACTIVE" => 1), array(
			"UF_NAME",
			"ID",
			"UF_XML_ID",
			"UF_ICON",
			"UF_CODE"
		), array(), true);
		while ($arGroup = $rsGroup->Fetch()) {
			$arResult["OFFER_PARAMS"]["GROUP"][$arGroup["UF_XML_ID"]] = array(
				"ID"           => $arGroup["ID"],
				"NAME"         => $arGroup["UF_NAME"],
				"UF_CODE"      => $arGroup["UF_CODE"],
				"URL"          => \ig\CRouter::getCatalogGroupPageUrl($arGroup),
				"VALUE"        => $arGroup["UF_XML_ID"],
				"SPHINX_VALUE" => \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_GROUP", $arGroup["UF_XML_ID"]),
				"COUNT"        => 0,
				"ICON"         => $arGroup["UF_ICON"],
				"DISABLED"     => "N"
			);
		}
	}
	
	public static function actualizeBasket() {
		if(\Bitrix\Main\Loader::includeModule('sale') && \Bitrix\Main\Loader::includeModule('iblock')) {
			$arTmp = array();
			
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
			$basketItems = $basket->getBasketItems();
			foreach ($basketItems as $basketItem) {
				$arTmp[$basketItem->getField("PRODUCT_ID")] = $basketItem->getField("ID");
			}
			
			if(!empty($arTmp)) {
				$rsItems = \Bitrix\Iblock\ElementTable::getList(array(
					'filter' => array(
						"=ACTIVE" => 'Y',
						"ID"      => array_keys($arTmp)
					),
					'select' => array("ID")
				));
				
				while ($arItem = $rsItems->Fetch()) {
					unset($arTmp[$arItem["ID"]]);
				}
				
				// в корзине товары, которые деактивированы
				if(!empty($arTmp)) {
					foreach ($arTmp as $intProductID => $intBasketProductID) {
						$basket->getItemById($intBasketProductID)->delete();
					}
					
					$basket->save();
				}
			}
		}
	}
	
	public static function prepareCartData(&$arResult) {
		\ig\CHelper::actualizeBasket();
		
		if(\Bitrix\Main\Loader::includeModule('sale') && \Bitrix\Main\Loader::includeModule('iblock')) {
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
			$basketItems = $basket->getBasketItems();
			foreach ($basketItems as $basketItem) {
				$arResult["CART"][$basketItem->getField("PRODUCT_ID")] = array(
					"QUANTITY" => $basketItem->getQuantity(),
					"SUMM"     => $basketItem->getFinalPrice(),
					"PRICE"    => $basketItem->getPrice()
				);
			}
			
			$arResult["CART_SUMM"] = $basket->getBasePrice();
		}
	}
	
	public static function prepareCatalogDetailUrl($arItem) {
		$arPath = explode('/', $arItem["DETAIL_PAGE_URL"]);
		
		if(strlen($arPath[count($arPath)-3])>0 && $arPath[count($arPath)-2] == $arPath[count($arPath)-3]) {
			$strResult = implode('/', array_slice($arPath, 0, count($arPath)-2)).'/';
		} else {
			$strResult = $arItem["DETAIL_PAGE_URL"];
		}
		
		
		return $strResult;
	}
	
	public static function getCartProductData($arParams = array()) {
		$arResult = array();
		
		if(\Bitrix\Main\Loader::includeModule('sale')) {
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
			$basketItems = $basket->getOrderableItems();
			
			foreach ($basketItems as $basketItem) {
//				$arResult[intval($basketItem->getField('PRODUCT_ID'))] = intval($basketItem->getQuantity());

				$arResult[intval($basketItem->getField('PRODUCT_ID'))] = array(
					'quantity' => $basketItem->getQuantity(),
					'price' => $basketItem->getPrice(),
					'summ' => $basketItem -> getFinalPrice()
				);
			}
			
			// discounts
			if($arParams["DISCOUNT"] == 'Y') {
				$obOrder = \Bitrix\Sale\Order::create(
					\Bitrix\Main\Context::getCurrent()->getSite(),
					\Bitrix\Sale\Fuser::getId()
				);
				$obOrder->setBasket($basket);
				$obDiscounts = $obOrder->getDiscount();
				$obDiscounts -> calculate();

				foreach($obDiscounts->getOrder()->getBasket() as $basketItem) {
					$floatDiscountPrice = $basketItem->getPrice();
					
					if($floatDiscountPrice != $arResult[$basketItem -> getField("PRODUCT_ID")]["price"]) {
						$arResult[$basketItem -> getField("PRODUCT_ID")]['price_discount'] = $floatDiscountPrice;
						$arResult[$basketItem -> getField("PRODUCT_ID")]['summ_discount'] = $floatDiscountPrice*$basketItem->getQuantity();
					}
				}
			}
		}
		
		return $arResult;
	}
	
	public static function isAvailable($strText) {
		return $strText == 'В наличии';
	}
	
	public static function getGroupsData() {
		if(empty(self::$arGroupData)) {
			$rsGroup = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("Group"), array("UF_ACTIVE" => 1), array("*"), array(), true);
			while ($arGroup = $rsGroup->Fetch()) {
				$arGroup["SPHINX_VALUE"] = \ig\sphinx\CCatalogOffers::convertValueToSphinx("PROPERTY_GROUP", $arGroup["UF_XML_ID"]);
				self::$arGroupData[$arGroup["UF_XML_ID"]] = $arGroup;
			}
		}
		
		return self::$arGroupData;
	}
	
	public static function getGroupPropertiesData() {
		if(empty(self::$arGroupProperties)) {
			$rsGroupProperties = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PropertyGroup"), array("UF_ACTIVE" => 1), array("*"), array("order" => array("UF_SORT" => "ASC", "UF_NAME" => "ASC")), true);
			while ($arGroupProperty = $rsGroupProperties->Fetch()) {
				self::$arGroupProperties[$arGroupProperty["ID"]] = $arGroupProperty;
			}
		}
		
		return self::$arGroupProperties;
	}
	
	public static function getGroupPropertiesValues($strXmlID = false) {
		if(empty(self::$arGroupPropertyValues)) {
			$rsValues = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PropertyValues"), array("UF_ACTIVE" => 1), array("*"), array("order" => array("UF_SORT" => "ASC", "UF_NAME" => "ASC")), true);
			while ($arValue = $rsValues->Fetch()) {
				self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
			}
			
			$rsValues = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("Colors"), array("UF_ACTIVE" => 1), array("*"), array("order" => array("UF_SORT" => "ASC", "UF_NAME" => "ASC")), true);
			while ($arValue = $rsValues->Fetch()) {
				self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
			}
			
			$rsValues = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PeriodHeightExt"), array("UF_ACTIVE" => 1), array("*"), array("order" => array("UF_SORT" => "ASC", "UF_NAME" => "ASC")), true);
			while ($arValue = $rsValues->Fetch()) {
				self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
			}
			
			$rsValues = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName("PeriodHeightNowExt"), array("UF_ACTIVE" => 1), array("*"), array("order" => array("UF_SORT" => "ASC", "UF_NAME" => "ASC")), true);
			while ($arValue = $rsValues->Fetch()) {
				self::$arGroupPropertyValues[$arValue["UF_XML_ID"]] = $arValue;
			}
		}
		
		if(empty($strXmlID)) {
			return self::$arGroupPropertyValues;
		} else return self::$arGroupPropertyValues[$strXmlID];
	}
	
	public static function getGroupData($strGroup) {
		$arGroups = \ig\CHelper::getGroupsData();
		
		if(is_int($strGroup)) {
			foreach($arGroups as $arGroup) {
				if(!$arGroup["ID"] == $strGroup) {
					return $arGroup;
				}
			}
		} else {
			return $arGroups[$strGroup];
		}
	}
	
	public static function getGroupMainProperties($strGroup) {
		$arResult = array();
		
		$arGroup = \ig\CHelper::getGroupData($strGroup);
		
		$arMainPropsID = $arGroup["UF_MAIN_PROP"];
		$arGroupProperties = \ig\CHelper::getGroupPropertiesData();
		foreach($arGroupProperties as $arGroupProperty) {
			if(in_array($arGroupProperty["ID"], $arMainPropsID)) {
				$arResult[] = $arGroupProperty;
			}
		}
		
		return $arResult;
	}
	
	public static function setHeightFilterPropertyValue($intElementID) {
		$arElement = \Bitrix\Iblock\ElementTable::getList(array(
			"filter" => array("ID" => $intElementID),
			"select" => array("ID", "IBLOCK_ID")
		)) -> fetch();
		
		if($arElement["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("offers")) {
			\ig\CHelper::setHeightNowFilterPropertyValue($arElement);
		} elseif($arElement["IBLOCK_ID"] == \ig\CHelper::getIblockIdByCode("catalog")) {
			\ig\CHelper::setHeight10FilterPropertyValue($arElement);
		}
	}
	
	public static function setHeightNowFilterPropertyValue($arElement) {
		$rsProp = \CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], array("sort" => "asc"), Array("CODE"=>"HEIGHT_NOW_EXT"));
		if($arProp = $rsProp->Fetch()) {
			if(strlen($arProp["VALUE"])>0) {
				$strFilterValue = \ig\CHelper::getConvertedHeightNow($arProp["VALUE"]);
				\CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("HEIGHT_NOW" => $strFilterValue));
			} else {
				\CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("HEIGHT_NOW" => false));
			}
		}
	}
	
	public static function getConvertedHeightNow($strHeightNowExt) {
		$strHeightNowExt = trim($strHeightNowExt);
		
		if(strlen($strHeightNowExt)>0) {
			if(empty(self::$arHeightNowConverter)) {
				$arHeightNow = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName('PeriodHeightNow'));
				$arHeightNowExt = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName('PeriodHeightNowExt'));
				foreach ($arHeightNowExt as $arHExt) {
					self::$arHeightNowConverter[$arHExt["UF_XML_ID"]] = $arHeightNow[$arHExt["UF_POINTER"]]["UF_XML_ID"];
				}
			}
		}
		
		return self::$arHeightNowConverter[$strHeightNowExt];
	}
	
	public static function setHeight10FilterPropertyValue($arElement) {
		$rsProp = \CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], array("sort" => "asc"), Array("CODE"=>"HEIGHT_10_EXT"));
		if($arProp = $rsProp->Fetch()) {
			if(strlen($arProp["VALUE"])>0) {
				$strFilterValue = \ig\CHelper::getConvertedHeight10($arProp["VALUE"]);
				\CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("HEIGHT_10" => $strFilterValue));
			} else {
				\CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("HEIGHT_10" => false));
			}
		}
	}
	
	public static function getConvertedHeight10($strHeight10Ext) {
		$strHeight10Ext = trim($strHeight10Ext);
		
		if(strlen($strHeight10Ext)>0) {
			if(empty(self::$arHeight10Converter)) {
				$arHeight10 = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName('PeriodHeight'));
				$arHeight10Ext = \ig\CHighload::getList(\ig\CHighload::getHighloadBlockIDByName('PeriodHeightExt'));
				foreach ($arHeight10Ext as $arHExt) {
					self::$arHeight10Converter[$arHExt["UF_XML_ID"]] = $arHeight10[$arHExt["UF_POINTER"]]["UF_XML_ID"];
				}
			}
		}

		return self::$arHeight10Converter[$strHeight10Ext];
	}
	
	public static function priceToPeriod($floatPrice, $bReturnArray = false) {
		$arPricePeriods = self::getPricePediod();
		foreach($arPricePeriods as $strCode => $arPricePeriod) {
			if($floatPrice>=$arPricePeriod["FROM"] && $floatPrice<$arPricePeriod["TO"]) {
				if($bReturnArray)
					return $arPricePeriod;
				else return $strCode;
			}
		}
		
		return false;
	}
	
	public static function getPricePediod() {
		if(!CRegistry::exists("PERIOD_PRICE")) {
			$arPricePeriod = CHighload::getList(CHighload::getHighloadBlockIDByName("PeriodPrice"));
			$arTmp = array();
			foreach($arPricePeriod as $arPediod) {
				$arTmp[$arPediod["UF_XML_ID"]] = array(
					"NAME" => $arPediod["UF_NAME"],
					"FROM" => $arPediod["UF_VALUE_FROM"],
					"TO" => $arPediod["UF_VALUE_TO"]
				);
			}
			CRegistry::add("PERIOD_PRICE", $arTmp);
		}
		
		return CRegistry::get("PERIOD_PRICE");
	}
	
	public static function isUserContentManager() {
		$bResult = false;
		
		global $USER;
		
		$arUserGroups = $USER->GetUserGroupArray();
		if(in_array(12, $arUserGroups)) {
			$bResult = true;
		}
		
		return $bResult;
	}
	
	public static function formatNumber($intNumber) {
		return \ig\CFormat::formatNumber($intNumber);
	}
	
	public static function getHLGroupID() {
		if(!CRegistry::exists("HIGHLOAD_IBLOCK_ID__GROUP")) {
			CRegistry::add("HIGHLOAD_IBLOCK_ID__GROUP", CHighload::getHighloadBlockIDByName('Group'));
		}
		
		return CRegistry::get("HIGHLOAD_IBLOCK_ID__GROUP");
	}
	
	public static function getHLPropertyGroupID() {
		if(!CRegistry::exists("HIGHLOAD_IBLOCK_ID__PROPERTY_GROUP")) {
			CRegistry::add("HIGHLOAD_IBLOCK_ID__PROPERTY_GROUP", CHighload::getHighloadBlockIDByName('PropertyGroup'));
		}
		
		return CRegistry::get("HIGHLOAD_IBLOCK_ID__PROPERTY_GROUP");
	}
	
	public static function getFirst($value) {
		if(is_array($value))
			return array_shift($value);
		else return $value;
	}
	
	public static function getPropertyIDByCode($strCode, $intIblockID=false) {
		$strCode = trim($strCode);
		$intIblockID = intval($intIblockID);
		
		$arFilter = array("CODE" => $strCode);
		if($intIblockID>0) $arFilter["IBLOCK_ID"] = $intIblockID;
		
		$rsProp = \CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), $arFilter);
		while ($arProp = $rsProp->GetNext()) {
			return $arProp["ID"];
		}
		
		return '';
	}
	
	public static function getProductByOfferID($intOfferID, $arParams) {
		$intOfferID = intval($intOfferID);
		
		$arResult = array();
		
		if($intOfferID>0) {
			if(!isset($arParams["SELECT"])) {
				$arParams["SELECT"] = array("*", "PROPERTY_*");
			}
			
			echo __FILE__.': '.__LINE__.'<pre>'.print_r($intOfferID, true).'</pre>';
			$rsI = \CIBlockElement::GetList(Array(), array(
				"ID" => $intOfferID
			), false, false, $arParams["SELECT"]);
			if ($arI = $rsI->Fetch()) {
				echo __FILE__.': '.__LINE__.'<pre>'.print_r($arI, true).'</pre>';
				$arResult = $arI;
			}
		}
		
		return $arResult;
	}
	
	public static function getIblockIdByCode($strCode, $arAdditionalFilter = array()) {
		if(!CRegistry::exists("IBLOCK_ID__".$strCode)) {
			$arFilter = array("CODE" => $strCode);
			if(!empty($arAdditionalFilter) && is_array($arAdditionalFilter))
				$arFilter = array_merge($arAdditionalFilter, $arFilter);
			
			$arIblock = \Bitrix\Iblock\IblockTable::getList(
				array(
					"filter" => $arFilter
				)
			) -> fetch();
			
			CRegistry::add("IBLOCK_ID__".$strCode, $arIblock["ID"]);
		}
		
		return CRegistry::get("IBLOCK_ID__".$strCode);
	}
	
	public static function getEnumID($intIblockID, $strCode, $strName) {
		$rsEnum = \CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$intIblockID, "CODE"=>$strCode, "VALUE"=>$strName));
		$arEnum = $rsEnum -> Fetch();
		
		return $arEnum["ID"];
	}
	
	public static function isPropertyEnabledForGroup($strPropertyCode, $varGroup) {
		$arPropertyTree = self::getPropertyTree();
		if(!is_int($varGroup) && strlen($varGroup)>0) {
			$intGroup = \ig\CHighload::getIDByXmlID($varGroup, \ig\CHelper::getHLGroupID());
		} else {
			$intGroup = $varGroup;
		}
		
		return in_array($intGroup, $arPropertyTree[$strPropertyCode]["GROUP"]);
	}
	
	public static function getPropertyTree($arParams = array()) {
		$strHash = 'propertyTree_'.md5(serialize($arParams));
		
		if(!\ig\CRegistry::exists($strHash)) {
			$arResult = array();
			
			$arPropertyIDToCode = array();
			
			$arResult["HEIGHT_NOW_EXT"] = array(
				"NAME" => "Высота сейчас",
				"GROUP" => array(1, 4, 5, 9, 10, 11, 12)
			);
			
			$obProperty = CHighload::getList(CHighload::getHighloadBlockIDByName("PropertyGroup"), array(), array("UF_NAME", "ID", "UF_CODE", "UF_POINTER"), array(), true);
			while($arProperty = $obProperty -> Fetch()) {
				$arPropertyIDToCode[$arProperty["ID"]] = $arProperty["UF_CODE"];
				
				$arResult[$arProperty["UF_CODE"]] = array(
					"NAME" => $arProperty["UF_NAME"],
					"CODE" => $arProperty["UF_CODE"],
					"GROUP" => $arProperty["UF_POINTER"],
					"VALUES" => array()
				);
			}
			
			$arValuesFilter = array();
			if($arParams["TYPE"]>0) {
				$arValuesFilter["UF_POINTER"] = $arParams["TYPE"];
			}
			$obPropertyValue = CHighload::getList(CHighload::getHighloadBlockIDByName("PropertyValues"), $arValuesFilter, array("UF_NAME", "UF_POINTER", "UF_PROPERTY", "ID", "UF_XML_ID", "UF_ICON"), array("order" => array("UF_SORT" => "ASC")), true);
			while($arPropertyValue = $obPropertyValue -> Fetch()) {
				$arTmp = array(
					"NAME" => $arPropertyValue["UF_NAME"],
					"XML_ID" => $arPropertyValue["UF_XML_ID"],
					"ID" => $arPropertyValue["ID"],
					"LINK" => $arPropertyValue["UF_POINTER"]
				);
				
				if(!empty($arPropertyValue["UF_ICON"])) {
					if(strpos($arPropertyValue["UF_ICON"], "#") === 0) {
						$strIcon = SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg'.$arPropertyValue["UF_ICON"];
					} else {
						$strIcon = $arPropertyValue["UF_ICON"];
					}
					
					$arTmp["ICON"] = $strIcon;
				}
				
				if($arParams["ENABLE_TYPE"]) {
					if(is_array($arParams["ENABLE_TYPE"])) {
						$arIntersect = array_intersect($arPropertyValue["UF_POINTER"], $arParams["ENABLE_TYPE"]);
						if(empty($arIntersect)) $arTmp["DISABLED"] = 'Y';
					} else {
						if(!in_array($arParams["ENABLE_TYPE"], $arPropertyValue["UF_POINTER"])) {
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
	
	public static function getOfferName($intParentID) {
		$intParentID = intval($intParentID);
		$strResult = '';
		
		if($intParentID>0) {
			\Bitrix\Main\Loader::includeModule("iblock");
			
			$arTmp = array();
			
			$rsI = \CIBlockElement::GetList(false, array(
				"IBLOCK_ID" => CHelper::getIblockIdByCode('catalog'),
				"ID" => $intParentID
			), false, false, array(
				"ID", "IBLOCK_ID", "NAME", "PROPERTY_IS_RUSSIAN", "PROPERTY_IS_VIEW", "IBLOCK_SECTION_ID", "PROPERTY_NAME_LAT"
			));
			if ($arI = $rsI->GetNext()) {
				$rsNav = \CIBlockSection::GetNavChain($arI["IBLOCK_ID"], $arI["IBLOCK_SECTION_ID"], array("NAME"));
				while($arNav = $rsNav -> Fetch()) {
					$arTmp[] = $arNav["NAME"];
				}
				
				if(isset($arTmp[1])) $arTmp[1] = CUtil::lowercaseString($arTmp[1]);
				
				if(empty($arI["PROPERTY_IS_VIEW_VALUE"])) {
					if(empty($arI["PROPERTY_IS_RUSSIAN_VALUE"])) {
						$arTmp[] = "'".$arI["PROPERTY_NAME_LAT_VALUE"]."'";
					} else {
						$arTmp[] = "'".$arI["NAME"]."'";
					}
				}
				
				$strResult = implode(' ', $arTmp);
			}
		}
		
		return $strResult;
	}
	
	public static function getGroupByCatalogID($intID) {
		$rsI = \CIBlockElement::GetList(Array(), array(
			"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("catalog"),
			"ID" => $intID
		), false, false, array(
			"ID", "IBLOCK_ID", "PROPERTY_GROUP"
		));
		if ($arI = $rsI->GetNext()) {
			if(!empty($arI["PROPERTY_GROUP_VALUE"])) {
				$arValue = array_shift(CHighload::getList(CHighload::getHighloadBlockIDByName("Group"), array("UF_XML_ID" => $arI["PROPERTY_GROUP_VALUE"]), array("ID")));
				
				if($arValue["ID"]>0) {
					return $arValue["ID"];
				}
			}
		}
		
		return false;
	}
}