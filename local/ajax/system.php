<?

if(isset($_REQUEST["igs-action"])) {
	$strAction = trim($_REQUEST["igs-action"]);
} else {
	$strAction = trim($_REQUEST["action"]);
}

if(!empty($strAction)) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	$arResult = array();
	if($strAction == 'getContacts') {
		$APPLICATION->IncludeComponent(
			"ig:contacts",
			"",
			array(
			),
			false
		);
	} elseif($strAction == 'getOrderFloatBar') {
		$APPLICATION->IncludeComponent(
			"ig:sale.order",
			"",
			array(
				"PAY_FROM_ACCOUNT" => "N",
				"COUNT_DELIVERY_TAX" => "N",
				"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
				"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
				"ALLOW_AUTO_REGISTER" => "Y",
				"SEND_NEW_USER_NOTIFY" => "N",
				"PATH_TO_BASKET" => "/personal/order/make/",
				"PATH_TO_PERSONAL" => "/personal/order/make/",
				"PATH_TO_PAYMENT" => "/personal/order/payment/",
				"SET_TITLE" => "Y",
				"DELIVERY_NO_SESSION" => "N",
				"COMPONENT_TEMPLATE" => "order",
				"ALLOW_APPEND_ORDER" => "Y",
				"DELIVERY_NO_AJAX" => "N",
				"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
				"TEMPLATE_LOCATION" => "popup",
				"SPOT_LOCATION_BY_GEOIP" => "Y",
				"DELIVERY_TO_PAYSYSTEM" => "p2d",
				"SHOW_VAT_PRICE" => "Y",
				"USE_PREPAYMENT" => "N",
				"COMPATIBLE_MODE" => "N",
				"USE_PRELOAD" => "N",
				"ALLOW_USER_PROFILES" => "N",
				"ALLOW_NEW_PROFILE" => "N",
				"TEMPLATE_THEME" => "blue",
				"SHOW_ORDER_BUTTON" => "always",
				"SHOW_TOTAL_ORDER_BUTTON" => "N",
				"SHOW_PAY_SYSTEM_LIST_NAMES" => "N",
				"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
				"SHOW_DELIVERY_LIST_NAMES" => "Y",
				"SHOW_DELIVERY_INFO_NAME" => "Y",
				"SHOW_DELIVERY_PARENT_NAMES" => "Y",
				"SHOW_STORES_IMAGES" => "Y",
				"SKIP_USELESS_BLOCK" => "Y",
				"BASKET_POSITION" => "before",
				"SHOW_BASKET_HEADERS" => "N",
				"DELIVERY_FADE_EXTRA_SERVICES" => "N",
				"SHOW_COUPONS_BASKET" => "N",
				"SHOW_COUPONS_DELIVERY" => "N",
				"SHOW_COUPONS_PAY_SYSTEM" => "N",
				"SHOW_NEAREST_PICKUP" => "N",
				"DELIVERIES_PER_PAGE" => "9",
				"PAY_SYSTEMS_PER_PAGE" => "9",
				"PICKUPS_PER_PAGE" => "5",
				"SHOW_PICKUP_MAP" => "N",
				"SHOW_MAP_IN_PROPS" => "N",
				"PICKUP_MAP_TYPE" => "yandex",
				"PROPS_FADE_LIST_3" => array(
				),
				"USER_CONSENT" => "Y",
				"USER_CONSENT_ID" => "0",
				"USER_CONSENT_IS_CHECKED" => "Y",
				"USER_CONSENT_IS_LOADED" => "N",
				"ACTION_VARIABLE" => "soa-action",
				"PATH_TO_AUTH" => "/auth/",
				"DISABLE_BASKET_REDIRECT" => "N",
				"EMPTY_BASKET_HINT_PATH" => "/",
				"USE_PHONE_NORMALIZATION" => "Y",
				"PRODUCT_COLUMNS_VISIBLE" => array(
					0 => "PREVIEW_PICTURE",
					1 => "PROPS",
				),
				"ADDITIONAL_PICT_PROP_2" => "-",
				"ADDITIONAL_PICT_PROP_8" => "-",
				"ADDITIONAL_PICT_PROP_10" => "-",
				"ADDITIONAL_PICT_PROP_16" => "-",
				"ADDITIONAL_PICT_PROP_18" => "-",
				"BASKET_IMAGES_SCALING" => "adaptive",
				"SERVICES_IMAGES_SCALING" => "adaptive",
				"PRODUCT_COLUMNS_HIDDEN" => array(
				),
				"HIDE_ORDER_DESCRIPTION" => "N",
				"USE_YM_GOALS" => "N",
				"USE_ENHANCED_ECOMMERCE" => "N",
				"USE_CUSTOM_MAIN_MESSAGES" => "N",
				"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
				"USE_CUSTOM_ERROR_MESSAGES" => "N"
			),
			false
		);
	} elseif($strAction == 'getDynamicData') {
		$arResult = \ig\CHelper::getDynamicData();
	} elseif($strAction == 'getOfferName') {
		$intParentID = intval($_REQUEST["intID"]);
		
		$arResult = array("RESULT" => "OK", "NAME" => \ig\CHelper::getOfferName($intParentID));
	} elseif($strAction == 'translit') {
		$strText = trim($_REQUEST["strText"]);
		$strDirection = trim($_REQUEST["strDirection"]);
		
		$arResult = array(
			"TEXT" => \ig\CUtil::translitText($strText, $strDirection),
			"RESULT" => "OK"
		);
	} elseif($strAction == 'getProductGroup' && $_REQUEST["intID"]>0) {
		CModule::IncludeModule("iblock");
		$intID = intval($_REQUEST["intID"]);
		$intGroupID = 0;
		
		if($intID>0) {
			$intGroupID = \ig\CHelper::getGroupByCatalogID($intID);
		}
		
		if($intGroupID>0) {
			$arResult = array(
				"RESULT" => "OK",
				"GROUP" => $intGroupID
			);
		} else {
			$arResult = array(
				"RESULT" => "ERROR",
				"GROUP" => 0
			);
		}
	} elseif($strAction == 'getSortDefaultName' && $_REQUEST["intSectionID"]>0) {
		CModule::IncludeModule("iblock");
		
		$arTmp = array();
		$rsNav = CIBlockSection::GetNavChain($_REQUEST["IBLOCK_ID"], $_REQUEST["intSectionID"], array("NAME", "DEPTH_LEVEL"));
		while($arNav = $rsNav -> Fetch()) {
			if($arNav["DEPTH_LEVEL"] == 2)
				$arTmp[] = \ig\CUtil::lowercaseString($arNav["NAME"]);
			else $arTmp[] = $arNav["NAME"];
		}
		
		$arResult = array("RESULT" => "OK", "NAME" => implode(" ", $arTmp));
	}
	
	if($arResult) {
		echo json_encode($arResult);
	}
}