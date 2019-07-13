<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
	return;

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"TOP_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => "Кол-во возвращаемых товаров",
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		),
		"GLOBAL_VARIABLE_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Название глобальной переменной для передачи исходного товара",
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"AJAX_MODE" => array()
	),
);