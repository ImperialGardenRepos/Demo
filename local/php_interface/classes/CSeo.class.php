<?php
namespace ig;

class CSeo {
	public static function getMetaCatalogDetail($arResult) {
		$arMeta = array();
		
		$arRod = $arResult["SECTION"]["PATH"][0];
		$arVid = $arResult["SECTION"]["PATH"][1];
		
		$strRodRod = (empty($arRod["UF_NAME_ROD"])?$arRod["NAME"]:$arRod["UF_NAME_ROD"]);
		$strVidRod = (empty($arVid["UF_NAME_ROD"])?$arVid["NAME"]:$arVid["UF_NAME_ROD"]);
		$strRodVin = (empty($arRod["UF_NAME_VIN"])?$arRod["NAME"]:$arRod["UF_NAME_VIN"]);
		$strVidVin = (empty($arVid["UF_NAME_VIN"])?$arVid["NAME"]:$arVid["UF_NAME_VIN"]);
		
		if(!empty($arResult["PROPERTIES"]["IS_RUSSIAN"]["VALUE"])) {
			$strName = $arResult["NAME"];
		} else {
			$strName = $arResult["PROPERTIES"]["NAME_LAT"]["VALUE"];
		}
		
		if($arResult["PROPERTIES"]["IS_VIEW"]["VALUE"]) {
			// https://dev.imperialgarden.ru/katalog/rasteniya/bereza/povislaya/
			// h1: Береза повислая
			// title: Купить березу повислую из питомника в СЦ Imperial Garden на Новой Риге
			// keywords: купить березу повислую, береза повислая оптом, береза повислая на Новой Риге
			// description: Продажа березы повислой оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Большой выбор предложений, низние цены
			
			$arMeta["H1"] = $arRod["NAME"].' '.$arVid["NAME"];
			$arMeta["BROWSER_TITLE"] = 'Купить '.ToLower($strRodVin).' '.ToLower($strVidVin).' из питомника в СЦ Imperial Garden на Новой Риге';
			$arMeta["KEYWORDS"] = 'купить '.ToLower($strRodVin).' '.ToLower($strVidVin).', '.ToLower($arRod["NAME"]).' '.ToLower($arVid["NAME"]).' оптом, '.ToLower($arRod["NAME"]).' '.ToLower($arVid["NAME"]).' на Новой Риге';
			$arMeta["DESCRIPTION"] = 'Продажа '.ToLower($strRodRod).' '.ToLower($strVidRod).' оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Большой выбор предложений, низние цены';
		} else {
			//h1: Туя западная 'Brabant' // для русских сортов русское название, а для латинских латинское - как делали у h1
			//title: Купить тую западную Brabant из питомника в СЦ Imperial Garden на Новой Риге
			//keywords: купить тую западную brabant, туя западная brabant оптом, туя западная brabant на Новой Риге
			//description: Продажа туи западной 'Brabant' оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Большой выбор предложений, низние цены
			
			$arMeta["H1"] = \ig\CFormat::formatPlantTitle(
				(!empty($arResult["PROPERTIES"]["IS_VIEW"]["VALUE"])?'':$strName),
				$arResult["SECTION"]["PATH"][0]["NAME"],
				$arResult["SECTION"]["PATH"][1]["NAME"]);
			
			$arMeta["BROWSER_TITLE"] = 'Купить '.ToLower($strRodVin).' '.ToLower($strVidVin).' \''.$strName.'\' из питомника в СЦ Imperial Garden на Новой Риге';
			$arMeta["KEYWORDS"] = 'купить '.ToLower($strRodVin).' '.ToLower($strVidVin).' '.ToLower($strName).', '.ToLower($arRod["NAME"]).' '.ToLower($arVid["NAME"]).' '.ToLower($strName).' оптом, '.ToLower($arRod["NAME"]).' '.ToLower($arVid["NAME"]).' '.ToLower($strName).' на Новой Риге';
			$arMeta["DESCRIPTION"] = 'Продажа '.ToLower($strRodRod).' '.ToLower($strVidRod).' \''.$strName.'\' оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Большой выбор предложений, низние цены';
		}
		
		return $arMeta;
	}
	
