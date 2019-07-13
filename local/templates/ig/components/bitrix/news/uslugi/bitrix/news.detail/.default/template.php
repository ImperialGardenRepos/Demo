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

$strImageAlt = $arResult["NAME"];

ob_start();
?>
<div class="section section--grey section--article section--fullheight text">
	<div class="container">
		<h1><?=$arResult["NAME"]?></h1>
		<?
		if(!empty($arResult["DETAIL_PICTURE"]["ID"]) || !empty($arResult["DETAIL_TEXT"])) {?>
		<div class="fcard fcard--project">
			<div class="fcard__grid"><?
				if($arResult["DETAIL_PICTURE"]["ID"]>0) {?>
				<div class="fcard__image">
					<div class="image-slider image-slider--autosize image-slider--autosize-h js-images-hover-slider js-images-popup-slider cursor-pointer">
						<img class="active" data-lazy-src="<?=$arResult["TILE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" data-src-full="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$strImageAlt?>" data-ihs-content="<?=$arResult["DETAIL_PICTURE"]["DESCRIPTION"]?>">
						<div class="image-slider__size">
							<img src="<?=$arResult["TILE"]["src"]?>" alt="<?=$strImageAlt?>">
						</div>
					</div>
				</div><?
				}?>
				
				<div class="fcard__main">
					<div class="p text">
						<?=$arResult["DETAIL_TEXT"]?>
					</div>
				</div>
			</div>
		</div><?
		}
		
		if(!empty($arResult["DISPLAY_PROPERTIES"]["FREE_TEXT"]["DISPLAY_VALUE"])) {
			echo $arResult["DISPLAY_PROPERTIES"]["FREE_TEXT"]["DISPLAY_VALUE"];
		}
		
		if(!empty($arResult["DISPLAY_PROPERTIES"]["PRICE_TEXT"]["DISPLAY_VALUE"])) { ?>
			<h4>Прайс-лист</h4><?
			echo $arResult["DISPLAY_PROPERTIES"]["PRICE_TEXT"]["DISPLAY_VALUE"];
		}
		
		if($arResult["PROPERTIES"]["PRICE_FILE"]["VALUE"]>0) {
			$arFile = CFile::GetFileArray($arResult["PROPERTIES"]["PRICE_FILE"]["VALUE"]); ?>
			<div class="action-bar h3 text-align-center">
				<a target="_blank" href="<?=$arFile["SRC"]?>" class="nounderline-important">
					<span class="link link--bordered-pseudo"><?=(empty($arFile["DESCRIPTION"])?'Скачать прайс-лист':$arFile["DESCRIPTION"])?></span>
					<svg class="icon icon--file-large vmiddle">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-xls"></use>
					</svg>
				</a>
			</div><?
		}
		
		if(!empty($arResult["DISPLAY_PROPERTIES"]["NOTES"]["DISPLAY_VALUE"])) { ?>
			<h4>Примечание</h4><?
			echo $arResult["DISPLAY_PROPERTIES"]["NOTES"]["DISPLAY_VALUE"];
		}
		?>
		
		<h2>Заказать</h2>
		<form class="form form--quote form-validate js-action" action="<?=$APPLICATION->GetCurPage()?>" method="post">
			<div class="form__items js-message js-message-container">
				<div class="form__item">
					<div class="cols-wrapper">
						<div class="cols">
							<div class="col">
								<div class="form__item-field form__item-field--error-absolute">
									<div class="textfield-wrapper">
										<input type="text" class="textfield" name="F[NAME]" data-rule-required="true">
										<div class="textfield-placeholder">Ваше имя</div>
										<div class="textfield-after color-active">*</div>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="form__item-field form__item-field--error-absolute">
									<div class="textfield-wrapper">
										<input type="email" class="textfield" name="F[EMAIL]" data-rule-required="true" data-rule-email="true">
										<div class="textfield-placeholder">E-mail</div>
										<div class="textfield-after color-active">*</div>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="form__item-field form__item-field--error-absolute">
									<div class="textfield-wrapper">
										<input type="tel" class="textfield mask-phone-ru" name="F[PHONE]" data-rule-required="true" data-rule-phonecomplete="true">
										<div class="textfield-placeholder">Телефон</div>
										<div class="textfield-after color-active">*</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form__item form__item--lm">
					<div class="cols-wrapper">
						<div class="cols">
							<div class="col vmiddle">
								<div class="checkboxes">
									<div class="checkboxes__inner">
										<div class="checkbox checkbox--noinput">
											Нажимая на кнопку “отправить”, Вы соглашаетесь
											с&nbsp;<a target="_blank" href="<?=\ig\CRegistry::get("politika-konfidentsialnosti")?>">политикой конфиденциальности</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col tablet-hide"></div>
							<div class="col vmiddle text-align-right text-align-center-on-mobile">
								<button type="submit" class="btn btn--medium minw200 active">Заказать услугу</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="F[SERVICE_ID]" value="<?=$arResult["ID"]?>">
			<input type="hidden" name="frmOrderServiceSent" value="Y">
		</form>
	</div>
</div><?
$strContents = ob_get_contents();
ob_end_clean();

$strSliderHtml = '';
if(!empty($arResult["SLIDER_IMAGES"])) {
	$strSliderHtml = \ig\CHelper::getSlider("#SLIDER#", array("IMAGES" => $arResult["SLIDER_IMAGES"]));
}
$strContents = str_replace("#SLIDER#", $strSliderHtml, $strContents);
echo $strContents;
?>