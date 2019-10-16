<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Вопросы и ответы - садовый центр Imperial Garden");
$APPLICATION->SetTitle("Вопросы и ответы");
?>
<?$APPLICATION->IncludeComponent(
	"ig:section.list",
	"tabbed-list",
	Array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => 360000,
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"COUNT_ELEMENTS" => "N",
		"IBLOCK_ID" => 14,
		"IBLOCK_TYPE" => "services",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array("NAME", "CODE", ""),
		"SECTION_ID" => "",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array("", ""),
		"TOP_DEPTH" => 1,
		"ADD_ELEMENTS" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>