<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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
$this->setFrameMode(true);?>
<div class="section section--grey section--article section--fullheight text">
	<div class="container">
		<h1><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/partneram/add_partner_h1.php"
				), false
			)?></h1>
		<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/partneram/add_partner_text.php"
				), false
			)?></p>
		<form class="form form--quote form-validate js-action" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" method="post">
			<div class="form__items js-message js-message-container">
				<div class="form__item">
					<div class="cols-wrapper cols-wrapper--mobile-plain">
						<div class="cols">
							<div class="col">
								<div class="form__item-field form__item-field--error-absolute">
									<div class="textfield-wrapper">
										<input type="text" class="textfield" name="F[COMPANY]">
										<div class="textfield-placeholder">Название компании</div>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="form__item-field form__item-field--error-absolute">
									<div class="ddbox js-ddbox js-form-disable-on-submit" data-ddbox-close-on-select="true">
										<div class="ddbox__selection">
											<div class="ddbox__label">Тема обращения</div>
											<div class="ddbox__arrow">
												<svg class="icon icon--chevron-down">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
												</svg>
											</div>
											<div class="ddbox__value js-ddbox-selection" data-default-value='1'></div>
											<div class="ddbox__value js-ddbox-placeholder active">Произвольная</div>
										</div>
										<div class="ddbox__dropdown">
											<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
												<div class="checkboxes">
													<div class="checkboxes__inner">
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="F[SUBJECT]" value="" checked>
															<div class="checkbox__icon"></div>
															<span class="js-ddbox-value">Произвольная</span>
														</label><?
														foreach($arResult["SUBJECT"] as $arEnum) {?>
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="F[SUBJECT]" value="<?=$arEnum["ID"]?>">
															<div class="checkbox__icon"></div>
															<span class="js-ddbox-value"><?=$arEnum["VALUE"]?></span>
														</label><?
														}?>
													</div>
												</div>
											
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form__item form__item--lm">
					<div class="cols-wrapper cols-wrapper--mobile-plain">
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
					<div class="form__item-field">
						<div class="fileupload-widget js-form-disable-on-submit">
							<div class="fileupload-widget-inner">
								<div class="fileupload-btn">
									<span class="btn js-form-disable-on-submit overflow-hidden"><input type="file" name="files[]" class="js-fileupload" data-fileupload-list="#fileupload-files" data-fileupload-label="Вложения" multiple>Приложить файл</span>
									<input class="js-file-upload-hash" type="hidden" name="hash">
								</div>
								<div class="fileupload-files-wrapper">
									<div class="fileupload-files-container">
										<div class="fileupload-files" id="fileupload-files"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form__item form__item--lm">
					<div class="form__item-field form__item-field--error-absolute">
						<div class="textfield-wrapper">
							<textarea class="textfield js-autosize" name="F[TEXT]" cols="40" rows="1"></textarea>
							<div class="textfield-placeholder">Сообщение</div>
						</div>
					</div>
				</div>
				<div class="form__item form__item--lm">
					<div class="cols-wrapper cols-wrapper--mobile-plain">
						<div class="cols cols--auto">
							<div class="col">
								<div class="checkboxes">
									<div class="checkboxes__inner">
										<div class="checkbox checkbox--noinput">
											Нажимая на кнопку “отправить”, Вы соглашаетесь
											с&nbsp;<a target="_blank" href="<?=\ig\CRegistry::get("politika-konfidentsialnosti")?>">политикой конфиденциальности</a>
										</div>
									</div>
								</div>
							</div><?
							if(false) {?>
							<div class="col col--fit">
								<div class="form__item-field form__item-field--error-absolute google-recaptcha">
									<div class="g-recaptcha" data-sitekey="6LfHihQTAAAAAKE8ezCGalHe57-iZnuAYBRbSy1E"></div>
									<input type="text" readonly class="google-recaptcha-validator" name="recaptcha_validator" data-rule-recaptcha="true" data-msg-recaptcha="Пройдите тест на робота">
								</div>
							</div><?
							}?>
							<div class="col text-align-right text-align-center-on-mobile">
								<button type="submit" class="btn btn--medium minw200 active">Отправить сообщение</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="frmBecomePartnerSent" value="Y">
		</form>
		<?$APPLICATION->IncludeComponent(
			"bitrix:advertising.banner",
			"line-3",
			Array(
				"CACHE_TIME" => "0",
				"CACHE_TYPE" => "N",
				"NOINDEX" => "N",
				"QUANTITY" => "3",
				"TYPE" => "SECTION_LINK_3",
				"TITLE" => "Мы предлагаем"
			)
		);?>
	</div>
</div>