<?
if($_REQUEST["frmRentQuerySent"] == 'Y') {
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
			
			$intIblockID = \ig\CHelper::getIblockIdByCode("form_rent_query");
			
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
			"EVENT_NAME" => "IG_RENT_QUERY",
			"LID" => SITE_ID,
			"C_FIELDS" => $arEventFields
		));
	}
	
	$arReply = array(
		"text" => '<div class="form__message text-align-center"><div class="heading-icon color-active"><svg class="icon"><use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg></div><h1 class="h2">'.$arFormData["NAME"].', спасибо! </h1><p>Ваше обращение успешно отправлено.</p></div>'
	);
	
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
					"PATH" => "/local/inc_area/partneram/arenda/top_text.php"
				), false
			)?></p>
		
		<div class="fcard fcard--project fcard--image-text">
			<div class="fcard__grid">
				<div class="fcard__image">
					<div class="image-slider image-slider--autosize image-slider--autosize-h js-images-hover-slider js-images-popup-slider cursor-pointer"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/arenda/image.php"
							), false
						)?>
					</div>
				</div>
				<div class="fcard__main">
					<div class="p text"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/partneram/arenda/right_text.php"
							), false
						)?>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="ltabs-wrapper">
			
			<div class="ltabs ltabs--auto js-tabs mobile-hide">
				<div class="ltabs__list">
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover text-align-center active">
						<a class="ltabs__link ltabs__link--dotted" href="#price-list"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/arenda/tab_title_1.php"
								), false
							)?></a>
					</div>
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover text-align-center">
						<a class="ltabs__link ltabs__link--dotted" href="#quote"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/arenda/tab_title_2.php"
								), false
							)?></a>
					</div>
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover text-align-center">
						<a class="ltabs__link ltabs__link--dotted" href="#rent-conditions"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/arenda/tab_title_3.php"
								), false
							)?></a>
					</div>
					<div class="ltabs__item ltabs__item--medium ltabs__item--noborder ltabs__item--nohover text-align-center">
						<a class="ltabs__link ltabs__link--dotted" href="#payment"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/arenda/tab_title_4.php"
								), false
							)?></a>
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
		                                "PATH" => "/local/inc_area/partneram/arenda/tab_title_1.php"
	                                ), false
                                )?>
                            </span> </a>
					
					<div class="tab-pane-container expand-it-container active">
						<div class="tab-pane-inner expand-it-inner active">
							<div class="cols-wrapper cols-wrapper--mobile-plain">
								<div class="cols cols--auto">
									<div class="col">
										<p><?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"AREA_FILE_SUFFIX" => "inc",
													"EDIT_TEMPLATE" => "",
													"PATH" => "/local/inc_area/partneram/arenda/tab_text_1.php"
												), false
											)?></p>
									</div>
									<div class="col col--fit text-align-center"><?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "inc",
												"EDIT_TEMPLATE" => "",
												"PATH" => "/local/inc_area/partneram/arenda/tab_text_1_price.php"
											), false
										)?>
									</div>
								</div>
							</div>
						</div>
					</div>
				
				</div>
				
				<div class="tab-pane tab-pane--default tab-pane--hidden expand-it-wrapper expand-it-wrapper-collapse-siblings" id="quote">
					
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
		                                "PATH" => "/local/inc_area/partneram/arenda/tab_title_2.php"
	                                ), false
                                )?>
                            </span> </a>
					
					<div class="tab-pane-container expand-it-container">
						<div class="tab-pane-inner expand-it-inner">
							
							<form class="form form--quote form-validate js-action" action="<?=$APPLICATION->GetCurPageParam()?>" method="post">
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
													<button type="submit" class="btn btn--medium minw200 active">
														Запросить
													</button>
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
								<input type="hidden" name="frmRentQuerySent" value="Y">
							</form>
						
						</div>
					</div>
				
				</div>
				
				<div class="tab-pane tab-pane--default tab-pane--hidden expand-it-wrapper expand-it-wrapper-collapse-siblings" id="rent-conditions">
					
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
		                                "PATH" => "/local/inc_area/partneram/arenda/tab_title_3.php"
	                                ), false
                                )?>
                            </span> </a>
					
					<div class="tab-pane-container expand-it-container">
						<div class="tab-pane-inner expand-it-inner">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/arenda/tab_text_3.php"
								), false
							)?>
						
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
		                                "PATH" => "/local/inc_area/partneram/arenda/tab_title_4.php"
	                                ), false
                                )?>
                            </span> </a>
					
					<div class="tab-pane-container expand-it-container">
						<div class="tab-pane-inner expand-it-inner">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/partneram/arenda/tab_text_4.php"
								), false
							)?>
						</div>
					</div>
				
				</div>
			
			</div>
		
		</div>
		<?
		if($_REQUEST["container-id"]) {
			$strContainerID = $_REQUEST["container-id"];
		} else {
			$strContainerID = 'partneram-avtopark';
		}
		?><?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"arenda",
			Array(
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"ADD_SECTIONS_CHAIN" => "N",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CACHE_TIME" => "36000000",
				"CACHE_TYPE" => "A",
				"CHECK_DATES" => "N",
				"DETAIL_URL" => "",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"DISPLAY_DATE" => "N",
				"DISPLAY_NAME" => "N",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"DISPLAY_TOP_PAGER" => "N",
				"FIELD_CODE" => array("DETAIL_PICTURE", ""),
				"FILTER_NAME" => "",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"IBLOCK_ID" => "33",
				"IBLOCK_TYPE" => "services",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "N",
				"MESSAGE_404" => "",
				"NEWS_COUNT" => "10",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"PAGER_SHOW_ALWAYS" => "Y",
				"PAGER_TEMPLATE" => "ajax",
				"PAGER_TITLE" => $strContainerID,
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => $_REQUEST["sectionCode"],
				"PREVIEW_TRUNCATE_LEN" => "",
				"PROPERTY_CODE" => array("PRICE", "WEIGHT"),
				"SET_BROWSER_TITLE" => "N",
				"SET_LAST_MODIFIED" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_STATUS_404" => "N",
				"SET_TITLE" => "N",
				"SHOW_404" => "N",
				"SORT_BY1" => "SORT",
				"SORT_BY2" => "ID",
				"SORT_ORDER1" => "ASC",
				"SORT_ORDER2" => "DESC",
				"STRICT_SECTION_CHECK" => "N"
			)
		);?>
		
		
		
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