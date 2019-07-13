<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Товар каталога",
	"DESCRIPTION" => "Товар каталога",
	"ICON" => "/images/news_detail.gif",
	"SORT" => 30,
	"CACHE_PATH" => "Y",
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