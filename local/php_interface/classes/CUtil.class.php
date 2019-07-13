<?
namespace ig;

class CUtil {
	private static
		$arTranslitToEn = array("Щ"=>"Shch", "щ"=>"shch", "Ё"=>"Yo", "ё"=>"yo", "Ж"=>"Zh", "ж"=>"zh", "Х"=>"Kh", "х"=>"kh", "Ц"=>"Ts", "ц"=>"ts", "Ч"=>"Ch", "ч"=>"ch", "Ш"=>"Sh", "ш"=>"sh", "Э"=>"Je", "э"=>"je", "Ю"=>"Yu", "ю"=>"yu", "Я"=>"Ya", "я"=>"ya", "А"=>"A", "а"=>"a", "Б"=>"B", "б"=>"b", "В"=>"V", "в"=>"v", "Г"=>"G", "г"=>"g", "Д"=>"D", "д"=>"d", "Е"=>"E", "е"=>"e", "З"=>"Z", "з"=>"z", "И"=>"I", "и"=>"i", "Й"=>"J", "й"=>"j", "К"=>"K", "к"=>"k", "Л"=>"L", "л"=>"l", "М"=>"M", "м"=>"m", "Н"=>"N", "н"=>"n", "О"=>"O", "о"=>"o", "П"=>"P", "п"=>"p", "Р"=>"R", "р"=>"r", "С"=>"S", "с"=>"s", "Т"=>"T", "т"=>"t", "У"=>"U", "у"=>"u", "Ф"=>"F", "ф"=>"f", "Ы"=>"Y", "ы"=>"y", "ь"=>"", "ъ"=>""),
		$arTranslitToRu = array("Shch"=>"Щ", "shch"=>"щ", "Sch"=>"Ш", "sch"=>"ш", "Ch"=>"Ч", "ch"=>"ч", "Ck"=>"К", "ck"=>"к", "Je"=>"Э", "je"=>"э", "Kh"=>"Х", "kh"=>"х", "Sh"=>"Ш", "sh"=>"ш", "Th"=>"Т", "th"=>"т", "Ts"=>"Ц", "ts"=>"ц", "Wh"=>"В", "wh"=>"в", "Wh"=>"В", "wh"=>"в", "Ya"=>"Я", "ya"=>"я", "Yo"=>"Ю", "yo"=>"ю", "Yu"=>"Ю", "yu"=>"ю", "Zh"=>"Ж", "zh"=>"ж", "A"=>"А", "a"=>"а", "B"=>"Б", "b"=>"б", "c"=>"к", "C"=>"К", "D"=>"Д", "d"=>"д", "E"=>"Е", "e"=>"е", "F"=>"Ф", "f"=>"ф", "G"=>"Г", "g"=>"г", "H"=>"Х", "h"=>"х", "I"=>"И", "i"=>"и", "J"=>"Дж", "j"=>"дж", "K"=>"К", "k"=>"к", "L"=>"Л", "l"=>"л", "M"=>"М", "m"=>"м", "N"=>"Н", "n"=>"н", "O"=>"О", "o"=>"о", "P"=>"П", "p"=>"п", "Q"=>"К", "q"=>"к", "R"=>"Р", "r"=>"р", "S"=>"С", "s"=>"с", "T"=>"Т", "t"=>"т", "U"=>"У", "u"=>"у", "V"=>"В", "v"=>"в", "W"=>"В", "w"=>"в", "X"=>"Кс", "x"=>"кс", "Y"=>"И", "y"=>"и", "Z"=>"З", "z"=>"з");
	
	private static $switch = array(
		"а" => "f", "А" => "F", "б" => ",", "Б" => "<",
		"в" => "d", "В" => "D", "г" => "u", "Г" => "D",
		"д" => "l", "Д" => "L", "е" => "t", "Е" => "T",
		"ё" => "`", "Ё" => "~", "ж" => ";", "Ж" => ":",
		"з" => "p", "З" => "P", "и" => "b", "И" => "B",
		"й" => "q", "Й" => "Q", "к" => "r", "К" => "R",
		"л" => "k", "Л" => "K", "м" => "v", "М" => "V",
		"н" => "y", "Н" => "Y", "о" => "j", "О" => "J",
		"п" => "g", "П" => "G", "р" => "h", "Р" => "H",
		"с" => "c", "С" => "C", "т" => "n", "Т" => "N",
		"у" => "e", "У" => "E", "ф" => "a", "Ф" => "A",
		"х" => "[", "Х" => "{", "ц" => "w", "Ц" => "W",
		"ч" => "x", "Ч" => "X", "ш" => "i", "Ш" => "I",
		"щ" => "o", "Щ" => "O", "ъ" => "]", "Ъ" => "}",
		"ы" => "s", "Ы" => "S", "ь" => "m", "Ь" => "M",
		"э" => "'", "Э" => "\"", "ю" => ".", "Ю" => ">",
		"я" => "z", "Я" => "Z", "," => "?", "." => "/"
	);
	
	/**
	 * Для перевода из кириллической раскладки в латиницу
	 * @param string $string - строка для перевода
	 *
	 * @return string
	 */
	public static function fromCyrillic( $string ){
		return
			strtr( $string, self::$switch  );
	}
	
	/**
	 * Для перевода из латинской раскладки в кириллическую
	 * @param string $string
	 *
	 * @return string
	 */
	public function toCyrillic( $string ){
		return
			strtr( $string, array_flip( self::$switch )  );
	}
	
	private function getTranslitArray($strDirection) {
		if($strDirection == 'en2ru') {
			return self::$arTranslitToRu;
		} else {
			return self::$arTranslitToEn;
		}
	}
	
