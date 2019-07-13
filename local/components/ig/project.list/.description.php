<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Список проектов",
	"DESCRIPTION" => "Список проектов",
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
			"ID" => "common",
			"NAME" => "Разное",
			"SORT" => 10
		),
	)
);

?>