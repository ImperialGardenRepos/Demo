<?php
//namespace ig;
//
//\Bitrix\Main\EventManager::getInstance()->addEventHandler(
//	'catalog',
//	'\Bitrix\Catalog\Price::OnAfterUpdate',
//	array('\ig\CCatalogEvents', 'OnAfterPriceAddUpdateHandler')
//);
//
//\Bitrix\Main\EventManager::getInstance()->addEventHandler(
//	'catalog',
//	'\Bitrix\Catalog\Price::OnAfterAdd',
//	array('\ig\CCatalogEvents', 'OnAfterPriceAddUpdateHandler')
//);
//
//\Bitrix\Main\EventManager::getInstance()->addEventHandler(
//	'catalog',
//	'\Bitrix\Catalog\Price::OnAfterDelete',
//	array('\ig\CCatalogEvents', 'OnAfterPriceAddUpdateHandler')
//);
//
////
//class CCatalogEvents {
//	function OnAfterPriceAddUpdateHandler(\Bitrix\Main\Event $event) {
//		$intEntityID = $event->getParameter('primary');
//
//		if($intEntityID>0) {
//			$arEntity = \Bitrix\Catalog\PriceTable::getById($intEntityID) -> fetch();
//			echo __FILE__.': '.__LINE__.'<pre>'.print_r($arEntity, true).'</pre>';
//		//	die();
//			$intProdctID = $arEntity["PRODUCT_ID"];
//			$arProduct = \ig\CHelper::getProductByOfferID($intProdctID, array("SELECT" => array("ID", "IBLOCK_ID", "PROPERTY_CML2_LINK", "PROPERTY_CML2_LINK.PROPERTY_PRICE_SORT")));
//			echo __FILE__.': '.__LINE__.'<pre>'.print_r($arProduct, true).'</pre>';
//		}
//
//		//die();
//	}
//}