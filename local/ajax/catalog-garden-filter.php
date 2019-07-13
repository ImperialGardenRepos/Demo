<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	"ig:catalog-garden.filter",
	"",
Array()
);

//$APPLICATION->IncludeComponent(
//	"ig:catalog-garden.section",
//	"",
//	Array(
//		"AJAX_MODE" => "N",
//		"AJAX_OPTION_ADDITIONAL" => "",
//		"AJAX_OPTION_HISTORY" => "N",
//		"AJAX_OPTION_JUMP" => "N",
//		"AJAX_OPTION_STYLE" => "Y",
//		"DISPLAY_BOTTOM_PAGER" => "Y",
//		"DISPLAY_DATE" => "Y",
//		"DISPLAY_NAME" => "Y",
//		"DISPLAY_PICTURE" => "Y",
//		"DISPLAY_PREVIEW_TEXT" => "Y",
//		"DISPLAY_TOP_PAGER" => "N",
//		"MESSAGE_404" => "",
//		"PAGER_BASE_LINK_ENABLE" => "N",
//		"PAGER_DESC_NUMBERING" => "N",
//		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
//		"PAGER_SHOW_ALL" => "N",
//		"PAGER_SHOW_ALWAYS" => "N",
//		"PAGER_TEMPLATE" => ".default",
//		"PAGER_TITLE" => "Новости",
//		"SET_STATUS_404" => "N",
//		"SHOW_404" => "N"
//	)
//);

$arResponse = array();

$strFilterHtml = \ig\CRegistry::get('catalog-filter_html');
if(!empty($strFilterHtml)) {
	$arResponse["filter_html"] = $strFilterHtml;
}
//$strResultLink = \ig\CRegistry::get('catalog-page_url');
//if(!empty($strResultLink)) {
//	$arResponse["page_url"] = $strResultLink;
//}
//
//$strSearchHtml = \ig\CRegistry::get('catalog-html');
//if(!empty($strFilterHtml)) {
//	$arResponse["html"] = $strSearchHtml;
//}

$strResultsHtml = \ig\CRegistry::get('catalog-results_html');
if(!empty($strResultsHtml)) {
	$arResponse["results_html"] = $strResultsHtml;
}

echo json_encode($arResponse);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
