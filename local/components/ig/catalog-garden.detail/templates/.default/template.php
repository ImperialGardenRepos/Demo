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

$arSortProp = $arResult["PROPERTIES"];
?>
<div class="section section--grey section--plant">
	<div class="container"><?
require ('main_info.php');

$bDisplaySpecs = false;
ob_start();
?>
		<div id="specs" data-goto-offset-element=".header, .fcard__tabs" data-goto-offset-end="#specs-end"></div>
		
		<h2>Особенности</h2>
		<table class="ttable ttable--adaptive">
			<tbody><?
			foreach($arResult["PROPERTIES"] as $strCode => $arProperty) {
				if($arProperty["SORT"]<20000 && $arProperty["DISPLAYED"] != 'Y') {
					$strDisplayValue = \ig\CFormat::formatPropertyValue($arProperty["CODE"], $arProperty["VALUE"], $arProperty, array("COLOR_EXT" => "Y"));
					$strComment = $arResult["PROPERTIES"][$arProperty["CODE"].'_COMMENT']["VALUE"];
					if(strlen($strDisplayValue)>0) {
						$bDisplaySpecs = true;?>
					<tr>
						<th class="nowrap"><?=$arProperty["NAME"]?></th>
						<td><?
							echo $strDisplayValue;
							
							if(!empty($strComment)) {
								echo '<p>'.$strComment.'</p>';
							}?></td>
					</tr><?
					}
				}
			}?>
			</tbody>
		</table>
		<div id="specs-end"></div><?
		$strSpecs = ob_get_contents();
		ob_end_clean();
		
		if($bDisplaySpecs) {
			echo $strSpecs;
		}
		unset($strSpecs);
		
		if(!empty($arSortProp["IMPORTANT_INFO"]["VALUE"]["TEXT"])) { ?>
			<div class="ittgb">
				<div class="ittgb__grid">
					<div class="ittgb__content text"><?
						if($arSortProp["IMPORTANT_INFO"]["VALUE"]["TYPE"] == 'TEXT') {
							echo $arSortProp["IMPORTANT_INFO"]["VALUE"]["TEXT"];
						} else {
							echo htmlspecialcharsback($arSortProp["IMPORTANT_INFO"]["VALUE"]["TEXT"]);
						}?>
					</div>
				</div>
			</div><?
		}?>
	</div>
