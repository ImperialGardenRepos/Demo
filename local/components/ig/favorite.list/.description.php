<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Список избранного",
	"DESCRIPTION" => "Список избранного",
	"ICON" => "/images/news_list.gif",
	"SORT" => 20,
//	"SCREENSHOT" => array(
//		"/images/post-77-1108567822.jpg",
//		"/images/post-1169930140.jpg",
//	),
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "ImperialGarden",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => GetMessage("T_IBLOCK_DESC_NEWS"),
			"SORT" => 10
		),
	)
);

?>