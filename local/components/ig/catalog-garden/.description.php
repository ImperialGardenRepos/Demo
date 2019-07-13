<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Каталог товаров для сада",
	"DESCRIPTION" => "Каталог товаров для сада",
	"ICON" => "/images/catalog.gif",
	"COMPLEX" => "Y",
	"SORT" => 10,
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