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
<form class="tab-pane filter js-filter" action="<?=$arResult["FILTER_PAGE_URL"]?>" method="get">
	<input type="hidden" name="frmCatalogFilterSent" value="Y">
	<input type="hidden" name="IS_AJAX" value="Y">
	<div class="js-filter-inner">
		<div class="filter__main">
			<div class="filter__section filter__popup js-filter-section-wrapper expand-it-wrapper">
				<div class="filter__content">
					<div class="cols-wrapper cols-wrapper--filter-main">
						<div class="cols"><?
							// groups
							$arPropertyParams = array(
								"BLOCK_TITLE" => "Группа растений",
								"PRODUCTS_COUNT" => $arResult["COUNT_GROUP_PRODUCTS"],
								"BLOCK_PARAMS" => ' class="ddbox js-ddbox" data-ddbox-reset-inputs="#ddbox-usage, #ddbox-query, .js-filter-section-inputs" id="ddbox-group" data-place=\'{"0": ".js-filter-ddbox-group-mobile-place", "640": ".js-filter-ddbox-group-place"}\'',
								"DEFAULT" => array(
									"NAME" => "Любая",
									"COUNT" => $arResult["COUNT_DATA"]["GROUP"]["TOTAL"]
								),
								"CURRENT_COUNT" => $arResult["COUNT_GROUP_PRODUCTS"],
								"COUNT" => $arResult["COUNT_DATA"]["GROUP"],
								"VALUES" => $arResult["OFFER_PARAMS"]["GROUP"]["VALUES"]
							);
							$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_GROUP', $arPropertyParams);
							if($strHtml) { ?>
								<div class="col mobile-hide">
									<?=$strHtml?>
									<div class="js-filter-ddbox-group-place"></div>
								</div><?
							}
							
							// usage
							$arPropertyParams = array(
								"BLOCK_TITLE" => $arResult["PROPERTY_TREE"]["USAGE"]["NAME"],
								"DISABLED" => $arResult["OFFER_PARAMS"]["USAGE"]["DISABLED"],
								"PRODUCTS_COUNT" => $arResult["COUNT_USAGE_PRODUCTS"],
								"BLOCK_PARAMS" => '  class="ddbox js-ddbox js-ddbox-usage" data-ddbox-reset-inputs=".js-filter-section-inputs, #ddbox-query" id="ddbox-usage" data-place=\'{"0": ".js-filter-ddbox-usage-mobile-place", "640": ".js-filter-ddbox-usage-place"}\'',
								"DEFAULT" => array(
									"NAME" => "Любое",
									"COUNT" => $arResult["COUNT_DATA"]["USAGE"]["TOTAL"]
								),
								"CURRENT_COUNT" => $arResult["COUNT_USAGE_PRODUCTS"],
								"COUNT" => $arResult["COUNT_DATA"]["USAGE"],
								"VALUES" => $arResult["OFFER_PARAMS"]["USAGE"]["VALUES"]
							);

							$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_USAGE', $arPropertyParams);
							if($strHtml) { ?>
								<div class="col mobile-hide">
									<?=$strHtml?>
									<div class="js-filter-ddbox-usage-place"></div>
								</div><?
							} ?>
								<div class="col col--query"><?
									if(!$arResult["IS_NEW"] && !$arResult["IS_ACTION"]) {?>
								<div class="ddbox ddbox--textfield opened-blocked js-ddbox js-ddbox-query" data-ddbox-reset-inputs="#ddbox-group, #ddbox-usage, .js-filter-section-inputs" id="ddbox-query" data-ddbox-aggregate="2">
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
												<input type="text" class="textfield textfield--placeholder-top" name="searchQuery" value="" maxlength="150" placeholder="Название" autocomplete="off" data-action-url="/local/ajax/catalog-filter.php?search=Y">
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
											<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
												<p>Введите поисковый запрос.</p>
											</div>
											<div class="ddbox__dropdown-scroll-gradient"></div>
										</div>
									
									</div>
								</div><?
									} ?>
								</div><?
							?>
						</div>
					</div>
				</div>
				<?
				if(!$arResult["IS_NEW"] && !$arResult["IS_ACTION"]) { ?>
				<div class="filter__section-header">
					<div class="filter__section-title filter__section-title--back expand-it<?=($arParams["EXPAND_FILTER"]=="Y" ?' active':'')?>" data-expand-selector="#filter-main-list">
						<svg class="icon icon--arrow-left">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
						</svg>
						<span class="filter__section-title-link link--ib link--dotted">
                            Вернуться в каталог
                        </span>
					</div>
					<div class="filter__section-title filter__section-title--open expand-it<?=($arParams["EXPAND_FILTER"]=="Y" ?' active':'')?>">
						
						<svg class="icon icon--filter">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-filter"></use>
						</svg>
						<span class="filter__section-title-link link--ib link--dotted">
                            Фильтры
                        </span>
						
						<div class="filter__section-title-action no-propagation js-filter-reset mobile-hide">
							<span class="link--ib link--dotted">Сбросить</span>
							<svg class="icon icon--cross">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
							</svg>
						</div>
						
						<div class="filter__section-title-action no-propagation js-filter-reset mobile-show" data-ddbox-reset-inputs="#ddbox-group, #ddbox-usage, #ddbox-query, .js-filter-section-inputs">
							<span class="link--ib link--dotted">Сбросить</span>
							<svg class="icon icon--cross">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
							</svg>
						</div>
					</div>
				</div>
				
				<div class="filter__section-content expand-it-container js-filter-section<?=($arParams["EXPAND_FILTER"]=="Y"?' overflow-visible active':'')?>" id="filter-main-list">
					<div class="filter__section-content-inner expand-it-inner">
						
						<div class="js-filter-ddbox-group-mobile-place"></div>
						<div class="js-filter-ddbox-usage-mobile-place"></div>
						
						
						<div class="cols-wrapper cols-wrapper--filter-groups js-filter-section-inputs">
							<div class="cols">
								
								<div class="col">
									
									<div class="filter__group js-filter-group">
										
										<div class="filter__group-title">
											Общие
											<div class="filter__group-title-actions">
												<div class="filter__group-title-action js-filter-group-reset tooltip" data-title="Сбросить все фильтры группы">
													<svg class="icon icon--cross">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</div>
											</div>
										</div>
										
										<div class="filter__group-content"><?
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Цена",
												"DISABLED" => $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-price"',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["CATALOG_PRICE_LIST"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["CATALOG_PRICE_LIST"],
												"VALUES" => $arResult["OFFER_PARAMS"]["CATALOG_PRICE_LIST"]["VALUES"]
											);

											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'CATALOG_PRICE_LIST', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// available
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Наличие",
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-in_stock"',
												"DEFAULT" => array(
													"NAME" => "Любое",
													"COUNT" => $arResult["COUNT_DATA"]["AVAILABLE"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["AVAILABLE"],
												"VALUES" => $arResult["OFFER_PARAMS"]["AVAILABLE"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_AVAILABLE', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// additional
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Дополнительно",
												"DISABLED" => $arResult["OFFER_PARAMS"]["ADDITIONAL"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-dop" data-ddbox-aggregate="2"',
												"DEFAULT" => array(
													"NAME" => "Выберите значение",
													"COUNT" => $arResult["COUNT_DATA"]["ADDITIONAL"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["ADDITIONAL"],
												"VALUES" => $arResult["OFFER_PARAMS"]["ADDITIONAL"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_ADDITIONAL', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// многоствольные
											$bChecked = \CatalogFilter::$arRequestFilter["PROPERTY_MULTISTEMMED"]==63;
											$bDisabled = $arResult["COUNT_DATA"]["MULTISTEMMED"][63]<=0;
											if($arResult["COUNT_DATA"]["MULTISTEMMED"][63] > 0) {
												if($bChecked) {
													$intOffersDelta = $arResult["COUNT_DATA"]["MULTISTEMMED"]["TOTAL"] - $arResult["COUNT_DATA"]["MULTISTEMMED"][63];
												} else {
													$intOffersDelta = $arResult["COUNT_DATA"]["MULTISTEMMED"][63] - $arResult["COUNT_DATA"]["MULTISTEMMED"]["TOTAL"];
												}
											} else {
												$intOffersDelta = 0;
											}
											?>
											
											<div class="checkboxes checkboxes--multistem">
												<div class="checkboxes__inner">
													<label class="checkbox checkbox--block checkbox-plain-js js-ddbox-input<?=($bDisabled?' disabled':'')?>">
														<input type="checkbox" name="F[PROPERTY_MULTISTEMMED]" value="63"<?=($bChecked?' checked':'').($bDisabled?' disabled':'')?>>
														<div class="checkbox__icon"></div>
														Многоствольное <span class="count"><?=($intOffersDelta>0?'+'.$intOffersDelta:$intOffersDelta)?></span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col">
									
									<div class="filter__group js-filter-group">
										
										<div class="filter__group-title">
											Внешний вид
											<div class="filter__group-title-actions">
												<div class="filter__group-title-action js-filter-group-reset tooltip" data-title="Сбросить все фильтры группы">
													<svg class="icon icon--cross">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</div>
											</div>
										</div>
										
										<div class="filter__group-content"><?
											// height now
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Высота сейчас (м)",
												"DISABLED" => $arResult["OFFER_PARAMS"]["HEIGHT_NOW"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-height"',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["HEIGHT_NOW"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["HEIGHT_NOW"],
												"VALUES" => $arResult["OFFER_PARAMS"]["HEIGHT_NOW"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_HEIGHT_NOW', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// height 10
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Высота взрослого растения (м)",
												"DISABLED" => $arResult["OFFER_PARAMS"]["HEIGHT_10"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox"',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["HEIGHT_10"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["HEIGHT_10"],
												"VALUES" => $arResult["OFFER_PARAMS"]["HEIGHT_10"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_HEIGHT_10', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// crown
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Форма кроны",
												"DISABLED" => $arResult["OFFER_PARAMS"]["CROWN"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-krona" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["CROWN"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["CROWN"],
												"VALUES" => $arResult["OFFER_PARAMS"]["CROWN"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_CROWN', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// HAIRCUT_SHAPE
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Форма стрижки",
												"DISABLED" => $arResult["OFFER_PARAMS"]["HAIRCUT_SHAPE"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-haircut-shape" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["HAIRCUT_SHAPE"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["HAIRCUT_SHAPE"],
												"VALUES" => $arResult["OFFER_PARAMS"]["HAIRCUT_SHAPE"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_HAIRCUT_SHAPE', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											?>
										
										</div>
									</div>
								</div>
								
								<div class="col">
									
									<div class="filter__group js-filter-group">
										
										<div class="filter__group-title">
											Экология
											<div class="filter__group-title-actions">
												<div class="filter__group-title-action js-filter-group-reset tooltip" data-title="Сбросить все фильтры группы">
													<svg class="icon icon--cross">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</div>
											</div>
										</div>
										
										<div class="filter__group-content"><?
											// LIGHT
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Свет",
												"DISABLED" => $arResult["OFFER_PARAMS"]["LIGHT"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox"',
												"DEFAULT" => array(
													"NAME" => "Любой",
													"COUNT" => $arResult["COUNT_DATA"]["LIGHT"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["LIGHT"],
												"VALUES" => $arResult["OFFER_PARAMS"]["LIGHT"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_LIGHT', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// WATER
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Влага",
												"DISABLED" => $arResult["OFFER_PARAMS"]["WATER"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox"',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["LIGHT"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["WATER"],
												"VALUES" => $arResult["OFFER_PARAMS"]["WATER"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_WATER', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// GROUND
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Почва",
												"DISABLED" => $arResult["OFFER_PARAMS"]["GROUND"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox"',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["GROUND"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["GROUND"],
												"VALUES" => $arResult["OFFER_PARAMS"]["GROUND"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_GROUND', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											
											// ZONA_POSADKI
											$arPropertyParams = array(
												"BLOCK_TITLE" => "Зона посадки водных",
												"DISABLED" => $arResult["OFFER_PARAMS"]["ZONA_POSADKI"]["DISABLED"],
												"BLOCK_PARAMS" => ' class="ddbox js-ddbox"',
												"DEFAULT" => array(
													"NAME" => "Любая",
													"COUNT" => $arResult["COUNT_DATA"]["ZONA_POSADKI"]["TOTAL"]
												),
												"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
												"COUNT" => $arResult["COUNT_DATA"]["ZONA_POSADKI"],
												"VALUES" => $arResult["OFFER_PARAMS"]["ZONA_POSADKI"]["VALUES"]
											);
											
											$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_ZONA_POSADKI', $arPropertyParams);
											if($strHtml) {
												echo $strHtml;
											}
											?>
										</div>
									</div>
								</div>
								
								<div class="col col--double">
									
									<div class="filter__group js-filter-group">
										
										<div class="filter__group-title">
											Цветение и плоды
											<div class="filter__group-title-actions">
												<div class="filter__group-title-action js-filter-group-reset tooltip" data-title="Сбросить все фильтры группы">
													<svg class="icon icon--cross">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</div>
											</div>
										</div>
										
										<div class="filter__group-content">
											
											<div class="cols-wrapper cols-wrapper--filter-col-double">
												<div class="cols">
													
													<div class="col"><?
														// COLOR_FLOWER
														$arTmp = $arResult["OFFER_PARAMS"]["COLOR"]["VALUES"];
														unset($arTmp["drK4dD37"]);
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Цвет цветков (шишки)",
															"DISABLED" => $arResult["OFFER_PARAMS"]["COLOR_FLOWER"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-color_flower" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
															"DEFAULT" => array(
																"NAME" => "Любой",
																"COUNT" => $arResult["COUNT_DATA"]["COLOR_FLOWER"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["COLOR_FLOWER"],
															"VALUES" => $arTmp
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_COLOR_FLOWER', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}
														
														// COLOR_LEAF
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Цвет листьев (хвои)",
															"DISABLED" => $arResult["OFFER_PARAMS"]["COLOR_LEAF"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-color_leaf" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
															"DEFAULT" => array(
																"NAME" => "Любой",
																"COUNT" => $arResult["COUNT_DATA"]["COLOR_LEAF"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["COLOR_LEAF"],
															"VALUES" => $arResult["OFFER_PARAMS"]["COLOR"]["VALUES"]
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_COLOR_LEAF', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}
														
														
														// COLOR_FRUIT
														$arTmp = $arResult["OFFER_PARAMS"]["COLOR"]["VALUES"];
														unset($arTmp["drK4dD37"]);
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Цвет плодов",
															"DISABLED" => $arResult["OFFER_PARAMS"]["COLOR_FRUIT"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-color_fruit" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
															"DEFAULT" => array(
																"NAME" => "Любой",
																"COUNT" => $arResult["COUNT_DATA"]["COLOR_FRUIT"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["COLOR_FRUIT"],
															"VALUES" => $arTmp
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_COLOR_FRUIT', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}
														
														
														// COLOR_LEAF_AUTUMN
														$arTmp = $arResult["OFFER_PARAMS"]["COLOR"]["VALUES"];
														unset($arTmp["drK4dD37"]);
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Осенняя окраска",
															"DISABLED" => $arResult["OFFER_PARAMS"]["COLOR_LEAF_AUTUMN"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox" id="ddbox-color_leaf" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
															"DEFAULT" => array(
																"NAME" => "Любая",
																"COUNT" => $arResult["COUNT_DATA"]["COLOR_LEAF_AUTUMN"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["COLOR_LEAF_AUTUMN"],
															"VALUES" => $arTmp
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_COLOR_LEAF_AUTUMN', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}?>
													</div>
													
													<div class="col"><?
														// COLOR_BARK
														$arTmp = $arResult["OFFER_PARAMS"]["COLOR"]["VALUES"];
														unset($arTmp["drK4dD37"]);
														
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Цвет коры",
															"DISABLED" => $arResult["OFFER_PARAMS"]["COLOR_BARK"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox ddbox--wide js-ddbox ddbox--wide ddbox--expanded-left" id="ddbox-color_bark" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
															"DEFAULT" => array(
																"NAME" => "Любой",
																"COUNT" => $arResult["COUNT_DATA"]["COLOR_BARK"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["COLOR_BARK"],
															"VALUES" => $arTmp
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('checkbox', 'PROPERTY_COLOR_BARK', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}
														
														
														// FLOWERING
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Период цветения",
															"DISABLED" => $arResult["OFFER_PARAMS"]["FLOWERING"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-period-flower" data-ddbox-value-separator=" &mdash; "',
															"DEFAULT" => array(
																"NAME" => "Январь &mdash; Декабрь",
																"COUNT" => $arResult["COUNT_DATA"]["FLOWERING"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["FLOWERING"],
															"VALUES" => $arResult["OFFER_PARAMS"]["FLOWERING"]["VALUES"]
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('period', 'PROPERTY_FLOWERING', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}
														
														
														// RIPENING
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Период созревания плодов",
															"DISABLED" => $arResult["OFFER_PARAMS"]["RIPENING"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-period-ripening" data-ddbox-value-separator=" &mdash; "',
															"DEFAULT" => array(
																"NAME" => "Январь &mdash; Декабрь",
																"COUNT" => $arResult["COUNT_DATA"]["RIPENING"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["RIPENING"],
															"VALUES" => $arResult["OFFER_PARAMS"]["RIPENING"]["VALUES"]
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('period', 'PROPERTY_RIPENING', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														}
														
														
														// TASTE
														$arPropertyParams = array(
															"BLOCK_TITLE" => "Вкус плодов",
															"DISABLED" => $arResult["OFFER_PARAMS"]["TASTE"]["DISABLED"],
															"BLOCK_PARAMS" => ' class="ddbox js-ddbox" id="ddbox-taste" data-ddbox-aggregate="8" data-ddbox-value-separator=""',
															"DEFAULT" => array(
																"NAME" => "Любой",
																"COUNT" => $arResult["COUNT_DATA"]["TASTE"]["TOTAL"]
															),
															"CURRENT_COUNT" => $arResult["COUNT_OFFERS"],
															"COUNT" => $arResult["COUNT_DATA"]["TASTE"],
															"VALUES" => $arResult["OFFER_PARAMS"]["TASTE"]["VALUES"]
														);
														
														$strHtml = \CatalogFilter::getPropertyHtml('radio', 'PROPERTY_TASTE', $arPropertyParams);
														if($strHtml) {
															echo $strHtml;
														} ?>
													</div>
												
												</div>
											</div>
										
										</div>
									
									</div>
								
								</div>
							
							</div>
						</div>
					
					</div>
				</div><?
				} ?>
				
			
				
				<div class="filter__footer">
			
			<div class="cols-wrapper cols-wrapper--filter-footer">
				<div class="cols"><?
					$bChecked = \CatalogFilter::$arRequestFilter["PROPERTY_RECOMMENDED"]==1;
					$bDisabled = $arResult["COUNT_DATA"]["RECOMMENDED"][1]<=0;
					
					if($arResult["COUNT_DATA"]["RECOMMENDED"][1] > 0) {
						if($bChecked) {
							$intOffersDelta = $arResult["COUNT_DATA"]["RECOMMENDED"]["TOTAL"] - $arResult["COUNT_DATA"]["RECOMMENDED"][1];
						} else {
							$intOffersDelta = $arResult["COUNT_DATA"]["RECOMMENDED"][1] - $arResult["COUNT_DATA"]["RECOMMENDED"]["TOTAL"];
						}
					} else {
						$intOffersDelta = 0;
					} ?>
					<div class="col col--filter-recommend mobile-hide"><?
						if(!$arResult["IS_NEW"] && !$arResult["IS_ACTION"]) {?>
						<div class="maxw-like-filter-col">
							<div class="checkboxes js-filter-not-ddbox" data-place='{"0": ".js-filter-recommend-mobile-place", "640": ".js-filter-recommend-place"}'>
								<div class="checkboxes__inner">
									<label class="checkbox checkbox--block checkbox-plain-js<?=($bDisabled?' disabled':'')?>">
										<div class="checkbox__icon"></div>
										<input type="checkbox" name="F[PROPERTY_RECOMMENDED]"<?=($bChecked?' checked':'').($bDisabled?' disabled':'')?> value="1">
										Хиты сезона <span class="count"><?=($intOffersDelta>0?'+'.$intOffersDelta:$intOffersDelta)?></span>
									</label>
								</div>
							</div>
							<div class="js-filter-recommend-place"></div>
						</div><?
						} ?>
					</div>
					
					<div class="col col--filter-total">
						
						<div class="ftotals">
							<div class="ftotal">
								Растений: <span class="ftotal__value js-filter-total-value"><?=\ig\CHelper::formatNumber($arResult["COUNT_PRODUCTS"])?></span>
							</div>
							<div class="ftotal">
								Предложений: <span class="ftotal__value js-filter-total-value"><?=\ig\CHelper::formatNumber($arResult["COUNT_OFFERS"])?></span>
							</div>
						</div>
					
					</div>
					
					<div class="col col--filter-result-btn">
						
						<a href="#" class="btn js-filter-show-results js-ddbox-close-all" data-expand-selector="#filter-main-list">Посмотреть</a>
					
					</div>
					
					<div class="col col--filter-link mobile-hide">
						
						<div class="filter-link" data-place='{"0": ".js-filter-link-mobile-place", "640": ".js-filter-link-place"}'>
							
							<a href="<?=$arResult["RESULT_LINK"]?>" class="link--dotted js-copy" data-clipboard-text="<?=$arResult["RESULT_LINK"]?>" data-title="Ссылка скопирована">Ссылка на результат поиска</a>
						
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