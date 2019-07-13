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

$strBackground = \ig\CUtil::getFileRecursive("background-image.jpg");
if(empty($strBackground)) {
	$strBackground = SITE_TEMPLATE_PATH.'/pic/hero-image.jpg';
}

$strNav = '';
foreach($arResult["SECTIONS"] as $arSection) {
	$strNav .= '
	<li class="tabs__item">
		<a class="tabs__link tabs__link--block js-goto" href="#'.$arSection["CODE"].'">
			<svg class="icon">
				<use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#'.$arSection["UF_ICON"].'"></use>
			</svg>
			<span class="link link--ib link--dotted">'.$arSection["NAME"].'</span>
		</a>
	</li>';
}

?>
<div class="section section--bring-to-front section--hero">
	<div class="section__bg img-to-bg">
		<img data-lazy-src="<?=$strBackground?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
	</div>
	<div class="container">
		<div class="hero hero--default">
			<div class="hero__inner">
				<div class="hero__title"><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_RECURSIVE" => "Y",
							"AREA_FILE_SHOW" => "sect",
							"AREA_FILE_SUFFIX" => "inc_title",
							"EDIT_TEMPLATE" => ""
						)
					);?></div>
				<div class="hero__summary mobile-hide">
					<p><?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_RECURSIVE" => "Y",
								"AREA_FILE_SHOW" => "sect",
								"AREA_FILE_SUFFIX" => "inc_text",
								"EDIT_TEMPLATE" => ""
							)
						);?></p>
				</div>
			</div><?
			if(!empty($strNav)) {?>
			<nav class="tabs tabs--services tabs--flex" data-goto-hash-change="true">
				<div class="tabs__inner js-tabs-fixed-center">
					<div class="tabs__scroll js-tabs-fixed-center-scroll">
						<div class="tabs__scroll-inner">
							<ul class="tabs__list">
								<?=$strNav?>
							</ul>
						</div>
					</div>
				</div>
			</nav><?
			}?>
		</div>
	</div>
</div><?
if($strNav) {?>
<div class="tabs-vert-fixed scroll-activate" data-scroll-activate-trigger="#vertical-tabs-trigger" data-scroll-activate-offset-element=".header" data-scroll-activate-trigger-hook="onLeave" data-scroll-activate-reverse="true">
	
	<nav class="tabs tabs--vert tabs--active-allowed" data-goto-hash-change="true">
		<div class="tabs__inner js-tabs-fixed-center">
			<div class="tabs__scroll js-tabs-fixed-center-scroll">
				<div class="tabs__scroll-inner">
					<ul class="tabs__list">
						<?=$strNav?>
					</ul>
				</div>
			</div>
		</div>
	</nav>
</div><?
}?>
<div id="vertical-tabs-trigger" class="vertical-tabs-trigger"></div>
<?
$arBGClass = array("bg-pattern--grey-school-supplies", "", "bg-pattern--grey-leaves-3", "");

$intCnt = 0;
foreach($arResult["SECTIONS"] as $arSection) {
	if($intCnt > 3) {
		$intCnt = 0;
	}
	?>
	<div class="section section--block bg-pattern <?=$arBGClass[$intCnt]?> section--saw-before" id="<?=$arSection["CODE"]?>" data-goto-offset-element=".header">
		<div class="container">
			<h2 class="h1 text-align-center"><?=$arSection["NAME"]?></h2>
			<div class="p text text-align-center">
				<p><?=$arSection["DESCRIPTION"]?></p>
			</div><?
			if(!empty($arResult["ITEMS"][$arSection["ID"]])) {?>
			
			<div class="tgbs">
				<div class="tgbs__inner text-align-center"><?
					foreach($arResult["ITEMS"][$arSection["ID"]] as $arItem) {?>
					<div class="tgb tgb--img-hover tgb--1-2">
						<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<div class="tgb__image img-to-bg">
								<img data-lazy-src="<?=$arItem["TILE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="<?=$arItem["NAME"]?>">
							</div>
							<div class="tgb__overlay">
								<div class="tgb__overlay-part">
									<div class="tgb__overlay-part-inner">
										<div class="tgb__title h2"><?=$arItem["NAME"]?></div>
									</div>
								</div>
								<div class="tgb__overlay-part mobile-hide">
									<div class="tgb__overlay-part-inner">
										<div class="tgb__summary text">
											<p><?=$arItem["PREVIEW_TEXT"]?></p>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div><?
					}?>
				</div>
			</div><?
			}?>
		
		</div>
	</div><?
	$intCnt++;
}?>