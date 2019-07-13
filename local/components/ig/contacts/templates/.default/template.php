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

if($arParams["IS_AJAX"]) { ?>
<div class="header__popover-content header__popover-content--fheight js-scrollbar">
	<div class="header__popover-block"><?
} else { ?>
<div class="section section--grey section--article section--contacts section--fullheight text">
	<div class="container">
		<h1>Контакты</h1><?
}?>
		<div class="ltabs-wrapper contacts container-anti-mobile">
			<div class="ltabs js-tabs">
				<div class="ltabs__list">
					<div class="ltabs__item ltabs__item--noborder ltabs__item--nohover text-align-center active">
						
						<a class="ltabs__link ltabs__link--expand-click-area ltabs__link--dotted" href="#garden">
							Садовый центр </a>
					</div>
					
					<div class="ltabs__item ltabs__item--noborder ltabs__item--nohover text-align-center">
						<a class="ltabs__link ltabs__link--expand-click-area ltabs__link--dotted" href="#office">
							Офис </a>
					</div>
				</div>
			</div>
			<div class="tab-panes">
				
				<div class="tab-pane tab-pane--default tab-pane--hidden active js-contacts-tab-pane" id="garden" data-point="<?=\ig\CRegistry::get("contacts-centr-coord")?>" data-zoom="12">
					<div class="h4"><?=\ig\CRegistry::get("contacts-centr-adres");?></div>
					<p><?=\ig\CRegistry::get("contacts-centr-grafik-raboty");?></p>
					<div class="h4 font-bold"><?=\ig\CRegistry::get("contacts-centr-telefon");?></div>
					
					<div class="h4"><a href="mailto:<?=\ig\CRegistry::get("email")?>" class="link--bordered-pseudo"><?=\ig\CRegistry::get("email")?></a>
					</div>
				</div>
				<div class="tab-pane tab-pane--default tab-pane--hidden js-contacts-tab-pane" id="office" data-point="<?=\ig\CRegistry::get("contacts-ofis-coord")?>" data-zoom="12">
					<div class="h4"><?=\ig\CRegistry::get("contacts-ofis-adres");?></div>
					<p><?=\ig\CRegistry::get("contacts-ofis-grafik-raboty");?></p>
					<div class="h4 font-bold"><?=\ig\CRegistry::get("contacts-ofis-telefon");?></div>
					<div class="h4"><a href="mailto:<?=\ig\CRegistry::get("email")?>" class="link--bordered-pseudo"><?=\ig\CRegistry::get("email")?></a>
					</div>
				</div>
			</div>
			<div class="contacts__map"><?
				if(false) {?>
				<div class="contacts__map-tabs mobile-hide">
					<nav class="tabs tabs--active-allowed">
						<div class="tabs__inner js-tabs-fixed-center">
							<div class="tabs__scroll js-tabs-fixed-center-scroll">
								<div class="tabs__scroll-inner">
									<ul class="tabs__list">
										<li class="tabs__item active"><a class="tabs__link tabs__link--block" href="#">
												<svg class="icon">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-car"></use>
												</svg>
												<span>
                                                    <span class="link link--dotted">На&nbsp;машине</span>
                                                </span> </a></li>
										<li class="tabs__item"><a class="tabs__link tabs__link--block" href="#">
												<svg class="icon">
													<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-bus"></use>
												</svg>
												<span>
                                                    <span class="link link--dotted">Общественным транспортом</span>
                                                </span> </a></li>
									</ul>
								</div>
							</div>
						</div>
					</nav>
				</div><?
				}?>
				<div class="map js-contacts-map" data-point="<?=\ig\CRegistry::get("contacts-centr-coord")?>" data-center="<?=\ig\CRegistry::get("contacts-centr-coord")?>" data-zoom="12"></div>
			</div><?
			$intFile = \ig\CRegistry::get("kontakty-rekvizity");
			
			if($intFile>0) {?>
			<div class="action-bar h3 text-align-center">
				<a target="_blank" href="<?=CFile::GetPath($intFile)?>" class="nounderline-important">
					<span class="link link--bordered-pseudo">Скачать реквизиты</span>
					<svg class="icon icon--file-large vmiddle">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-xls"></use>
					</svg>
				</a>
			</div><?
			}?>
		</div>
	</div>
</div>
