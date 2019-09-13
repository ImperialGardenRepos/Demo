<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

//ToDo:: FORM remove this after component
if($_REQUEST["frmProjectDetailSent"] == 'Y') {
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
	
	$intObjectID = intval($arFormData["OBJECT_ID"]);
	if($intObjectID>0) {
		if(\Bitrix\Main\Loader::includeModule("iblock")) {
			$rsI = \CIBlockElement::GetList(Array(), array(
				"ACTIVE"    => "Y",
				"ID" => $intObjectID,
				"IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, false, array(
				"ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"
			));
			if ($arI = $rsI->GetNext()) {
				$arFormData["OBJECT"] = '<a href="https://'.SITE_SERVER_NAME.$arI["DETAIL_PAGE_URL"].'">'.$arI["NAME"].'</a>';
				$arFormData["OBJECT_NAME"] = $arI["NAME"];
			}
		}
	}
	
	if(empty($arFormData["OBJECT"])) {
		$arErrors[] = '&mdash; объект не найден';
	}
	
	if(!empty($arErrors)) {
		$strReply = '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">Ошибка отправки обращения</h1><p>В ходе отправки возникли следующие ошибки:<br><br>'.implode("<br>", $arErrors).'</p></div>';
	} else {
		$arEventFields = $arFormData;
		$arEventFields["SOURCE_PAGE"] = 'https://'.$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPageParam();
		
		\Bitrix\Main\Mail\Event::send(array(
			"EVENT_NAME" => "IG_PROJECT_DETAIL",
			"LID" => SITE_ID,
			"C_FIELDS" => $arEventFields
		));
		
		if(CModule::IncludeModule("iblock")) {
			$el = new CIBlockElement;
			
			$arFields = array(
				"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode("form_object_query"),
				"NAME" => $arFormData["NAME"].' ('.$arFormData["PHONE"].', '.$arFormData["EMAIL"].')',
				"ACTIVE" => "Y",
				"PROPERTY_VALUES" => array(
					"EMAIL" => $arFormData["EMAIL"],
					"PHONE" => $arFormData["PHONE"],
					"OBJECT" => $arFormData["OBJECT_NAME"],
					"LINK" => $arEventFields["SOURCE_PAGE"]
				)
			);
			
			$el->Add($arFields);
		}
		
		$strReply = '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">'.$arFormData["NAME"].', спасибо! </h1><p>Ваше обращение успешно отправлено.</p></div>';
	}
	
	$arReply["text"] = $strReply;
	
	
	\ig\CUtil::showJsonReply($arReply);
}

$ElementID = $APPLICATION->IncludeComponent(
	'bitrix:news.detail',
	'',
	Array(
		'DISPLAY_DATE' => $arParams['DISPLAY_DATE'],
		'DISPLAY_NAME' => $arParams['DISPLAY_NAME'],
		'DISPLAY_PICTURE' => $arParams['DISPLAY_PICTURE'],
		'DISPLAY_PREVIEW_TEXT' => $arParams['DISPLAY_PREVIEW_TEXT'],
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'FIELD_CODE' => $arParams['DETAIL_FIELD_CODE'],
		'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
		'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['detail'],
		'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
		'META_KEYWORDS' => $arParams['META_KEYWORDS'],
		'META_DESCRIPTION' => $arParams['META_DESCRIPTION'],
		'BROWSER_TITLE' => $arParams['BROWSER_TITLE'],
		'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
		'DISPLAY_PANEL' => $arParams['DISPLAY_PANEL'],
		'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
		'SET_TITLE' => $arParams['SET_TITLE'],
		'MESSAGE_404' => $arParams['MESSAGE_404'],
		'SET_STATUS_404' => $arParams['SET_STATUS_404'],
		'SHOW_404' => $arParams['SHOW_404'],
		'FILE_404' => $arParams['FILE_404'],
		'INCLUDE_IBLOCK_INTO_CHAIN' => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
		'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
		'ACTIVE_DATE_FORMAT' => $arParams['DETAIL_ACTIVE_DATE_FORMAT'],
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		'USE_PERMISSIONS' => $arParams['USE_PERMISSIONS'],
		'GROUP_PERMISSIONS' => $arParams['GROUP_PERMISSIONS'],
		'DISPLAY_TOP_PAGER' => $arParams['DETAIL_DISPLAY_TOP_PAGER'],
		'DISPLAY_BOTTOM_PAGER' => $arParams['DETAIL_DISPLAY_BOTTOM_PAGER'],
		'PAGER_TITLE' => $arParams['DETAIL_PAGER_TITLE'],
		'PAGER_SHOW_ALWAYS' => 'N',
		'PAGER_TEMPLATE' => $arParams['DETAIL_PAGER_TEMPLATE'],
		'PAGER_SHOW_ALL' => $arParams['DETAIL_PAGER_SHOW_ALL'],
		'CHECK_DATES' => $arParams['CHECK_DATES'],
		'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
		'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
		'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
		'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
		'IBLOCK_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news'],
		'USE_SHARE' => $arParams['USE_SHARE'],
		'SHARE_HIDE' => $arParams['SHARE_HIDE'],
		'SHARE_TEMPLATE' => $arParams['SHARE_TEMPLATE'],
		'SHARE_HANDLERS' => $arParams['SHARE_HANDLERS'],
		'SHARE_SHORTEN_URL_LOGIN' => $arParams['SHARE_SHORTEN_URL_LOGIN'],
		'SHARE_SHORTEN_URL_KEY' => $arParams['SHARE_SHORTEN_URL_KEY'],
		'ADD_ELEMENT_CHAIN' => $arParams['ADD_ELEMENT_CHAIN'] ?? '',
		'STRICT_SECTION_CHECK' => $arParams['STRICT_SECTION_CHECK'] ?? '',
	),
	$component
);