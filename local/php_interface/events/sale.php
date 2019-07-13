<?php
namespace ig;

AddEventHandler("sale", "OnOrderNewSendEmail", Array("\\ig\\CSaleEvents", "OnOrderNewSendEmailHandler"), 100);


class CSaleEvents {
	function OnOrderNewSendEmailHandler($orderID, &$eventName, &$arFields)
	{
		\Bitrix\Main\Loader::includeModule('sale');
		
		$obOrder = \Bitrix\Sale\Order::load($orderID);
		$obPropertyCollection = $obOrder->getPropertyCollection();
		
		$arDelivery = \Bitrix\Sale\Delivery\Services\Manager::getById(\ig\CUtil::getFirst(array_unique($obOrder -> getDeliverySystemId())));
		
		$arOrderData = array(
			"PROPERTY_NAME" => array(
				"TITLE" => 'Клиент',
				"VALUE" => $obPropertyCollection->getPayerName()->getViewHtml()
			),
			"PROPERTY_EMAIL" => array(
				"TITLE" => 'Email',
				"VALUE" => $obPropertyCollection->getUserEmail()->getViewHtml()
			),
			"PROPERTY_PHONE" => array(
				"TITLE" => 'Телефон',
				"VALUE" => $obPropertyCollection -> getPhone()->getViewHtml()
			),
			"PROPERTY_ADDRESS" => array(
				"TITLE" => 'Адрес доставки',
				"VALUE" => $obPropertyCollection -> getAddress()->getViewHtml()
			),
			"COMMENT" => array(
				"TITLE" => 'Комментарий',
				"VALUE" => $obOrder -> getField("USER_DESCRIPTION")
			),
			"DELIVERY" => array(
				"TITLE" => 'Тип доставки',
				"VALUE" => $arDelivery["NAME"]
			),
		);
		
		$arResult = array();
		foreach($arOrderData as $arOrderProp) {
			if(strlen($arOrderProp["VALUE"])>0) {
				$arResult[] = $arOrderProp["TITLE"].': '.$arOrderProp["VALUE"];
			}
		}
		
		$arFields["PRICE"] = \CCurrencyLang::CurrencyFormat(floatval($obOrder -> getField("PRICE")) - floatval($obOrder -> getField("PRICE_DELIVERY")), $obOrder -> getField("CURRENCY"));
		
		$arFields["SALE_NEW_ORDER_FORM_DATA"] = implode('<br>', $arResult);
	}
}