<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?><?$APPLICATION->IncludeComponent(
	"ig:contacts",
	"",
Array()
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>