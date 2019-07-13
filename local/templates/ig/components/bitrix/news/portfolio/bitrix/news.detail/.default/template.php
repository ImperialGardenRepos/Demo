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

$arDateTo = explode(".", substr($arResult["DATE_ACTIVE_TO"], 0, 10));

$arImages = array();

if($arResult["DETAIL_PICTURE"]["ID"]>0) {
	$arImages[] = $arResult["PREVIEW_PICTURE"]["ID"];
}

foreach($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $intPhotoID) {
	$arImages[] = $intPhotoID;
}

$obImageProcessor = new \ig\CImageResize;

$strImages = '';
$strStartImage = '';
foreach($arImages as $intCnt => $intImageID) {
	$arFile = CFile::GetFileArray($intImageID);
	$arFile["WATERMARK_SRC"] = $obImageProcessor->getResizedImgOrPlaceholder($intImageID, 5000);
	
	$arPreview["src"] = $obImageProcessor->getResizedImgOrPlaceholder($intImageID, 1200, 505);
	
//	$arFileTmp = CFile::ResizeImageGet(
//		$intImageID,
//		array("width" => 1200, 'height' => 505),
//		BX_RESIZE_IMAGE_EXACT,
//		false
//	);
	
	if($intCnt == 0) {
		$strStartImage = $arPreview["src"];
	}
	
	$strImages .= '<img'.($intCnt==0?' class="active"':'').' data-lazy-src="'.$arPreview["src"].'" src="'.SITE_TEMPLATE_PATH.'/img/blank.png" data-src-full="'.$arFile["WATERMARK_SRC"].'" data-ihs-content="'.$arFile["DESCRIPTION"].'" alt="">';
}

