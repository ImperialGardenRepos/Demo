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
<div class="section section--grey section--article section--z-auto section--fullheight text">
	<div class="container">
		
		<h1><?=$arResult["IBLOCK"]["NAME"]?></h1>
		<?
		foreach($arResult["MAIN"] as $arItem) { ?>
			<div class="bqtgb bqtgb--w-image text bg-pattern bg-pattern--grey-leaves">
				<blockquote class="bqtgb__quote">
					<p><?=$arItem["PREVIEW_TEXT"]?></p>
				</blockquote>
				<div class="bqtgb__footer">
					<div class="bqtgb__footer-image">
						<img data-lazy-src="<?=\CFile::GetPath($arItem["PREVIEW_PICTURE"])?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
					</div>
					<div class="bqtgb__footer-content">
						<span class="font-medium"><?=$arItem["NAME"]?></span><br><?
						if(!empty($arItem["PROPERTIES"]["POSITION"]["VALUE"])) {?>
							<?=$arItem["PROPERTIES"]["POSITION"]["VALUE"]?><br><br><?
						}
						if(!empty($arItem["PROPERTIES"]["PHONE"]["VALUE"])) { ?>
							<?=$arItem["PROPERTIES"]["PHONE"]["VALUE"]?><br><?
						}
						
						if(!empty($arItem["PROPERTIES"]["EMAIL"]["VALUE"])) {?>
							<a href="mailto:<?=$arItem["PROPERTIES"]["EMAIL"]["VALUE"]?>"><?=$arItem["PROPERTIES"]["EMAIL"]["VALUE"]?></a><?
						}?>
					</div>
				</div>
			</div><?
		}?>
		<div class="tabs-wrapper js-header-sticky-wrapper">
			<div class="tabs-wrapper__inner js-header-sticky">
				<nav class="tabs tabs--flex tabs--active-allowed" data-goto-hash-change="true">
					<div class="tabs__inner js-tabs-fixed-center">
						<div class="tabs__scroll tabs__scroll--nopadding js-tabs-fixed-center-scroll">
							<div class="tabs__scroll-inner">
								<ul class="tabs__list"><?
									foreach($arResult["SECTIONS"] as $arSection) {?>
									<li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#<?=$arSection["CODE"]?>">
											<span class="link link--ib link--dotted"><?=$arSection["NAME"]?></span>
										</a>
									</li><?
									}?>
								</ul>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</div><?
		foreach($arResult["SECTIONS"] as $arSection) {?>
		<div id="<?=$arSection["CODE"]?>" data-goto-offset-element=".header, .tabs-wrapper__inner" data-goto-offset-end="#<?=$arSection["CODE"]?>-end"></div>
		<h2><?=$arSection["NAME"]?></h2>
		<div class="prsns">
			<div class="prsns__inner"><?
				foreach($arResult["ITEMS"][$arSection["ID"]] as $arItem) {?>
				<div class="prsn">
					<div class="prsn__inner">
						<div class="prsn__image">
							<div class="prsn__image-inner">
								<img data-lazy-src="<?=\CFile::GetPath($arItem["PREVIEW_PICTURE"])?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
							</div>
						</div>
						<div class="prsn__content">
							<span class="prsn__title"><?=$arItem["NAME"]?></span><br><?
							if(!empty($arItem["PROPERTIES"]["POSITION"]["VALUE"])) {?>
							<?=$arItem["PROPERTIES"]["POSITION"]["VALUE"]?><br><br><?
							}
							
							if(!empty($arItem["PROPERTIES"]["PHONE"]["VALUE"])) { ?>
							<?=$arItem["PROPERTIES"]["PHONE"]["VALUE"]?><br><?
							}
							
							if(!empty($arItem["PROPERTIES"]["EMAIL"]["VALUE"])) {?>
							<a href="mailto:<?=$arItem["PROPERTIES"]["EMAIL"]["VALUE"]?>"><?=$arItem["PROPERTIES"]["EMAIL"]["VALUE"]?></a><?
							}?>
						</div>
					</div>
				</div><?
				}?>
			</div>
		</div>
		<div id="<?=$arSection["CODE"]?>-end"></div><?
		}?>
	</div>
</div>