	public static function getMetaCatalogRod($arResult) {
		$arMeta = array();
		
//		https://dev.imperialgarden.ru/katalog/rasteniya/tuya/
//		h1: Туя
//		title: Купить тую из питомника в СЦ Imperial Garden на Новой Риге
//		keywords: купить тую, туя оптом, виды и сорта туи, туя на Новой Риге
//		description: Продажа туи оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Сравнение видов и сортов, фильтрация по цене
		
		$strName = $arResult["SECTION"]["NAME"];
		$strRodRod = (empty($arResult["SECTION"]["UF_NAME_ROD"])?$arResult["SECTION"]["NAME"]:$arResult["SECTION"]["UF_NAME_ROD"]);
		$strRodVin = (empty($arResult["SECTION"]["UF_NAME_VIN"])?$arResult["SECTION"]["NAME"]:$arResult["SECTION"]["UF_NAME_VIN"]);
		
		$arMeta["H1"] = $strName;
		$arMeta["BROWSER_TITLE"] = 'Купить '.ToLower($strRodVin).' из питомника в СЦ Imperial Garden на Новой Риге';
		$arMeta["KEYWORDS"] = 'купить '.ToLower($strRodVin).', '.ToLower($strName).' оптом, виды и сорта '.ToLower($strRodRod).', '.ToLower($strName).' на Новой Риге';
		$arMeta["DESCRIPTION"] = 'Продажа '.ToLower($strRodRod).' оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Сравнение видов и сортов, фильтрация по цене';
		
		return $arMeta;
	}
	
	public static function getMetaCatalogVid($arResult) {
		$arMeta = array();
		
//		https://dev.imperialgarden.ru/katalog/rasteniya/tuya/zapadnaya/
//		h1:	Туя западная
//		title: Купить сорта туи западной из питомника в СЦ Imperial Garden на Новой Риге
//		keywords: купить сорта туи западной, туя западная оптом, сорта туи западной, туя западная на Новой Риге
//		description: Продажа сортов туи западной оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Большой выбор сортов, низние цены
		
		$arRod = $arResult["SECTION"]["PATH"][0];
		$arVid = $arResult["SECTION"];
		
		$strRodRod = (empty($arRod["UF_NAME_ROD"])?$arRod["NAME"]:$arRod["UF_NAME_ROD"]);
		$strVidRod = (empty($arVid["UF_NAME_ROD"])?$arVid["NAME"]:$arVid["UF_NAME_ROD"]);
		$strRodVin = (empty($arRod["UF_NAME_VIN"])?$arRod["NAME"]:$arRod["UF_NAME_VIN"]);
		$strVidVin = (empty($arVid["UF_NAME_VIN"])?$arVid["NAME"]:$arVid["UF_NAME_VIN"]);
		
		$arMeta["H1"] = $arRod["NAME"].' '.$arVid["NAME"];
		$arMeta["BROWSER_TITLE"] = 'Купить сорта '.ToLower($strRodRod).' '.ToLower($strVidRod).' из питомника в СЦ Imperial Garden на Новой Риге';
		$arMeta["KEYWORDS"] = 'купить сорта '.ToLower($strRodRod).' '.ToLower($strVidRod).', '.ToLower($arRod["NAME"]).' '.ToLower($arVid["NAME"]).' оптом, сорта '.ToLower($strRodRod).' '.ToLower($strVidRod).', '.ToLower($arRod["NAME"]).' '.ToLower($arVid["NAME"]).' на Новой Риге';
		$arMeta["DESCRIPTION"] = 'Продажа сортов '.ToLower(ToLower($strRodRod)).' '.ToLower(ToLower($strVidRod)).' оптом и в розницу в садовом центре Imperial Garden на Новой Риге. Большой выбор сортов, низние цены';
		
		return $arMeta;
	}
	
	public static function includeJs() {
		$strResult = '';
		
		$strTemplatePath = '/local/templates/ig';
		$strDocRoot = \Bitrix\Main\Application::getDocumentRoot();
		
		$strResultFile = $strTemplatePath.'/build/build_final.js';
		
		$arFiles = array(
			array("PATH"=>$strTemplatePath."/build/build.js", "MINIFY" => false),
			array("PATH"=>'/local/js/helper.js', "MINIFY" => true),
			array("PATH"=>'/local/js/scripts.js', "MINIFY" => true)
		);
		
		$arMTime = array();
		foreach($arFiles as $arFile) {
			$strAbsPath = $strDocRoot.$arFile["PATH"];
			
			if(file_exists($strAbsPath)) {
				$arMTime[] = filemtime($strAbsPath);
			}
		}
		
		$intResultMtime = filemtime($strDocRoot.$strResultFile);
		$intBuildMtime = max($arMTime);
		
		if($intResultMtime<$intBuildMtime) { // rebuild
			$strTmpJs = '';
			foreach($arFiles as $arFile) {
				$strAbsPath = $strDocRoot.$arFile["PATH"];
				if(file_exists($strAbsPath)) {
					$strTmp = file_get_contents($strAbsPath);
					if($arFile["MINIFY"] === true) {
						$strTmp = \JShrink\Minifier::minify($strTmp);
					}
					
					$strTmpJs .= $strTmp."\r\n";
				}
			}
			
			file_put_contents($strDocRoot.$strResultFile, $strTmpJs);
		}
		
		if(\Bitrix\Main\IO\File::isFileExists($strDocRoot.$strResultFile)) {
			$strResult = '<script src='.$strResultFile.' async></script>';
		}
		
		return $strResult;
	}
	
