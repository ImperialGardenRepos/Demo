<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Аренда техники");
$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/inc_area/partneram/arenda/index.php"
	), false,  array('HIDE_ICONS' => 'Y')
)
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>