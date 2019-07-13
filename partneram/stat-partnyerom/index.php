<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Стать партнёром");
?>
<?$APPLICATION->IncludeComponent(
	"ig:partner.add",
	"",
	array(
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>