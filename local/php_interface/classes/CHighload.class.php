<?
namespace ig;

\Bitrix\Main\Loader::includeModule('highloadblock');

class CHighload {
	public static function getHLID() {
		if(static::$intHLID === 0) {
			static::setHLID();
		}
		
		return static::$intHLID;
	}
	
	public static function getEntityDataClass($intBlockID)
	{
		if(empty($intBlockID) || $intBlockID<1) return false;
		
		$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($intBlockID)->fetch();
		$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();
		
		return $entity_data_class;
	}
	
	public static function getFirst($intHighloadID=false, $arFilter = array(), $arSelect = array("*")) {
		if(empty($intHighloadID)) {
			$intHighloadID = static::getHLID();
		}
		
		$arResult = self::getList($intHighloadID, $arFilter, $arSelect, array("limit" => 1));
		
		return array_shift($arResult);
	}
	
	public static function getList($intHighloadID = false, $arFilter = array("=UF_ACTIVE" => 1), $arSelect = array("*"), $arParams = array(), $bReturnObject = false)
	{
		if(empty($intHighloadID)) {
			$intHighloadID = static::getHLID();
		}
		
		$arQueryParams = array_merge(array(
			'select'  => $arSelect,
			'filter'  => $arFilter,
		), $arParams);
		
		$arResult = self::rawGetList($intHighloadID, $arQueryParams, false, $bReturnObject);
		
		return $arResult;
	}
	
	public static function getIDByXmlID($strXmlID, $intHighloadID) {
		$arItems = self::getList($intHighloadID, array("UF_XML_ID" => $strXmlID));
		foreach($arItems as $arItem) {
			return $arItem["ID"];
		}
	}
	
	public static function rawGetList($intHLBlockID, $arParams, $bGetCount = false, $bReturnObject = false)
	{
		$arResult = array();
		if(
			!in_array("ID", $arParams["select"])
			&&
			!in_array("*", $arParams["select"])
			&&
			!isset($arParams["group"])
			&&
			empty($arParams["runtime"])
		)
			$arParams["select"][] = 'ID';
		
		$entity_data_class = self::getEntityDataClass($intHLBlockID);
		
		if($bGetCount) $arParams["count_total"] = true;
		
		$rsData = $entity_data_class::getList($arParams);
		
		if($bReturnObject)
			return $rsData;
		elseif($bGetCount)
			return $rsData -> getCount();
		else {
			while ($arData = $rsData->fetch()) {
				if(empty($arData["ID"])) {
					$arResult[] = $arData;
				} else $arResult[$arData["ID"]] = $arData;
			}
			
			return $arResult;
		}
	}
	
	public static function getHighloadBlockIDByName($strName) {
		$intResult = 0;
		
		$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(
			array(
				"filter" => array("NAME" => $strName),
				"select" => array("ID")
			)
		);
		if($arData = $rsData -> fetch()) {
			$intResult = $arData["ID"];
		}
		
		return $intResult;
	}
	
	public static function add($intHLBlockID=false, $arFields)
	{
		if(empty($intHLBlockID)) {
			$intHLBlockID = static::getHLID();
		}
		
		$entity_data_class = self::getEntityDataClass($intHLBlockID);
		$rsAdd = $entity_data_class::add($arFields);
		if($rsAdd->isSuccess()) return $rsAdd->getId(); else return $rsAdd->getErrorMessages();
	}
	
	public static function update($intHLBlockID=false, $obFilter, $arFields)
	{
		if(empty($intHLBlockID)) {
			$intHLBlockID = static::getHLID();
		}
		
		$arError = array();
		
		if(!is_array($obFilter) && intval($obFilter)>0) $obFilter = array("=ID" => $obFilter); // обращение по ID
		
		$entity_data_class = self::getEntityDataClass($intHLBlockID);
		$arUpdateItems = self::rawGetList($intHLBlockID, array(
			"filter" => $obFilter,
			"select" => array("ID")
		));
		
		foreach($arUpdateItems as $arUpdateItem) {
			$rsUpdate = $entity_data_class::update($arUpdateItem["ID"], $arFields);
			
			if(!$rsUpdate->IsSuccess()) {
				$obError = $rsUpdate->getErrorMessages();
				
				if(is_array($obError))
					$arError = array_merge($arError, $obError);
				else $arError[] = $rsUpdate->getErrorMessages();
			}
		}
		
		if(empty($arError))
			return true;
		else return $arError;
	}
	
	public static function delete($intHLBlockID, $arFilter) {
		if(empty($intHLBlockID)) {
			$intHLBlockID = static::getHLID();
		}
		
		if(is_numeric($arFilter)) $arFilter = array("ID" => $arFilter);
		
		if(!empty($arFilter)) {
			$arItems = self::getList($arFilter, array("ID"));
			foreach($arItems as $arItem) {
				$entity_data_class = self::getEntityDataClass($intHLBlockID);
				return $entity_data_class::delete($intRecordID);
			}
		}
	}
}