<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$intID = intval($_REQUEST["offerID"]);

if($intID>0) {
	if($_REQUEST["quantity"] == 1) {
		\ig\CFavorite::getInstance() -> addToFavorite($intID);
		
	} else {
		\ig\CFavorite::getInstance() -> removeFromFavorite($intID);
	}
	
//	echo json_encode(array('quantity' => \ig\CFavorite::getInstance() -> getFavoriteCnt()));
	echo json_encode(\ig\CHelper::getDynamicData());
} else {
	$APPLICATION->IncludeComponent("ig:favorite.list", "", Array(
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