	public static function getSymCode($strText) {
		// транслит со своими правилами
		$strResult = \ig\CUtil::translitText($strText);
		
		// регистр, замена символов
		$arTranslitParams = array(
			"max_len" => 100,
			"change_case" => 'L',
			"replace_space" => '-',
			"replace_other" => '-',
			"delete_repeat_replace" => true,
			"safe_chars" => '',
		);
		$strResult = \CUtil::translit($strResult, "ru", $arTranslitParams);
		
		return $strResult;
	}
	
	public static function translitText($strText, $strDirection = 'ru2en') {
		$strDirection = $strDirection=='en2ru' ? 'en2ru' : 'ru2en';
		
		$arReadyTranslitTable = self::getTranslitArray($strDirection);
		
		$strResult = str_replace(array_keys($arReadyTranslitTable), array_values($arReadyTranslitTable), $strText);
		
		return $strResult;
	}
	
	/**
	 * переводит первую букву строки в строчную
	 * @param string $strText исходный текст
	 * @return string результат трансформации
	 */
	public static function lowercaseString($strText) {
		return Tolower(substr($strText, 0, 1)) . substr($strText, 1);
	}
	
	/**
	 * переводит первую букву строки в заглавную
	 * @param string $strText исходный текст
	 * @return string результат трансформации
	 */
	public static function capitalizeString($strText)
	{
		return ToUpper(substr($strText, 0, 1)) . substr($strText, 1);
	}
	
	public static function getFirst($arData) {
		return array_shift($arData);
	}
	
	public static function prepareImageUrl($strUrl) {
		return str_replace(' ', '%20', $strUrl);
	}
	
	public static function getFileRecursive($sFileName, $sFilePath = '') {
		$strResult = '';
		
		$io = \CBXVirtualIo::GetInstance();
		
		if(empty($sFilePath)) {
			$sRealFilePath = $_SERVER["REAL_FILE_PATH"];
			
			if (strlen($sRealFilePath) > 0)
			{
				$slash_pos = strrpos($sRealFilePath, "/");
				$sFilePath = substr($sRealFilePath, 0, $slash_pos+1);
			}
			// otherwise use current
			else
			{
				$sFilePath = $GLOBALS["APPLICATION"]->GetCurDir();
			}
		}
		
		$bFileFound = $io->FileExists($_SERVER['DOCUMENT_ROOT'].$sFilePath.$sFileName);
		
		if (!$bFileFound && $sFilePath != "/")
		{
			$finish = false;
			
			do
			{
				// back one level
				if (substr($sFilePath, -1) == "/") $sFilePath = substr($sFilePath, 0, -1);
				$slash_pos = strrpos($sFilePath, "/");
				$sFilePath = substr($sFilePath, 0, $slash_pos+1);
				
				$bFileFound = $io->FileExists($_SERVER['DOCUMENT_ROOT'].$sFilePath.$sFileName);
				
				// if we are on the root - finish
				$finish = $sFilePath == "/";
			} while (!$finish && !$bFileFound);
		}
		
		if($bFileFound) {
			$strResult = $sFilePath.$sFileName;
		}
		
		return $strResult;
	}
	
	public static function smartTrim($text, $max_len, $trim_middle = false, $trim_chars = '...')
	{ // smartly trims text to desired length
		$text = trim($text);
		
		if (strlen(strip_tags($text)) < $max_len) {
			
			return strip_tags($text);
			
		} elseif ($trim_middle) {
			
			$hasSpace = strpos($text, ' ');
			if (!$hasSpace) {
				$first_half = substr($text, 0, $max_len / 2);
				$last_half = substr($text, -($max_len - strlen($first_half)));
			} else {
				$last_half = substr($text, -($max_len / 2));
				$last_half = trim($last_half);
				$last_space = strrpos($last_half, ' ');
				if (!($last_space === false)) {
					$last_half = substr($last_half, $last_space + 1);
				}
				$first_half = substr($text, 0, $max_len - strlen($last_half));
				$first_half = trim($first_half);
				if (substr($text, $max_len - strlen($last_half), 1) == ' ') {
					$first_space = $max_len - strlen($last_half);
				} else {
					$first_space = strrpos($first_half, ' ');
				}
				if (!($first_space === false)) {
					$first_half = substr($text, 0, $first_space);
				}
			}
			
			return $first_half.$trim_chars.$last_half;
			
		} else {
			
			$trimmed_text = strip_tags($text);
			$trimmed_text = substr($text, 0, $max_len);
			$trimmed_text = trim($trimmed_text);
			if (substr($text, $max_len, 1) == ' ') {
				$last_space = $max_len;
			} else {
				$last_space = strrpos($trimmed_text, ' ');
			}
			if (!($last_space === false)) {
				$trimmed_text = substr($trimmed_text, 0, $last_space);
			}
			return $trimmed_text.$trim_chars;
		}
	}
	
	public static function prepareFormData($arData) {
		$arResult = array();
		
		foreach($arData as $strKey => $varValue) {
			if(is_array()) {
				$arResult[$strKey] = self::prepareFormData($varValue);
			} else {
				$arResult[$strKey] = htmlspecialchars($varValue);
			}
		}
		
		return $arResult;
	}
	
	public static function showJsonReply($varReply) {
		$strReply = json_encode($varReply);
		
		self::showAjaxReply($strReply);
	}
	
	public static function showAjaxReply($strReply) {
		$GLOBALS["APPLICATION"] -> RestartBuffer();
		
		echo $strReply;
		
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
		die();
	}
}