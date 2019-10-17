<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule("sale");

$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

$intID = intval($_REQUEST["offerID"]);
$intQuantity = intval($_REQUEST["quantity"]);

$arResult = array();

if($intID > 0) {
	if($intQuantity == 0) {
		$basketItems = $basket->getBasketItems();
		foreach ($basket as $basketItem) {
			if($basketItem->getField('PRODUCT_ID') == $intID) {
				$basket->getItemById($basketItem->getField('ID'))->delete();
				$basket->save();
				
				break;
			}
		}
	} else {
		if ($item = $basket->getExistsItem('catalog', $intID)) {
			$item->setField('QUANTITY', $intQuantity);
		} else {
			$item = $basket->createItem('catalog', $intID);
			$item->setFields(array(
				'QUANTITY' => $intQuantity,
				'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
				'LID'      => \Bitrix\Main\Context::getCurrent()->getSite(),
				'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
			));
		}
	}
	
	$basket->save();
	
	//$arResult["quantity"] = count($basket->getQuantityList());
	
	$arParams = array();
	if($_REQUEST["getDiscount"] == 'Y') {
		$arParams["DISCOUNT"] = 'Y';
	}
	echo json_encode(\ig\CHelper::getDynamicData($arParams));
} else {
	$APPLICATION->IncludeComponent("ig:cart.list", "", Array(
		"AJAX_MODE"                       => "N",
		"AJAX_OPTION_ADDITIONAL"          => "",
		"AJAX_OPTION_HISTORY"             => "N",
		"AJAX_OPTION_JUMP"                => "N",
		"AJAX_OPTION_STYLE"               => "Y",
		"DISPLAY_BOTTOM_PAGER"            => "Y",
		"DISPLAY_TOP_PAGER"               => "N",
		"MESSAGE_404"                     => "",
		"PAGER_BASE_LINK_ENABLE"          => "N",
		"PAGER_DESC_NUMBERING"            => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL"                  => "N",
		"PAGER_SHOW_ALWAYS"               => "N",
		"PAGER_TEMPLATE"                  => ".default",
		"PAGER_TITLE"                     => "Новости",
		"SET_STATUS_404"                  => "N",
		"SHOW_404"                        => "N"
	));
}

