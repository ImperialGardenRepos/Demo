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
$this->setFrameMode(true); ?>
	<div class="section section--grey section--article section--fullheight text">
		<div class="container">
			
			<h1><?=$arResult["IBLOCK"]["NAME"]?></h1>
			
			<nav class="tabs tabs--flex tabs--active-allowed js-tabs js-tabs-hash-change">
				<div class="tabs__inner js-tabs-fixed-center">
					<div class="tabs__scroll tabs__scroll--nopadding js-tabs-fixed-center-scroll">
						<div class="tabs__scroll-inner">
							<ul class="tabs__list"><?
								$intCnt = 0;
								foreach($arResult["SECTIONS"] as $arSection) {?>
								<li class="tabs__item<?=($intCnt==0?' active':'')?>"><a class="tabs__link tabs__link--block" href="#<?=$arSection["CODE"]?>">
										<span class="link link--ib link--dotted"><?=$arSection["NAME"]?></span>
									</a>
								</li><?
									$intCnt++;
								}?>
							</ul>
						</div>
					</div>
				</div>
			</nav>
			<div class="tab-panes tab-panes--margin tab-panes--nostyle"><?
				$intCnt = 0;
				foreach($arResult["SECTIONS"] as $arSection) {?>
				<div class="tab-pane tab-pane--hidden<?=($intCnt==0?' active':'')?>" id="<?=$arSection["CODE"]?>">
					<div class="tab-panes tab-panes--nostyle tab-panes--margin tab-panes--active-allowed container-anti-mobile"><?
						foreach($arResult["ITEMS"][$arSection["ID"]] as $arItem) {
							$strCode = empty($arItem["CODE"])?$arItem["ID"]:$arItem["CODE"]; ?>
						<div class="tab-pane tab-pane--expand-it expand-it-wrapper expand-it-wrapper-collapse-siblings" id="sales-faq-<?=$strCode?>">
							<a href="#" class="tab-pane__title expand-it expand-it-hash-change">
								<div class="tab-pane__title-toggler">
									<svg class="icon">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
									</svg>
								</div>
								<span class="link link--dotted"><?=$arItem["NAME"]?></span>
							</a>
							<div class="tab-pane__container expand-it-container">
								<div class="tab-pane__inner expand-it-inner">
									<?=$arItem["PREVIEW_TEXT"]?>
								</div>
							</div>
						
						</div><?}
						?>
					</div>
				</div><?
					$intCnt++;
				}?>
			</div>
		</div>
	</div>