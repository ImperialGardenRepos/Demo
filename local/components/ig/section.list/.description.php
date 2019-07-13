<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Список разделов",
	"DESCRIPTION" => "Список разделов с элементами и навигацией",
	"ICON" => "/images/sections_top_count.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 20,
	"PATH" => array(
		"ID" => "ImperialGarden",
		"CHILD" => array(
			"ID" => "common",
			"NAME" => "Разное",
			"SORT" => 30
		),
	),
);

?>