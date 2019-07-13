<?
namespace ig;

class CFavorite {
	private static $instance;
	
	private function __construct()
	{
		if(!is_array($_SESSION["SK_FAVORITE"])) {
			$_SESSION["SK_FAVORITE"] = array();
		}
	}
	
	public static function getInstance()
	{
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function addToFavorite($intID) {
		$intID = intval($intID);
		
		if($intID>0 && !in_array($intID, $_SESSION["SK_FAVORITE"])) {
			$_SESSION["SK_FAVORITE"][] = $intID;
		}
	}
	
	public function removeFromFavorite($intID) {
		$intID = intval($intID);
		
		if($intID>0 && in_array($intID, $_SESSION["SK_FAVORITE"])) {
			unset($_SESSION["SK_FAVORITE"][array_search($intID, $_SESSION["SK_FAVORITE"])]);
		}
	}
	
	public function getFavorite() {
		$arResult = array();
		
		foreach($_SESSION["SK_FAVORITE"] as $intID) {
			$arResult[] = intval($intID);
		}
		
		return $arResult;
	}
	
	public function getFavoriteCnt() {
		return count($_SESSION["SK_FAVORITE"]);
	}
}