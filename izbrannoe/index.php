<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");
?><?$APPLICATION->IncludeComponent("ig:favorite.list", "full", Array(
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
));?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>