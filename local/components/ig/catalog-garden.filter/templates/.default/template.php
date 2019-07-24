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

if($arParams["IS_AJAX"] != 'Y') {
?>
<div class="section section--filter bg-pattern bg-pattern--grey-leaves">
	<div class="container">
	
		<div class="ltabs">
			<div class="ltabs__list">
				<div class="ltabs__item">
					<div class="flex flex-align-baseline">
						<a class="ltabs__link" href="/katalog/rasteniya/">
							Растения
						</a>
						<div class="nav stabs mobile-hide">
							<div class="stabs__list">
								<div class="stabs__item hidden">
									<a class="stabs__link" href="/katalog/rasteniya/">
										Все
									</a>
								</div>
							</div>
						</div>
						<div class="nav stabs mobile-hide">
							<div class="stabs__list">
								<div class="stabs__item">
									<a href="/upload/1c_files/Price.xls" class="nounderline-important">
										<svg class="icon">
											<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-download-cloud"></use>
										</svg>
										<span class="stabs__link stabs__link--link no-text-transform">
                                            Прайс
                                        </span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ltabs__item active">
					<div class="flex flex-align-baseline">
						<a class="ltabs__link" href="/katalog/tovary-dlya-sada/">
							Товары для сада
						</a>
						<div class="nav stabs" data-place='{"0": ".js-filter-stabs-mobile-place", "640": ".js-filter-stabs-place"}'>
							<div class="stabs__list">
								<div class="stabs__item<?=((!$arResult["IS_NEW"] && !$arResult["IS_ACTION"])?' active':'')?> hidden">
									<a class="stabs__link" href="/katalog/tovary-dlya-sada/">
										Все
									</a>
								</div>
								<div class="stabs__item mobile-show-inline-block">
									<a href="/upload/1c_files/Price_Sad.xls" class="nounderline-important">
										<svg class="icon">
											<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-download-cloud"></use>
										</svg>
										<span class="stabs__link stabs__link--link no-text-transform">
                                            Прайс
                                        </span>
									</a>
								</div>
							</div>
						</div>
						<div class="js-filter-stabs-place"></div>
						<div class="nav stabs mobile-hide">
							<div class="stabs__list">
								<div class="stabs__item">
									<a href="/upload/1c_files/Price_Sad.xls" class="nounderline-important">
										<svg class="icon">
											<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-download-cloud"></use>
										</svg>
										<span class="stabs__link stabs__link--link no-text-transform">
                                            Прайс
                                        </span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-panes">
			<div class="filter-top">
				<div class="js-filter-stabs-mobile-place"></div>
			</div><?
}

include("form.php");

if($arParams["IS_AJAX"] != 'Y') { ?>
		</div>
	</div>
</div><?
}?>