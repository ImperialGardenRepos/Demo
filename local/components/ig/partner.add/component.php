<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

global $INTRANET_TOOLBAR;

use Bitrix\Main\Application,
	\Bitrix\Main\IO,
	Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Main\Data\Cache;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$request = Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest());

//\ig\CHelper::addRecaptcha();

if($_REQUEST["frmBecomePartnerSent"] == 'Y') {
	if(empty($_POST['hash'])) {
		if(!isset($_SESSION["IG_UPLOAD_HASH"])) {
			$_SESSION["IG_UPLOAD_HASH"] = time();
		}
		
		$strHash = $_SESSION["IG_UPLOAD_HASH"];
	} else {
		$strHash = intval($_POST['hash']);
	}
	
	if (empty($_POST['files_count'])) {
		unset($_SESSION['files_'.$strHash]);
		unset($_SESSION['files_data_'.$strHash]);
	}
	
	if (!isset($_SESSION['files_'.$strHash])) {
		$_SESSION['files_'.$strHash] = [];
		$_SESSION['files_data_'.$strHash] = [];
	}
	
	if (!empty($_FILES['files']['name'][0])) {
		$path = Application::getDocumentRoot() . "/system/tmp_upload/".$strHash;
		if(!IO\Directory::isDirectoryExists($path)) {
			IO\Directory::createDirectory($path);
		}
		
		$pFile = new IO\File($_FILES["files"]["tmp_name"][0]);
		
		// переносим звгруженные файлы во временную директорию
		$strTmpFilename = randString(10);
		$strTmpPath = Application::getDocumentRoot() . "/system/tmp_upload/".$strHash.'/'.$strTmpFilename;
		$pFile->rename($strTmpPath);
		
		// сохраняем данные о файлах во временной директории
		$_SESSION["files_data_".$strHash][] = array(
			"name" => $_FILES["files"]["name"][0],
			"size" => $_FILES["files"]["size"][0],
			"tmp_name" => $strTmpPath,
			"type" => $_FILES["files"]["type"][0]
		);
		$_SESSION['files_'.$strHash] = array_merge($_SESSION['files_'.$strHash], $_FILES['files']['name']);
	}
	
	if (
		empty($_POST['files_count'])
			||
		(!empty($_SESSION['files_'.$strHash]) && $_POST['files_count'] == count($_SESSION['files_'.$strHash]))) {
		
		// сохраняем данные формы
		$arErrors = array();
		
		$arFormData = $_REQUEST["F"];
		if(empty($arFormData["NAME"])) {
			$arErrors[] = '&mdash; не указано имя';
		}
		
		if(!check_email($arFormData["EMAIL"])) {
			$arErrors[] = '&mdash; указан некорректный почтовый адрес';
		}
		
		if(empty($arFormData["PHONE"])) {
			$arErrors[] = '&mdash; указан некорректный телефон';
		}
		
		if(!empty($arErrors)) {
			$arReply = array(
				"upload_finished" => true,
				"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">Ошибка отправки обращения</h1><p>В ходе отправки возникли следующие ошибки:<br><br>'.implode("<br>", $arErrors).'</p></div>'
			);
		} else {
			$arEventFields = $arFormData;
			$arEventFields["SOURCE_PAGE"] = 'https://'.$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPageParam();
			
			if(CModule::IncludeModule("iblock")) {
				$el = new CIBlockElement;
				
				$rsEnum = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>\ig\CHelper::getIblockIdByCode("become_partner"), "CODE"=>"SUBJECT"));
				while($arEnum = $rsEnum->GetNext()) {
					$arResult["SUBJECT"][$arEnum["ID"]] = $arEnum;
				}
				
				$intIblockID = \ig\CHelper::getIblockIdByCode("become_partner");
				
				$arFields = array(
					"IBLOCK_ID" => $intIblockID,
					"NAME" => $arFormData["NAME"].' ('.$arFormData["PHONE"].', '.$arFormData["EMAIL"].')',
					"ACTIVE" => "Y",
					"PREVIEW_TEXT" => $arFormData["TEXT"],
					"PROPERTY_VALUES" => array(
						"EMAIL" => $arFormData["EMAIL"],
						"NAME" => $arFormData["NAME"],
						"PHONE" => $arFormData["PHONE"],
						"COMPANY" => $arFormData["COMPANY"],
						"FILES" => $_SESSION['files_data_'.$strHash],
						"SUBJECT" => $arFormData["SUBJECT"],
						"LINK" => $arEventFields["SOURCE_PAGE"]
					)
				);
				
				$intElementID = $el->Add($arFields);
			}
			
			$arEventFields["SUBJECT"] = $arResult["SUBJECT"][$arFormData["SUBJECT"]]["VALUE"];
			$arEventFields["IBLOCK_LINK"] = 'https://'.$_SERVER["HTTP_HOST"].'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$intIblockID.'&type=service&ID='.$intElementID.'&lang=ru&find_section_section=-1';
			
			\Bitrix\Main\Mail\Event::send(array(
				"EVENT_NAME" => "IG_PARTNER_ADD",
				"LID" => SITE_ID,
				"C_FIELDS" => $arEventFields
			));
		}
		
		// чистим временную директорию в любом случае
		$strPath = Application::getDocumentRoot() . "/system/tmp_upload/".$strHash;
		IO\Directory::deleteDirectory($strPath);
		
		unset($_SESSION['files_data_'.$strHash]);
		unset($_SESSION['files_'.$strHash]);
		
		$arReply = array(
			"upload_finished" => true,
			"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">'.$arFormData["NAME"].', спасибо! </h1><p>Ваше обращение успешно отправлено.</p></div>'
		);
	} else {
		$arReply = array(
			"session_files" => $_SESSION['files_'.$strHash]
		);
	}
	
	\ig\CUtil::showJsonReply($arReply);
}

if($this->startResultCache()) {
	if(\Bitrix\Main\Loader::includeModule("iblock")) {
		$rsEnum = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>\ig\CHelper::getIblockIdByCode("become_partner"), "CODE"=>"SUBJECT"));
		while($arEnum = $rsEnum->GetNext()) {
			$arResult["SUBJECT"][$arEnum["ID"]] = $arEnum;
		}
	}
	
	$this->includeComponentTemplate();
}