?>
<div class="section section--grey section--article text section--fullheight">
	<div class="container">
		<h1><?=$arResult["NAME"]?></h1>
		<div class="image-slider image-slider--autosize js-images-hover-slider js-images-popup-slider cursor-pointer">
			<?=$strImages?>
			<div class="image-slider__size">
				<img src="<?=$strStartImage?>" alt="">
			</div>
		</div><?
		if($arResult["PROPERTIES"]["VIDEO"]["VALUE"]) {
			if($arResult["PROPERTIES"]["VIDEO_PREVIEW"]["VALUE"]) {
				$strImage = CFile::GetPath($arResult["PROPERTIES"]["VIDEO_PREVIEW"]["VALUE"]);
			} else {
				$strImage = SITE_TEMPLATE_PATH.'/pic/video-image.jpg';
			}
			?>
		
		<a class="ivideo ivideo--right" href="<?=$arResult["PROPERTIES"]["VIDEO"]["VALUE"]?>" target="_blank" data-fancybox>
			<div class="ivideo__image">
				<img src="<?=$strImage?>" alt="">
			</div>
			<div class="ivideo__icon">
				<svg class="icon">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-play-circle"></use>
				</svg>
			</div><?
			if($arResult["PROPERTIES"]["VIDEO"]["DESCRIPTION"]) {?>
			<div class="ivideo__title"><?=$arResult["PROPERTIES"]["VIDEO"]["DESCRIPTION"]?></div><?
			}?>
		</a>
		
		<div class="pparams pparams--large"><?
			if($arDateTo[2]>0) {?>
			<div class="pparam pparam--large">
				<div class="pparam__title">Дата окончания работ</div>
				<div class="pparam__value"><?=$arDateTo[2]?> год</div>
			</div><?
			}
			
			if($arResult["PROPERTIES"]["TOTAL_AREA"]["VALUE"]) {?>
			<div class="pparam pparam--large">
				<div class="pparam__title">Общая площадь</div>
				<div class="pparam__value"><?=$arResult["PROPERTIES"]["TOTAL_AREA"]["VALUE"]?> га</div>
			</div><?
			}?>
		</div>
		
		<div class="p text">
			
			<p><?=$arResult["DETAIL_TEXT"]?></p><?
			if($arResult["PROPERTIES"]["TASKS"]["VALUE"]) {?>
			<p><strong>Решенные задачи</strong></p>
			<ul class="list-cols-3"><?
				foreach($arResult["PROPERTIES"]["TASKS"]["VALUE"] as $strTask) {?>
					<li><?=$strTask?></li><?
				}?>
			</ul><?
			}?>
		</div><?
		} else {?>
		<div class="pparams pparams--center pparams--large"><?
			if($arDateTo[2]>0) {?>
			<div class="pparam pparam--large">
				<div class="pparam__title">Дата окончания работ</div>
				<div class="pparam__value"><?=$arDateTo[2]?> год</div>
			</div><?
			}
			if($arResult["PROPERTIES"]["TOTAL_AREA"]["VALUE"]) {?>
			<div class="pparam pparam--large">
				<div class="pparam__title">Общая площадь</div>
				<div class="pparam__value"><?=$arResult["PROPERTIES"]["TOTAL_AREA"]["VALUE"]?> га</div>
			</div><?
			}?>
		</div>
		<div class="p text">
			<p><?=$arResult["DETAIL_TEXT"]?></p><?
			if($arResult["PROPERTIES"]["TASKS"]["VALUE"]) {?>
				<p><strong>Решенные задачи</strong></p>
				<ul class="list-cols-3"><?
				foreach($arResult["PROPERTIES"]["TASKS"]["VALUE"] as $strTask) {?>
					<li><?=$strTask?></li><?
				}?>
				</ul><?
			}?>
		</div><?
		}?>
		
		<h2>Подробнее о проекте</h2>
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
								<button type="submit" class="btn btn--medium minw200 active">Запросить</button>
							</div>
						</div>
					</div>
				
				</div>
			</div>
			<input type="hidden" name="F[OBJECT_ID]" value="<?=$arResult["ID"]?>">
			<input type="hidden" name="frmProjectDetailSent" value="Y">
		</form>
		<?
		if(!empty($arResult["MORE_OBJECT"])) {?>
		<h2>Ещё проекты</h2>
		<div class="tgbs tgbs--project">
			<div class="tgbs__inner"><?
				foreach($arResult["MORE_OBJECT"] as $arItem) {
					$arFileTmp = CFile::ResizeImageGet(
						$arItem["PREVIEW_PICTURE"],
						array("width" => 600, 'height' => 525),
						BX_RESIZE_IMAGE_EXACT,
						false
					);
					$arDateTo = explode(".", substr($arItem["DATE_ACTIVE_TO"], 0, 10));?>
				<div class="tgb tgb--img-hover tgb--project">
					<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						
						<div class="tgb__image img-to-bg">
							<img data-lazy-src="<?=$arFileTmp["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
						</div>
						
						<div class="tgb__overlay">
							<div class="tgb__overlay-part">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__title h2"><?=$arItem["NAME"]?></div>
									<div class="pparams"><?
										if($arDateTo[2]>0) {?>
										<div class="pparam">
											<div class="pparam__title">Дата окончания работ</div>
											<div class="pparam__value"><?=$arDateTo[2]?> год</div>
										</div><?
										}
										
										if($arItem["PROPERTIES"]["TOTAL_AREA"]["VALUE"]) {?>
											<div class="pparam">
											<div class="pparam__title">Общая площадь</div>
											<div class="pparam__value"><?=$arItem["PROPERTIES"]["TOTAL_AREA"]["VALUE"]?> га</div>
											</div><?
										}?>
									</div>
								</div>
							</div>
							<div class="tgb__overlay-part mobile-hide">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__summary text">
										<p><?=$arItem["PREVIEW_TEXT"]?></p>
									</div>
								</div>
							</div>
						</div>
					
					</a>
				</div><?
				}?>
			</div>
		</div><?
		}?>
	</div>
</div>