<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Фильтр каталога товаров для сада",
	"DESCRIPTION" => "Фильтр каталога товаров для сада",
	"ICON" => "/images/news_detail.gif",
	"SORT" => 30,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "ImperialGarden",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => "Каталог",
			"SORT" => 10
		),
	),
);

?>