</div>
<?
if(false) {
	
	?>
	<div class="section section--grey section--plant">
	<div class="container">
		<div class="fcard">
			
			<div class="fcard__grid">
				
				<div class="fcard__header">
					
					<div class="tags">
						<div class="tag">Рекомендация ig</div>
					</div>
					
					<h1 class="fcard__title">
						
						Виноград девичий пяти-листочковый «Engelmannii»
					
					</h1>
					
					<div class="fcard__subtitle">Pinus Cembra pinus</div>
				
				</div>
				
				<div class="fcard__image">
					
					<div class="image-slider js-images-hover-slider js-images-popup-slider cursor-pointer">
						<img class="active" data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image2.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image2-large.jpg" data-ihs-content="Общий вид" alt="">
						<img data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3-large.jpg" data-ihs-content="Через 10 лет" alt="">
						<img data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image1.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image1-large.jpg" data-ihs-content="Зима" alt="">
						<img data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image2.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image2-large.jpg" data-ihs-content="Осень" alt="">
						<img data-lazy-src="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3.jpg" src="img/blank.png" data-src-full="<?=SITE_TEMPLATE_PATH?>/pic/plant-card-image3-large.jpg" data-ihs-content="Лето" alt="">
						<div class="image-slider__fullscreen js-images-popup-slider-open">
							<svg class="icon">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-fullscreen"></use>
							</svg>
						</div>
					</div>
				
				</div>
				
				<div class="fcard__main">
					
					<div class="p text">
						
						<p>Листопадный кустарник или небольшое дерево до 7 м в высоту. Кора серовато-жёлтая или
							серовато-коричневая, ровная. Молодые побеги опушённые, на второй год становятся почти
							гладкими и принимают тусклый красно- коричневый цвет. Почки парные.</p>
					
					</div>
					
					<div class="ptgbs ptgbs--ffeatures">
						
						<div class="ptgb">
							<div class="ptgb__inner">
								<div class="ptgb__icon">
									<svg class="icon">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#filters_forma-krony_crone-form.18"></use>
									</svg>
								</div>
								<div class="ptgb__content">
									<div class="ptgb__subtitle">Форма кроны</div>
									<div class="ptgb__title">Пирамидальная</div>
								</div>
							</div>
						</div>
						
						<div class="ptgb">
							<div class="ptgb__inner">
								<div class="ptgb__icon">
									<svg class="icon icon--sunset">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#filters_svet_demi-shadow.42"></use>
									</svg>
								</div>
								<div class="ptgb__content">
									<div class="ptgb__subtitle">Свет</div>
									<div class="ptgb__title">Полутень</div>
								</div>
							</div>
						</div>
						
						<div class="ptgb">
							<div class="ptgb__inner">
								<div class="ptgb__icon">
									<svg class="icon icon--ground">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#filters_vlaga_mid-water.46"></use>
									</svg>
								</div>
								<div class="ptgb__content">
									<div class="ptgb__subtitle">Почва</div>
									<div class="ptgb__title">Нейтральная</div>
								</div>
							</div>
						</div>
						
						<div class="ptgb">
							<div class="ptgb__inner">
								<div class="ptgb__icon">
									<svg class="icon icon--drop">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#filters_vlaga_vlagolyub.44"></use>
									</svg>
								</div>
								<div class="ptgb__content">
									<div class="ptgb__subtitle">Влага</div>
									<div class="ptgb__title">Водное</div>
								</div>
							</div>
						</div>
					
					</div>
				
				</div>
			
			</div>
			
			
			<div class="fcard__tabs js-header-sticky-wrapper">
				<div class="fcard__tabs-inner js-header-sticky">
					
					<nav class="tabs tabs--flex tabs--active-allowed" data-goto-hash-change="true">
						<div class="tabs__inner js-tabs-fixed-center">
							<div class="tabs__scroll js-tabs-fixed-center-scroll">
								<div class="tabs__scroll-inner">
									<ul class="tabs__list">
										<li class="tabs__item">
											<a class="tabs__link js-goto" href="#offers">Предложения</a></li>
										<li class="tabs__item">
											<a class="tabs__link js-goto" href="#tab-2">Особенности</a></li>
										<li class="tabs__item"><a class="tabs__link js-goto" href="#tab-3">Описание</a>
										</li>
										<li class="tabs__item"><a class="tabs__link js-goto" href="#tab-4">Сорта
												вида</a></li>
										<li class="tabs__item"><a class="tabs__link js-goto" href="#tab-5">Что важно</a>
										</li>
										<li class="tabs__item"><a class="tabs__link js-goto" href="#useful">Полезное</a>
										</li>
										<li class="tabs__item"><a class="tabs__link js-goto" href="#services">Услуги</a>
										</li>
										<li class="tabs__item"><a class="tabs__link js-goto" href="#related">Похожие
												товары</a></li>
									</ul>
								</div>
							</div>
						</div>
					</nav>
				
				</div>
			</div>
			
			
			<div id="offers" class="fcard__offers" data-goto-offset-element=".header, .fcard__tabs">
				
				<div class="thead mobile-hide">
					<div class="thead__grid">
						<div class="thead__cell thead__cell--tcard-instock js-sort sort-asc cursor-pointer user-select-none" data-sort-selector=".tcard" data-sort-data-attr="sort-instock" data-sort-order="desc">
							Наличие
							<svg class="icon icon--chevron-down">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
							</svg>
							<svg class="icon icon--chevron-up">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
							</svg>
						</div>
						<div class="thead__cell thead__cell--tcard-tags">
							Теги
						</div>
						<div class="thead__cell thead__cell--tcard-height js-sort sort-desc cursor-pointer user-select-none" data-sort-selector=".tcard" data-sort-data-attr="sort-height" data-sort-order="asc">
							Высота, см
							<svg class="icon icon--chevron-down">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
							</svg>
							<svg class="icon icon--chevron-up">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
							</svg>
						</div>
						<div class="thead__cell js-sort sort-desc cursor-pointer user-select-none" data-sort-selector=".tcard" data-sort-data-attr="sort-pack" data-sort-order="asc">
							Упаковка
							<svg class="icon icon--chevron-down">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
							</svg>
							<svg class="icon icon--chevron-up">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
							</svg>
						</div>
						<div class="thead__cell thead__cell--tcard-price js-sort sort-desc cursor-pointer user-select-none" data-sort-selector=".tcard" data-sort-data-attr="sort-price" data-sort-order="asc">
							Цена за шт.
							<svg class="icon icon--chevron-down">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
							</svg>
							<svg class="icon icon--chevron-up">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
							</svg>
						</div>
						<div class="thead__cell thead__cell--tcard-quantity">
							Кол-во
						</div>
						<div class="thead__cell thead__cell--tcard-price-total">
							Итого
						</div>
						<div class="thead__cell thead__cell--tcard-actions">
						
						</div>
					</div>
				</div>
				
				<div class="fcard__offers-content js-scrollbar" data-scrollbar-allow-page-scroll="true">
					
					
					<div class="tcards">
						<div class="tcards__inner">
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 1}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 2}' data-sort-instock="0" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle" disabled>
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 3}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
													
													
													<div class="tag-icon tooltip" data-title="Скидка">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent"></use>
														</svg>
													</div>
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 4}' data-sort-instock="1" data-sort-height="600" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																400 - 600
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 5}' data-sort-instock="0" data-sort-height="900" data-sort-pack="Кор" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																800 - 900
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Коробка
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 6}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle" disabled>
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 7}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													
													<div class="tag-icon tooltip" data-title="Скидка">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent"></use>
														</svg>
													</div>
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 8}' data-sort-instock="0" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 9}' data-sort-instock="1" data-sort-height="600" data-sort-pack="Ком" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																400 - 600
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 10}' data-sort-instock="1" data-sort-height="900" data-sort-pack="Кор" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																800 - 900
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Коробка
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle" disabled>
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 11}' data-sort-instock="0" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
													
													
													<div class="tag-icon tooltip" data-title="Скидка">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent"></use>
														</svg>
													</div>
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 12}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 13}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 14}' data-sort-instock="0" data-sort-height="600" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																400 - 600
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle" disabled>
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 15}' data-sort-instock="1" data-sort-height="900" data-sort-pack="Кор" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
													
													
													<div class="tag-icon tooltip" data-title="Скидка">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent"></use>
														</svg>
													</div>
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																800 - 900
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Коробка
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 16}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 17}' data-sort-instock="0" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 18}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle" disabled>
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 19}' data-sort-instock="1" data-sort-height="600" data-sort-pack="Ком" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
													
													
													<div class="tag-icon tooltip" data-title="Скидка">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent"></use>
														</svg>
													</div>
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																400 - 600
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">6</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="6" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total">
																	<strong class="js-icard-price-total">340
																		794</strong> <span class="font-light">₽</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add success" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>'>
													<span class="btn__title"><span class="mobile-hide">В корзине</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove ">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 20}' data-sort-instock="0" data-sort-height="900" data-sort-pack="Кор" data-sort-price="56799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled">Под заказ</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled">Под заказ</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="Под заказ">
														<span class="icon icon-circle icon-circle--inactive"></span>
													</div>
													
													<div class="tag-icon tooltip" data-title="Хит">
														<svg class="icon">
															<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-star-outline"></use>
														</svg>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																800 - 900
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Коробка
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price">
																	<span class="font-bold js-icard-price">56 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
							
							
							<div class="tcard js-icard" data-cart-data='{"id": 21}' data-sort-instock="1" data-sort-height="1200" data-sort-pack="Ком" data-sort-price="46799">
								<div class="tcard__inner js-icard-offer-actions">
									
									<div class="tcard__grid">
										
										<div class="tcard__col tcard__col--instock">
											
											<div class="tags">
												
												<div class="tag tag--circled color-green">В наличие</div>
											
											</div>
										
										</div>
										
										<div class="tcard__col tcard__col--tags">
											
											<div class="mobile-show">
												<div class="tags">
													<div class="tag">Скидка</div>
													<div class="tag">Хит</div>
													
													<div class="tag tag--circled color-green">В наличие</div>
												
												</div>
											</div>
											
											<div class="mobile-hide">
												<div class="tag-icons">
													<div class="tag-icon tooltip tablet-small-show-inline-block" data-title="В наличие">
														<span class="icon icon-circle icon-circle--active"></span>
													</div>
												
												
												</div>
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--height">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-height">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Высота (см)</div>
															<div class="ptgb__title">
																
																600 - 1200
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--param tcard__col--pack">
											
											<div class="ptgbs ptgbs--params">
												
												<div class="ptgb ptgb--param-pack">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Упаковка</div>
															<div class="ptgb__title">
																<div class="ptgb__title-inner">
																	
																	Ком в мешковине и сетке (WRB)
																
																</div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-price">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Цена</div>
															<div class="ptgb__title ptgb__title--textfield">
																
																<div class="tcard__price-old">
																	<span class="line-through">62 799 <span class="font-light">₽</span></span>
																</div>
																<div class="tcard__price color-active">
																	<span class="font-bold js-icard-price">46 799</span>
																	<span class="font-light">₽</span></div>
															
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--quantity">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-quantity">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Кол-во</div>
															<div class="ptgb__title ptgb__title--textfield">
                                                <span class="input-spin touch-focused" data-trigger="spinner">
                                                    <span class="input-spin__btn" data-spin="down">&minus;</span>
                                                    <span data-spin-clone class="input-spin__value hidden">0</span>
                                                    <input type="tel" class="input-spin__textfield textfield keyfilter-pint js-icard-spinner inactive" data-spin="spinner" data-rule="quantity" data-min="0" data-max="9999" data-step="1" data-price="300" value="0" maxlength="4" size="6">
                                                    <span class="input-spin__btn" data-spin="up">+</span>
                                                </span>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--price-total">
											
											<div class="ptgbs ptgbs--actions">
												
												<div class="ptgb ptgb--action-total">
													<div class="ptgb__inner">
														<div class="ptgb__content">
															<div class="ptgb__subtitle">Сумма</div>
															<div class="ptgb__title ptgb__title--textfield">
																<div class="tcard__price-total inactive">
																	<strong class="js-icard-price-total">0</strong>
																	<span class="font-light">₽</span></div>
															</div>
														</div>
													</div>
												</div>
											
											</div>
										
										</div>
										
										
										<div class="tcard__col tcard__col--actions">
											
											<div class="tcard__actions-btns btns">
												
												
												<button class="btn btn--cart js-cart-add" data-label='<span class="mobile-hide">В корзине</span>' data-label-empty='В<span class="mobile-hide"> корзину</span>' disabled>
													<span class="btn__title">В<span class="mobile-hide"> корзину</span></span>
													<span class="icon icon--cart-tick">
                                            <svg class="icon icon--tick">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
                                            </svg>
                                            <svg class="icon icon--cart">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
                                            </svg>
                                        </span>
												</button>
												
												
												<button class="btn btn--icon js-cart-remove  hidden">
													<svg class="icon icon--trash btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-trash"></use>
													</svg>
												</button>
												
												<button class="btn btn--icon btn--favorite js-favorites-toggle">
													<svg class="icon icon--heart btn-toggle-state-default icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
													</svg>
													<svg class="icon icon--heart btn-toggle-state-active icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart"></use>
													</svg>
													<svg class="icon btn-toggle-state-cancel icon--cross icon--nomargin">
														<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
													</svg>
												</button>
											
											</div>
										
										</div>
									
									</div>
								
								</div>
							</div>
						
						
						</div>
					</div>
				
				</div>
				
				<div class="fcard__offers-more">
					<a class="btn btn--fullwidth btn--more-small js-toggle-element" data-toggle-class="active" data-toggle-element-class="" data-toggle-label=".link" data-toggle-change-label="Свернуть все предложения" href="#offers">
						<svg class="icon icon--linespacing">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-linespacing"></use>
						</svg>
						<span class="link link--dotted link--ib">Показать все предложения</span> </a>
				</div>
			
			</div>
		
		</div>
	</div></div>
	<?
}