	public static function includeCss() {
		$strResult = '';
		
		$strTemplatePath = '/local/templates/ig';
		$strDocRoot = \Bitrix\Main\Application::getDocumentRoot();
		
		$strResultFile = $strTemplatePath.'/build/build_final.css';
		
		$arFiles = array(
			array("PATH"=>$strTemplatePath."/build/build.css"),
			array("PATH"=>'/local/css/template_styles.css')
		);
		
		$arMTime = array();
		foreach($arFiles as $arFile) {
			$strAbsPath = $strDocRoot.$arFile["PATH"];
			
			if(file_exists($strAbsPath)) {
				$arMTime[] = filemtime($strAbsPath);
			}
		}
		
		$intResultMtime = filemtime($strDocRoot.$strResultFile);
		$intBuildMtime = max($arMTime);
		
		if($intResultMtime<$intBuildMtime) { // rebuild
			$strTmpCss = '';
			foreach($arFiles as $arFile) {
				$strAbsPath = $strDocRoot.$arFile["PATH"];
				if(file_exists($strAbsPath)) {
					$strTmp = file_get_contents($strAbsPath);
					
					$strTmpCss .= $strTmp."\r\n";
				}
			}
			
			file_put_contents($strDocRoot.$strResultFile, $strTmpCss);
		}
		
		if(\Bitrix\Main\IO\File::isFileExists($strDocRoot.$strResultFile)) {
			$strResult = '<link rel="stylesheet" href="'.$strResultFile.'" type="text/css" media="all" />';
		}
		
		return $strResult;
	}
	
	function check404(&$content) {
		if(\CHTTP::GetLastStatus() === '404 Not Found') {
			ob_start();
			
			include($_SERVER["DOCUMENT_ROOT"].'/local/templates/ig/404.php');
			
			$content = ob_get_contents();
			ob_end_clean();
		}
	}
	
	function deleteKernelJs(&$content) {
		global $USER, $APPLICATION;
		if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
		if($APPLICATION->GetProperty("save_kernel") == "Y") return;
		
		$arPatternsToRemove = Array(
			'/<script.+?src=".+?kernel_main\/kernel_main\.js\?\d+"><\/script\>/',
			'/<script.+?src=".+?bitrix\/cache\/js\/s1\/ig\/kernel.+?\?\d+"><\/script\>/', // /bitrix/cache/js/s1/ig/kernel
			'/<script.+?src=".+?bitrix\/js\/main\/loadext.+?\?\d+"><\/script\>/', // /bitrix/cache/js/s1/ig/kernel
			'/<script.+?src=".+?bitrix\/js\/main\/core\/core[^"]+"><\/script\>/',
			'/<script.+?>BX\.(setCSSList|setJSList)\(\[.+?\]\).*?<\/script>/',
			'/<script.+?>if\(\!window\.BX\)window\.BX.+?<\/script>/',
			'/<script[^>]+?>\(window\.BX\|\|top\.BX\)\.message[^<]+<\/script>/',
		);
		
		$content = preg_replace($arPatternsToRemove, "", $content);
		$content = preg_replace("/\n{2,}/", "\n\n", $content);
	}
	
	function deleteKernelCss(&$content) {
		global $USER, $APPLICATION;
		if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
		if($APPLICATION->GetProperty("save_kernel") == "Y") return;
		
		$arPatternsToRemove = Array(
			'/<link.+?href=".+?bitrix\/cache\/css\/s1\/ig\/kernel_main.+?\.css\?\d+"[^>]+>/',
			'/<link.+?href=".+?bitrix\/panel\/main\/.+?\.css\?\d+"[^>]+>/',
			'/<link.+?href=".+?kernel_main\/kernel_main_v1.+?\.css\?\d+"[^>]+>/',
			'/<link.+?href=".+?kernel_main\/kernel_main\.css\?\d+"[^>]+>/',
			'/<link.+?href=".+?bitrix\/js\/main\/core\/css\/core[^"]+"[^>]+>/',
			'/<link.+?href=".+?bitrix\/templates\/[\w\d_-]+\/styles.css[^"]+"[^>]+>/',
			'/<link.+?href=".+?bitrix\/templates\/[\w\d_-]+\/template_styles.css[^"]+"[^>]+>/',
		);
		
		$content = preg_replace($arPatternsToRemove, "", $content);
		$content = preg_replace("/\n{2,}/", "\n\n", $content);
	}
}