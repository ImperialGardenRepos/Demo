<?
if($_REQUEST["frmPriceQuerySent"] == 'Y') {
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
			"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">Ошибка отправки обращения</h1><p>В ходе отправки возникли следующие ошибки:<br><br>'.implode("<br>", $arErrors).'</p></div>'
		);
	} else {
		$arEventFields = $arFormData;
		$arEventFields["SOURCE_PAGE"] = 'https://'.$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPageParam();
		
		if(CModule::IncludeModule("iblock")) {
			$el = new CIBlockElement;
			
			$intIblockID = \ig\CHelper::getIblockIdByCode("form_price_query");
			
			$arFields = array(
				"IBLOCK_ID" => $intIblockID,
				"NAME" => $arFormData["NAME"].' ('.$arFormData["PHONE"].', '.$arFormData["EMAIL"].')',
				"ACTIVE" => "Y",
				"PREVIEW_TEXT" => $arFormData["TEXT"],
				"PROPERTY_VALUES" => array(
					"EMAIL" => $arFormData["EMAIL"],
					"NAME" => $arFormData["NAME"],
					"PHONE" => $arFormData["PHONE"],
					"LINK" => $arEventFields["SOURCE_PAGE"]
				)
			);
			
			$intElementID = $el->Add($arFields);
			$arEventFields["IBLOCK_LINK"] = 'https://'.$_SERVER["HTTP_HOST"].'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$intIblockID.'&type=service&ID='.$intElementID.'&lang=ru&find_section_section=-1';
		}
		
		$arEventFields["SUBJECT"] = $arResult["SUBJECT"][$arFormData["SUBJECT"]]["VALUE"];
		
		\Bitrix\Main\Mail\Event::send(array(
			"EVENT_NAME" => "IG_PRICE_QUERY",
			"LID" => SITE_ID,
			"C_FIELDS" => $arEventFields
		));
	}
	
	$arReply = array(
		"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">'.$arFormData["NAME"].', спасибо! </h1><p>Ваше обращение успешно отправлено.</p></div>'
	);
	
	\ig\CUtil::showJsonReply($arReply);
} elseif($_REQUEST["frmSubscribeSent"] == 'Y') {
	// сохраняем данные формы
	$arErrors = array();
	
	$arFormData = $_REQUEST["F"];
	
	if(!check_email($arFormData["EMAIL"])) {
		$arErrors[] = '&mdash; указан некорректный почтовый адрес';
	}
	
	if(!empty($arErrors)) {
		$arReply = array(
			"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">Ошибка отправки обращения</h1><p>В ходе отправки возникли следующие ошибки:<br><br>'.implode("<br>", $arErrors).'</p></div>'
		);
	} else {
		$arEventFields = $arFormData;
		$arEventFields["SOURCE_PAGE"] = 'https://'.$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPageParam();
		
		if(CModule::IncludeModule("iblock")) {
			$el = new CIBlockElement;
			
			$intIblockID = \ig\CHelper::getIblockIdByCode("form_subscribe");
			
			$arServices = array();
			$rsEnum = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$intIblockID, "CODE"=>"SERVICES"));
			while($arEnum = $rsEnum->GetNext()) {
				if(in_array($arEnum["ID"], $arFormData["SERVICES"])) {
					$arServices[] = $arEnum["VALUE"];
				}
			}
			
			$arFields = array(
				"IBLOCK_ID" => $intIblockID,
				"NAME" => $arFormData["EMAIL"],
				"ACTIVE" => "Y",
				"PREVIEW_TEXT" => $arFormData["TEXT"],
				"PROPERTY_VALUES" => array(
					"SERVICES" => $arFormData["SERVICES"]
				)
			);
			
			$intElementID = $el->Add($arFields);
		}
		
		$arEventFields["SERVICES"] = implode(", ", $arServices);
		$arEventFields["IBLOCK_LINK"] = 'https://'.$_SERVER["HTTP_HOST"].'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$intIblockID.'&type=service&ID='.$intElementID.'&lang=ru&find_section_section=-1';
		
		\Bitrix\Main\Mail\Event::send(array(
			"EVENT_NAME" => "IG_PRICE_QUERY",
			"LID" => SITE_ID,
			"C_FIELDS" => $arEventFields
		));
		
		$arReply = array(
			"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">Спасибо! </h1><p>Ваше обращение успешно отправлено.</p></div>'
		);
	}
	
	\ig\CUtil::showJsonReply($arReply);
}

