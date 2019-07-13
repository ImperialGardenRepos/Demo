<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Команда");
?>
<?$APPLICATION->IncludeComponent(
	"ig:section.list",
	"team",
	Array(
		
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => 360000,
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"COUNT_ELEMENTS" => "N",
			"IBLOCK_ID" => 21,
			"IBLOCK_TYPE" => "onas",
			"SECTION_CODE" => "",
			"SECTION_FIELDS" => array("NAME", "CODE", ""),
			"SECTION_ID" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array("", ""),
			"TOP_DEPTH" => 1,
			"ADD_ELEMENTS" => "Y"
		)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>