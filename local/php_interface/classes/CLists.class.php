<?php
namespace ig;

class CLists {
	private static $_instance = null,
		$arLists = array();
	
	public static function getMonth($intMonth = 0) {
		$arList = self::getInstance() -> getList("month");
		
		if(!empty($intMonth))
			return $arList[$intMonth];
		else return $arList;
	}
	
	private static function getList($strType) {
		return self::$arLists[$strType];
	}
	
	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new self;
			
			self::init();
		}
		
		return self::$_instance;
	}
	
	private static function init()
	{
		self::$arLists["month"] = array(
			1  => "Январь",
			2  => "Февраль",
			3  => "Март",
			4  => "Апрель",
			5  => "Май",
			6  => "Июнь",
			7  => "Июль",
			8  => "Август",
			9  => "Сентябрь",
			10 => "Октябрь",
			11 => "Ноябрь",
			12 => "Декабрь"
		);
	}
	
	private function __wakeup()
	{
	}
	
	private function __construct()
	{
	}
	
	private function __clone()
	{
	}
}