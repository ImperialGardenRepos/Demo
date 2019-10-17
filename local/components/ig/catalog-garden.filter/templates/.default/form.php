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
$this->setFrameMode(true);
?>

	<form class="tab-pane filter filter--goods js-filter" action="<?=$arResult["FILTER_PAGE_URL"]?>" method="get">
		<input type="hidden" name="frmCatalogFilterSent" value="Y">
		<input type="hidden" name="IS_AJAX" value="Y">
		<div class="js-filter-inner">
			<div class="filter__main">
				<div class="filter__tcheckboxes">
					<div class="tcheckboxes">
						<div class="tcheckboxes__inner js-tcheckboxes">
							<div class="tcheckboxes__scroll js-tcheckboxes-scroll">
								<div class="tcheckboxes__scroll-inner">
									
									<div class="cols-wrapper cols-wrapper--filter-sections">
										<div class="cols cols--flex"><?
											foreach($arResult["SECTIONS"] as $arSection) {?>
												<div class="col">
													<div class="tcheckbox-wrapper dropdown-selection">
														<label class="tcheckbox checkbox-plain-js js-ddbox-input">
															<input type="radio" name="F[SECTION]" value="F<?=$arSection["ID"]?>"<?=($arSection["CHECKED"]=='Y'?' checked':'')?>>
															<div class="tcheckbox__inner">
																<span class="link link--dotted"><?=$arSection["NAME"]?></span>
															</div>
														</label><?
												if(!empty($arSection["SUBSECTIONS"])) {?>
														<div class="dtcheckbox-trigger js-dtcheckbox-trigger tooltip  tooltip-popover tooltip-popover-right<?=($arSection["HAS_CHECKED_SUBSECTION"]=='Y'?' checked':'')?>" data-tooltipster='{"theme": "tooltipster-popover-dtcheckbox"}'>
															<svg class="icon">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-menu-list"></use>
															</svg>
														</div>
													<div class="dropdown-selection-content dtcheckbox-dropdown dtcheckbox-dropdown--left js-dtcheckbox-dropdown">
														<div class="dtcheckbox-dropdown__scroll"><?
															foreach($arSection["SUBSECTIONS"] as $arSubsection) {?>
																<label class="tcheckbox tcheckbox--block checkbox-plain-js js-ddbox-input">
																<input type="radio" name="F[SECTION]" value="S<?=$arSubsection["ID"]?>"<?=($arSubsection["CHECKED"]=='Y'?' checked':'')?>>
																<span class="link link--dotted"><?=$arSubsection["NAME"]?></span>
																</label><?
															}?>
														</div>
													</div>
													<?if(false) {?>
														<div class="dropdown-selection-content dtcheckbox-dropdown dtcheckbox-dropdown--left js-dtcheckbox-dropdown">
															<div class="dtcheckbox-dropdown__scroll"><?
													foreach($arSection["SUBSECTIONS"] as $arSubsection) {?>
																<label class="tcheckbox tcheckbox--block checkbox-plain-js js-ddbox-input">
																	<input type="radio" name="F[SECTION]" value="S<?=$arSubsection["ID"]?>"<?=($arSubsection["CHECKED"]=='Y'?' checked':'')?>>
																	<span class="link link--dotted"><?=$arSubsection["NAME"]?></span>
																</label><?
													}?>
															</div>
														</div><?
													}
												}?>
													</div>
												</div>
												
												
												<?
												if(false) {
												?>
											<div class="col">
												<div class="tcheckbox-wrapper dropdown-selection">
													<label class="tcheckbox checkbox-plain-js js-ddbox-input<?=$arSection["ID"]?>"<?=($arSection["CHECKED"]=='Y'?' checked':'')?>">
														<input type="radio" name="F[SECTION]" value="<?=$arSection["ID"]?>"<?=($arSection["CHECKED"]=='Y'?' checked':'')?>>
														<div class="tcheckbox__inner">
															<span class="link link--dotted"><?=$arSection["NAME"]?></span>
														</div>
													</label><?
													if(!empty($arSection["SUBSECTIONS"])) {?>
													<div class="dtcheckbox-trigger js-dtcheckbox-trigger tooltip  tooltip-popover tooltip-popover-right<?=($arSection["HAS_CHECKED_SUBSECTION"]=='Y'?' checked':'')?>" data-tooltipster='{"theme": "tooltipster-popover-dtcheckbox"}'>
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-menu-list"></use>
														</svg>
													</div>
													<div class="dropdown-selection-content dtcheckbox-dropdown dtcheckbox-dropdown--left js-dtcheckbox-dropdown">
														<div class="dtcheckbox-dropdown__scroll"><?
															foreach($arSection["SUBSECTIONS"] as $arSubsection) {?>
															<label class="tcheckbox tcheckbox--block checkbox-plain-js js-ddbox-input">
																<input type="radio" name="F[subsection]" value="<?=$arSubsection["ID"]?>"<?=($arSubsection["CHECKED"]=='Y'?' checked':'')?>>
																<span class="link link--dotted"><?=$arSubsection["NAME"]?></span>
															</label><?
															}?>
														</div>
													</div><?
													}?>
												</div>
											</div><?
												}
											}?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="filter__section filter__popup js-filter-section-wrapper expand-it-wrapper active">
					
					<div class="filter__content">
						
						<div class="cols-wrapper cols-wrapper--filter-main">
							<div class="cols"><?
									$arPropertyParams = array(
										"BLOCK_TITLE" => "Цена",
										"DISABLED" => $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["DISABLED"],
										"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-price" data-place=\'{"0": ".js-filter-ddbox-price-mobile-place", "640": ".js-filter-ddbox-price-place"}\'',
										"DEFAULT" => array(
											"NAME" => "Любая",
											"COUNT" => $arResult["COUNT_DATA"]["CATALOG_PRICE_LIST"]["TOTAL"]
										),
										"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
										"COUNT" => $arResult["COUNT_DATA"]["CATALOG_PRICE_LIST"],
										"VALUES" => $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["VALUES"]
									);
									
									$strHtml = \CatalogFilter::getPropertyHtml('radio', 'CATALOG_PRICE_LIST', $arPropertyParams);
									if($strHtml) { ?>
								<div class="col mobile-hide">
									<?=$strHtml?>
									<div class="js-filter-ddbox-price-place"></div>
								</div><?
									}
									
									// available
									$arPropertyParams = array(
										"BLOCK_TITLE" => "Наличие",
										"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-in_stock" data-place=\'{"0": ".js-filter-ddbox-in_stock-mobile-place", "640": ".js-filter-ddbox-in_stock-place"}\'',
										"DEFAULT" => array(
											"NAME" => "Любое",
											"COUNT" => $arResult["COUNT_DATA"]["AVAILABLE"]["TOTAL"]
										),
										"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
										"COUNT" => $arResult["COUNT_DATA"]["AVAILABLE"],
										"VALUES" => $arResult["OFFER_PARAMS"]["AVAILABLE"]["VALUES"]
									);
									
									$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_AVAILABLE', $arPropertyParams);
									if($strHtml) { ?>
								<div class="col mobile-hide">
									<?=$strHtml?>
									<div class="js-filter-ddbox-in_stock-place"></div>
								</div><?
									}?>
									
								
								<div class="col mobile-hide"><?
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Назначение",
												"DISABLED" => $arResult["OFFER_PARAMS"]["USAGE"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-use" data-place=\'{"0": ".js-filter-ddbox-use-mobile-place", "640": ".js-filter-ddbox-use-place"}\'',
												"DEFAULT" => array(
													"NAME" => "Любое",
													"COUNT" => $arResult["COUNT_DATA"]["USAGE"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["USAGE"],
												"VALUES" => $arResult["OFFER_PARAMS"]["USAGE"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_USAGE', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											if(false) {?>
									
									<div class="ddbox js-ddbox" id="ddbox-use" data-place='{"0": ".js-filter-ddbox-use-mobile-place", "640": ".js-filter-ddbox-use-place"}'>
										<div class="ddbox__selection">
											<div class="ddbox__label">Назначение</div>
											<div class="ddbox__reset">
												<svg class="icon icon--cross">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
												</svg>
											</div>
											<div class="ddbox__arrow">
												<svg class="icon icon--chevron-down">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
												</svg>
											</div>
											<div class="ddbox__value js-ddbox-selection" data-default-value='0'></div>
											<div class="ddbox__value js-ddbox-placeholder active">Любое</div>
										</div>
										<div class="ddbox__dropdown">
											<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
												
												<div class="checkboxes">
													<div class="checkboxes__inner">
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="0" checked data-default-checked>
															<div class="checkbox__icon"></div>
															<span class="count">+943</span>
															<span class="js-ddbox-value">Любое</span>
														</label>
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="1">
															<div class="checkbox__icon"></div>
															<span class="count">+65</span>
															<span class="js-ddbox-value">Дождеватели</span>
														</label>
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="2">
															<div class="checkbox__icon"></div>
															<span class="count">+65</span>
															<span class="js-ddbox-value">Капельный полив</span>
														</label>
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="3">
															<div class="checkbox__icon"></div>
															<span class="count">+5</span>
															<span class="js-ddbox-value">Опрыскиватели</span>
														</label>
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="4">
															<div class="checkbox__icon"></div>
															<span class="count">+95</span>
															<span class="js-ddbox-value">Пистолеты</span>
														</label>
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="5">
															<div class="checkbox__icon"></div>
															<span class="count">+61</span>
															<span class="js-ddbox-value">Фитинги и коннекторы</span>
														</label>
														
														<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
															<input type="radio" name="use" value="6">
															<div class="checkbox__icon"></div>
															<span class="count">+22</span>
															<span class="js-ddbox-value">Шланги</span>
														</label>
													
													</div>
												</div>
											
											</div>
											<div class="ddbox__dropdown-scroll-gradient"></div>
										</div>
									</div>
									<div class="js-filter-ddbox-use-place"></div><?
											}?>
								
								</div>
								
								<div class="col col--query">
									
									
									<div class="ddbox ddbox--textfield ddbox--over-filter-footer opened-blocked js-ddbox js-ddbox-query" id="ddbox-query" id="ddbox-query" data-ddbox-back-title="Вернуться в каталог">
										<div class="ddbox__selection">
											<div class="ddbox__label">Поиск по названию</div>
											<div class="ddbox__reset">
												<svg class="icon icon--cross">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
												</svg>
											</div>
											<div class="ddbox__value js-ddbox-selection js-ddbox-open-blocked"></div>
											<div class="ddbox__textfield">
												<div class="textfield-wrapper">
													<input type="text" class="textfield textfield--placeholder-top" name="searchQuery" value="" maxlength="150" placeholder="Название" autocomplete="off" data-action-url="/local/ajax/catalog-garden-filter.php?search=Y">
													<span class="textfield-reset">
                                                <svg class="icon icon--cross">
                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
                                                </svg>
                                            </span>
													<span class="textfield-after textfield-after--over-reset pointer-events-none">
                                                <svg class="icon icon--search">
                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-search"></use>
                                                </svg>
                                            </span>
												</div>
											</div>
										</div>
										<div class="ddbox__dropdown">
											<div class="ddbox__dropdown-container">
												<div class="ddbox__dropdown-inner js-ddbox-scrollbar"></div>
												<div class="ddbox__dropdown-scroll-gradient"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><?
					if(false) {?>
					<div class="filter__section-header">
						<div class="filter__section-title filter__section-title--back expand-it active" data-expand-selector="#filter-main-list">
							<svg class="icon icon--arrow-left">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
							</svg>
							<span class="filter__section-title-link link--ib link--dotted">
                            Вернуться в каталог
                        </span>
						</div>
						<div class="filter__section-title filter__section-title--open expand-it active">
							
							<svg class="icon icon--filter">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-filter"></use>
							</svg>
							<span class="filter__section-title-link link--ib link--dotted">
                            Фильтры
                        </span>
							
							<div class="filter__section-title-action no-propagation js-filter-reset">
								<span class="link--ib link--dotted">Сбросить</span>
								<svg class="icon icon--cross">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
								</svg>
							</div>
						</div>
					</div>
					
					<div class="filter__section-content expand-it-container js-filter-section overflow-visible active" id="filter-main-list">
						<div class="filter__section-content-inner expand-it-inner">
							
							<div class="js-filter-ddbox-price-mobile-place"></div>
							<div class="js-filter-ddbox-in_stock-mobile-place"></div>
							<div class="js-filter-ddbox-use-mobile-place"></div>
							
							
							<div class="cols-wrapper cols-wrapper--filter-groups js-filter-section-inputs">
								<div class="cols">
									
									<div class="col">
										
										<div class="filter__group">
											
											<div class="filter__group-content">
												
												<div class="ddbox ddbox--wide js-ddbox" id="ddbox-height">
													<div class="ddbox__selection">
														<div class="ddbox__label">Высота</div>
														<div class="ddbox__reset">
															<svg class="icon icon--cross">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
															</svg>
														</div>
														<div class="ddbox__arrow">
															<svg class="icon icon--chevron-down">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
															</svg>
														</div>
														<div class="ddbox__value js-ddbox-selection" data-default-value='0'></div>
														<div class="ddbox__value js-ddbox-placeholder active">Любая</div>
													</div>
													<div class="ddbox__dropdown">
														<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
															
															<div class="checkboxes">
																<div class="checkboxes__inner">
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height" value="0" checked data-default-checked>
																		<div class="checkbox__icon"></div>
																		<span class="count">+943</span>
																		<span class="js-ddbox-value">Любая</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height" value="1">
																		<div class="checkbox__icon"></div>
																		<span class="count">+65</span>
																		<span class="js-ddbox-value">до 1 м</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height" value="2">
																		<div class="checkbox__icon"></div>
																		<span class="count">+65</span>
																		<span class="js-ddbox-value">от 1 м до 5 м</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height" value="3">
																		<div class="checkbox__icon"></div>
																		<span class="count">+5</span>
																		<span class="js-ddbox-value">от 5 м до 10 м</span>
																	</label>
																
																</div>
															</div>
														
														</div>
														<div class="ddbox__dropdown-scroll-gradient"></div>
													</div>
												</div>
											
											</div>
										
										</div>
									
									</div>
									
									<div class="col">
										
										<div class="filter__group">
											
											<div class="filter__group-content">
												
												<div class="ddbox ddbox--wide js-ddbox" id="ddbox-height-10">
													<div class="ddbox__selection">
														<div class="ddbox__label">Высота через 10 лет</div>
														<div class="ddbox__reset">
															<svg class="icon icon--cross">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
															</svg>
														</div>
														<div class="ddbox__arrow">
															<svg class="icon icon--chevron-down">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
															</svg>
														</div>
														<div class="ddbox__value js-ddbox-selection" data-default-value='0'></div>
														<div class="ddbox__value js-ddbox-placeholder active">Любая</div>
													</div>
													<div class="ddbox__dropdown">
														<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
															
															<div class="checkboxes">
																<div class="checkboxes__inner">
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height-10" value="0" checked data-default-checked>
																		<div class="checkbox__icon"></div>
																		<span class="count">+943</span>
																		<span class="js-ddbox-value">Любая</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height-10" value="1">
																		<div class="checkbox__icon"></div>
																		<span class="count">+65</span>
																		<span class="js-ddbox-value">до 1 м</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height-10" value="2">
																		<div class="checkbox__icon"></div>
																		<span class="count">+65</span>
																		<span class="js-ddbox-value">от 1 м до 5 м</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="height-10" value="3">
																		<div class="checkbox__icon"></div>
																		<span class="count">+5</span>
																		<span class="js-ddbox-value">от 5 м до 10 м</span>
																	</label>
																
																</div>
															</div>
														
														</div>
														<div class="ddbox__dropdown-scroll-gradient"></div>
													</div>
												</div>
											
											</div>
										
										</div>
									
									
									</div>
									
									<div class="col">
										
										<div class="filter__group">
											
											<div class="filter__group-content">
												
												<div class="ddbox ddbox--wide js-ddbox" id="ddbox-krona" data-ddbox-aggregate="8" data-ddbox-value-separator="">
													<div class="ddbox__selection">
														<div class="ddbox__label">Форма кроны</div>
														<div class="ddbox__reset">
															<svg class="icon icon--cross">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
															</svg>
														</div>
														<div class="ddbox__arrow">
															<svg class="icon icon--chevron-down">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
															</svg>
														</div>
														<div class="ddbox__value js-ddbox-selection"></div>
														<div class="ddbox__value js-ddbox-placeholder active">Любая</div>
													</div>
													<div class="ddbox__dropdown">
														<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
															
															<div class="checkboxes">
																<div class="checkboxes__inner">
																	
																	<label class="checkbox checkbox--block checkbox-plain-js js-ddbox-input">
																		<input type="checkbox" name="krona[]" value="1" checked>
																		<div class="checkbox__icon">
																			<svg class="icon icon--tick">
																				<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
																			</svg>
																		</div>
																		<span class="count">+943</span>
																		<span class="js-ddbox-value">
                                                                        <svg class="icon icon--ddbox">
                                                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#filters_forma-krony_crone-form.18"></use>
                                                                        </svg>
                                                                        <span class="ddbox-icon-title">Название кроны</span>
                                                                    </span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox-plain-js js-ddbox-input">
																		<input type="checkbox" name="krona[]" value="2">
																		<div class="checkbox__icon">
																			<svg class="icon icon--tick">
																				<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
																			</svg>
																		</div>
																		<span class="count">+943</span>
																		<span class="js-ddbox-value">
                                                                        <svg class="icon icon--ddbox">
                                                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#filters_forma-krony_stelyash.22"></use>
                                                                        </svg>
                                                                        <span class="ddbox-icon-title">Название кроны 2</span>
                                                                    </span>
																	</label>
																
																</div>
															</div>
														
														</div>
														<div class="ddbox__dropdown-scroll-gradient"></div>
													</div>
												</div>
											
											</div>
										
										</div>
									
									</div>
									
									<div class="col">
										
										<div class="filter__group">
											
											<div class="filter__group-content">
												
												<div class="ddbox ddbox--wide ddbox--expanded-left js-ddbox" id="ddbox-field2">
													<div class="ddbox__selection">
														<div class="ddbox__label">Почва</div>
														<div class="ddbox__reset">
															<svg class="icon icon--cross">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
															</svg>
														</div>
														<div class="ddbox__arrow">
															<svg class="icon icon--chevron-down">
																<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
															</svg>
														</div>
														<div class="ddbox__value js-ddbox-selection" data-default-value='0'></div>
														<div class="ddbox__value js-ddbox-placeholder active">Любая</div>
													</div>
													<div class="ddbox__dropdown">
														<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
															
															<div class="checkboxes">
																<div class="checkboxes__inner">
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="ddbox-field2" value="0" checked data-default-checked>
																		<div class="checkbox__icon"></div>
																		<span class="count">+943</span>
																		<span class="js-ddbox-value">Любая</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="ddbox-field2" value="1">
																		<div class="checkbox__icon"></div>
																		<span class="count">+65</span>
																		<span class="js-ddbox-value">до 1 м</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="ddbox-field2" value="2">
																		<div class="checkbox__icon"></div>
																		<span class="count">+65</span>
																		<span class="js-ddbox-value">от 1 м до 5 м</span>
																	</label>
																	
																	<label class="checkbox checkbox--block checkbox--radio checkbox-plain-js js-ddbox-input">
																		<input type="radio" name="ddbox-field2" value="3">
																		<div class="checkbox__icon"></div>
																		<span class="count">+5</span>
																		<span class="js-ddbox-value">от 5 м до 10 м</span>
																	</label>
																
																</div>
															</div>
														
														</div>
														<div class="ddbox__dropdown-scroll-gradient"></div>
													</div>
												</div>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
						
						
						</div>
						
						<div class="filter__section-content-inner mobile-show">
							<div class="js-filter-recommend-mobile-place"></div>
						</div>
						
						<div class="filter__section-content-inner mobile-show">
							<div class="js-filter-link-mobile-place"></div>
						</div>
					</div><?
					}?>
					
					<div class="filter__footer">
						
						<div class="cols-wrapper cols-wrapper--filter-footer">
							<div class="cols"><?
								$bChecked = \CatalogFilter::$arRequestFilter["PROPERTY_RECOMMENDED"]==91;
								$bDisabled = $arResult["COUNT_DATA"]["RECOMMENDED"][91]<=0;
								
								if($arResult["COUNT_DATA"]["RECOMMENDED"][91] > 0) {
									if($bChecked) {
										$intOffersDelta = $arResult["COUNT_DATA"]["RECOMMENDED"]["TOTAL"] - $arResult["COUNT_DATA"]["RECOMMENDED"][91];
									} else {
										$intOffersDelta = $arResult["COUNT_DATA"]["RECOMMENDED"][91] - $arResult["COUNT_DATA"]["RECOMMENDED"]["TOTAL"];
									}
								} else {
									$intOffersDelta = 0;
								}?>
								<div class="col col--filter-recommend mobile-hide"><?
if(!$arResult["IS_NEW"] && !$arResult["IS_ACTION"]) {?>
									<div class="maxw-like-filter-col">
										<div class="checkboxes js-filter-not-ddbox" data-place='{"0": ".js-filter-recommend-mobile-place", "640": ".js-filter-recommend-place"}'>
											<div class="checkboxes__inner">
												<label class="checkbox checkbox--block checkbox-plain-js<?=($bDisabled?' disabled':'')?>">
													<div class="checkbox__icon">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
														</svg>
													</div>
													<input type="checkbox" name="F[PROPERTY_RECOMMENDED]" <?=($bChecked?' checked':'').($bDisabled?' disabled':'')?> value="91">
													Хиты сезона <span class="count"><?=($intOffersDelta>0?'+'.$intOffersDelta:$intOffersDelta)?></span>
												</label>
											</div>
										</div>
										<div class="js-filter-recommend-place"></div>
									</div><?
								}?>
								</div>
								
								<div class="col col--filter-total">
									
									<div class="ftotals">
										<div class="ftotal">
											Товаров: <span class="ftotal__value js-filter-total-value"><?=\ig\CHelper::formatNumber($arResult["COUNT_PRODUCTS"])?></span>
										</div>
										<div class="ftotal">
											Предложений: <span class="ftotal__value js-filter-total-value"><?=\ig\CHelper::formatNumber($arResult["COUNT_OFFERS"])?></span>
										</div>
									</div>
								
								</div>
								
								<div class="col col--filter-result-btn">
									
									<a href="#" class="btn js-filter-show-results js-ddbox-close-all" data-expand-selector="#filter-main-list">Показать</a>
								
								</div>
								
								<div class="col col--filter-link mobile-hide">
									
									<div class="filter-link" data-place='{"0": ".js-filter-link-mobile-place", "640": ".js-filter-link-place"}'>
										
										<a href="<?=$arResult["RESULT_LINK"]?>" class="link--dotted js-copy js-filter-link" data-clipboard-text="<?=$arResult["RESULT_LINK"]?>" data-title="Ссылка скопирована">Ссылка на результат поиска</a>
									</div>
									<div class="js-filter-link-place"></div>
								
								</div>
							
							</div>
						</div>
					
					</div>
				
				</div>
			</div>
		
		</div>
	</form>