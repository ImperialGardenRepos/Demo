<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Контакты - садовый центр Imperial Garden");
$APPLICATION->SetTitle("Контакты");
?><?$APPLICATION->IncludeComponent(
	"ig:contacts",
	"",
Array()
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>