?><div class="section section--grey section--article section--fullheight text">
	<div class="container">
		
		<h1><?=$APPLICATION->GetTitle()?></h1>
		
		<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/partneram/opt/top_text.php"
				), false
			)?></p>
		
		<div class="tgbs">
			<div class="tgbs__inner">
				
				<div class="tgb tgb--1-2 tgb--h50">
					<div class="tgb__inner">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/top_block_left_text.php"
							), false
						)?>
					</div>
					<div class="tgb__content text">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/top_block_left_opts.php"
							), false
						)?>
					</div>
				</div>
				
				<div class="tgb tgb--1-2 tgb--h50">
					<div class="tgb__inner"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/top_block_right_text.php"
							), false
						)?>
					</div>
					
					<div class="tgb__content text"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/top_block_right_opts.php"
							), false
						)?>
					</div>
				</div>
			
			</div>
		</div>
		<h2><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/partneram/opt/spec_offers_title.php"
				), false
			)?></h2>
		<div class="tgbs tgbs--spec">
			<div class="tgbs__inner">
				<div class="tgb tgb--1-3 tgb--spec">
					<div class="tgb__inner">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/spec_offers_1.php"
							), false
						)?>
					</div>
				</div>
				
				<div class="tgb tgb--1-3 tgb--spec">
					<div class="tgb__inner">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/spec_offers_2.php"
							), false
						)?>
					</div>
				</div>
				
				<div class="tgb tgb--1-3 tgb--spec">
					<div class="tgb__inner">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/opt/spec_offers_3.php"
							), false
						)?>
					</div>
				</div>
			
			</div>
		</div>
		<div class="ltabs-wrapper">
			<div class="ltabs ltabs--auto js-tabs mobile-hide">
				<div class="ltabs__list">
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover active">
						<a class="ltabs__link ltabs__link--dotted" href="#price-list">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/opt/tab_title_1.php"
								), false
							)?>
						</a>
					</div>
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover">
						<a class="ltabs__link ltabs__link--dotted" href="#delivery">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/opt/tab_title_2.php"
								), false
							)?>
						</a>
					</div>
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover">
						<a class="ltabs__link ltabs__link--dotted" href="#payment">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/opt/tab_title_3.php"
								), false
							)?>
						</a>
					</div>
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover">
						<a class="ltabs__link ltabs__link--dotted" href="#subscribe">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/opt/tab_title_4.php"
								), false
							)?>
						</a>
					</div>
				</div>
			</div>
			<div class="tab-panes tab-panes--expand-it container-anti-mobile">
				<div class="tab-pane tab-pane--default tab-pane--hidden expand-it-wrapper expand-it-wrapper-collapse-siblings active" id="price-list">
					
					<a href="#" class="tab-pane__title expand-it mobile-show active">
						<div class="tab-pane__title-toggler">
							<svg class="icon">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
							</svg>
						</div>
						<span class="link link--dotted">
                                <?$APPLICATION->IncludeComponent(
	                                "bitrix:main.include",
	                                "",
	                                Array(
		                                "AREA_FILE_SHOW" => "file",
		                                "AREA_FILE_SUFFIX" => "inc",
		                                "EDIT_TEMPLATE" => "",
		                                "PATH" => "/local/inc_area/partneram/opt/tab_title_1.php"
	                                ), false
                                )?>
                            </span>
					</a>
					<div class="tab-pane-container expand-it-container active">
						<div class="tab-pane-inner expand-it-inner active">
							<form class="form form--quote form-validate js-action" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" method="post">
								<div class="form__items js-message js-message-container">
									<div class="form__item">
										<div class="cols-wrapper">
											<div class="cols cols--auto">
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
												<div class="col col--fit text-align-right vmiddle text-align-center-on-mobile mobile-hide">
													<button type="submit" class="btn btn--medium minw200 active">Запросить</button>
												</div>
											</div>
										</div>
									</div>
									<div class="form__item form__item--agree">
										<div class="checkboxes">
											<div class="checkboxes__inner">
												<div class="checkbox checkbox--noinput">
													Нажимая на кнопку “отправить”, Вы соглашаетесь
													с&nbsp;<a target="_blank" href="<?=\ig\CRegistry::get("politika-konfidentsialnosti")?>">политикой конфиденциальности</a>
												</div>
											</div>
										</div>
									</div>
									<div class="form__item text-align-center-on-mobile mobile-show">
										
										<button type="submit" class="btn btn--medium minw200 active">Запросить</button>
									</div>
								</div>
								<input type="hidden" name="frmPriceQuerySent" value="Y">
							</form>
						
						</div>
					</div>
				
				</div>
				
				<div class="tab-pane tab-pane--default tab-pane--hidden expand-it-wrapper expand-it-wrapper-collapse-siblings" id="delivery">
					
					<a href="#" class="tab-pane__title expand-it mobile-show">
						<div class="tab-pane__title-toggler">
							<svg class="icon">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
							</svg>
						</div>
						<span class="link link--dotted">
                                <?$APPLICATION->IncludeComponent(
	                                "bitrix:main.include",
	                                "",
	                                Array(
		                                "AREA_FILE_SHOW" => "file",
		                                "AREA_FILE_SUFFIX" => "inc",
		                                "EDIT_TEMPLATE" => "",
		                                "PATH" => "/local/inc_area/partneram/opt/tab_title_2.php"
	                                ), false
                                )?>
                            </span>
					</a>
					
					<div class="tab-pane-container expand-it-container">
						<div class="tab-pane-inner expand-it-inner">
							
							<div class="stgbs stgbs--vborder">
								<div class="stgbs__inner">
									
									<div class="stgb">
										<div class="stgb__inner"><?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"AREA_FILE_SUFFIX" => "inc",
													"EDIT_TEMPLATE" => "",
													"PATH" => "/local/inc_area/partneram/opt/delivery_1.php"
												), false
											)?>
										</div>
									</div>
									
									<div class="stgb">
										<div class="stgb__inner"><?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"AREA_FILE_SUFFIX" => "inc",
													"EDIT_TEMPLATE" => "",
													"PATH" => "/local/inc_area/partneram/opt/delivery_2.php"
												), false
											)?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane tab-pane--default tab-pane--hidden expand-it-wrapper expand-it-wrapper-collapse-siblings" id="payment">
					
					<a href="#" class="tab-pane__title expand-it mobile-show">
						<div class="tab-pane__title-toggler">
							<svg class="icon">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
							</svg>
						</div>
						<span class="link link--dotted">
                                <?$APPLICATION->IncludeComponent(
	                                "bitrix:main.include",
	                                "",
	                                Array(
		                                "AREA_FILE_SHOW" => "file",
		                                "AREA_FILE_SUFFIX" => "inc",
		                                "EDIT_TEMPLATE" => "",
		                                "PATH" => "/local/inc_area/partneram/opt/tab_title_3.php"
	                                ), false
                                )?>
                            </span>
					</a>
					
					<div class="tab-pane-container expand-it-container">
						<div class="tab-pane-inner expand-it-inner">
							
							<div class="stgbs">
								<div class="stgbs__inner">
									
									<div class="stgb">
										<div class="stgb__inner">
											<div class="stgb__icon color-active">
												<svg class="icon icon--cash">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cash"></use>
												</svg>
											</div>
											<div class="stgb__content">
												<?$APPLICATION->IncludeComponent(
													"bitrix:main.include",
													"",
													Array(
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "inc",
														"EDIT_TEMPLATE" => "",
														"PATH" => "/local/inc_area/partneram/opt/payment_1.php"
													), false
												)?>
											</div>
										</div>
									</div>
									
									<div class="stgb">
										<div class="stgb__inner">
											<div class="stgb__icon color-active">
												<svg class="icon">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-card-terminal"></use>
												</svg>
											</div>
											<div class="stgb__content">
												<?$APPLICATION->IncludeComponent(
													"bitrix:main.include",
													"",
													Array(
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "inc",
														"EDIT_TEMPLATE" => "",
														"PATH" => "/local/inc_area/partneram/opt/payment_2.php"
													), false
												)?>
											</div>
										</div>
									</div>
									
									<div class="stgb">
										<div class="stgb__inner">
											<div class="stgb__icon color-active">
												<svg class="icon">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-bill"></use>
												</svg>
											</div>
											<div class="stgb__content">
												<?$APPLICATION->IncludeComponent(
													"bitrix:main.include",
													"",
													Array(
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "inc",
														"EDIT_TEMPLATE" => "",
														"PATH" => "/local/inc_area/partneram/opt/payment_3.php"
													), false
												)?>
											</div>
										</div>
									</div>
								
								</div>
							</div>
						
						</div>
					</div>
				
				</div>
				
				<div class="tab-pane tab-pane--default tab-pane--hidden expand-it-wrapper expand-it-wrapper-collapse-siblings" id="subscribe">
					
					<a href="#" class="tab-pane__title expand-it mobile-show">
						<div class="tab-pane__title-toggler">
							<svg class="icon">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
							</svg>
						</div>
						<span class="link link--dotted">
                                <?$APPLICATION->IncludeComponent(
	                                "bitrix:main.include",
	                                "",
	                                Array(
		                                "AREA_FILE_SHOW" => "file",
		                                "AREA_FILE_SUFFIX" => "inc",
		                                "EDIT_TEMPLATE" => "",
		                                "PATH" => "/local/inc_area/partneram/opt/tab_title_4.php"
	                                ), false
                                )?>
                            </span>
					</a>
					<div class="tab-pane-container expand-it-container">
						<div class="tab-pane-inner expand-it-inner">
							<form class="form form--quote form-validate js-action" action="<?=$APPLICATION->GetCurPageParam()?>" method="post">
								<div class="form__items js-message js-message-container">
									<div class="form__item">
										<div class="form__item-field form__item-field--error-absolute">
											<div class="checkboxes">
												<div class="checkboxes__inner"><?
													$intIblockID = \ig\CHelper::getIblockIdByCode("form_subscribe");
													
													$rsEnum = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>\ig\CHelper::getIblockIdByCode("form_subscribe"), "CODE"=>"SERVICES"));
													while($arEnum = $rsEnum->GetNext()) { ?>
														<label class="checkbox checkbox--block-mobile checkbox-plain-js">
															<input type="checkbox" class="foo-bar-field-subscribe" name="F[SERVICES][]" value="<?=$arEnum["ID"]?>" data-rule-require_from_group='[1,".foo-bar-field-subscribe"]' data-msg-require_from_group='Пожалуйста, выберите одну из подписок.'>
															<div class="checkbox__icon"></div>
															<?=$arEnum["VALUE"]?>
														</label><?
													}?>
												</div>
											</div>
										</div>
									</div>
									<div class="form__item form__item--lm">
										<div class="cols-wrapper">
											<div class="cols cols--auto">
												<div class="col">
													<div class="form__item-field form__item-field--error-absolute">
														<div class="textfield-wrapper">
															<input type="email" class="textfield" name="F[EMAIL]" data-rule-required="true" data-rule-email="true">
															<div class="textfield-placeholder">E-mail</div>
															<div class="textfield-after color-active">*</div>
														</div>
													</div>
												</div>
												<div class="col col--fit text-align-right vmiddle text-align-center-on-mobile mobile-hide">
													<button type="submit" class="btn btn--medium minw200 active">Запросить</button>
												</div>
											</div>
										</div>
									</div>
									<div class="form__item form__item--agree">
										<div class="checkboxes">
											<div class="checkboxes__inner">
												<div class="checkbox checkbox--noinput">
													Нажимая на кнопку “отправить”, Вы соглашаетесь
													с&nbsp;<a target="_blank" href="<?=\ig\CRegistry::get("politika-konfidentsialnosti")?>">политикой конфиденциальности</a>
												</div>
											</div>
										</div>
									</div>
									<div class="form__item text-align-center-on-mobile mobile-show">
										<button type="submit" class="btn btn--medium minw200 active">Запросить</button>
									</div>
								</div>
								<input type="hidden" name="frmSubscribeSent" value="Y">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
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