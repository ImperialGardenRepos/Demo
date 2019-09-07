<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Оптовый отдел - садовый центр Imperial Garden");
$APPLICATION->SetTitle("Оптовый отдел");
$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/inc_area/partneram/opt/opt_index.php"
	), false,  array('HIDE_ICONS' => 'Y')
)
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>