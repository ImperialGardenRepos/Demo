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
<div id="rent-container">
	<a name="rent-list" id="rent-list"></a>
	<h2><?=$arResult["NAME"]?></h2><?
	if(!empty($arResult["SECTIONS"])) { ?>
		<nav class="tabs tabs--projects tabs--flex">
			<div class="tabs__inner js-tabs-fixed-center">
				<div class="tabs__scroll js-tabs-fixed-center-scroll">
					<div class="tabs__scroll-inner">
						<ul class="tabs__list">
							<li class="tabs__item"><a data-container="rent-container" class="js-reload-container tabs__link tabs__link--block<?=(empty($arResult["SECTION"]["PATH"])?' active':'')?>" href="<?=$arResult["LIST_PAGE_URL"]?>#rent-list">
									<span class="link link--ib link--dotted">Все (<?=intval($arResult["TOTAL_COUNT"])?>)</span> </a></li><?
							foreach($arResult["SECTIONS"] as $arSection) {
								if($arSection["ELEMENT_CNT"]>0) {?>
							<li class="tabs__item"><a data-container="rent-container" class="js-reload-container tabs__link tabs__link--block<?=($arParams["PARENT_SECTION_CODE"]==$arSection["CODE"]?' active':'')?>" href="<?=$arSection["SECTION_PAGE_URL"]?>#rent-list">
									<span class="link link--ib link--dotted"><?=$arSection["NAME"]?> (<?=intval($arSection["ELEMENT_CNT"])?>)</span> </a></li><?
								}
							}?>
						</ul>
					</div>
				</div>
			</div>
		</nav><?
	}?>


	<div class="scards">
		<div class="scards__inner" id="container-<?=$arParams["PAGER_TITLE"]?>"><?
			foreach($arResult["ITEMS"] as $arItem) {?>
			<div class="scard">
				<div class="scard__inner">
					<div class="scard__title"><?=$arItem["NAME"]?></div>
					<div class="scard__content">
						<div class="scard__image image-slider image-slider--noshadow">
							<img class="active" data-lazy-src="<?=$arItem["TILE"]["SRC"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
						</div>
						<div class="scard__summary text">
							<p><?=$arItem["PREVIEW_TEXT"]?></p>
						</div>
					</div>
					<div class="scard__params">
						<div class="pparams"><?
							$arPropAddon = array(
								"PRICE" => 'руб',
								"WEIGHT" => 'кг'
							);
							foreach($arItem["DISPLAY_PROPERTIES"] as $arProp) {?>
							<div class="pparam">
								<div class="pparam__title"><?=$arProp["NAME"]?>:</div>
								<div class="pparam__value nowrap"><?=$arProp["DISPLAY_VALUE"]?> <?=$arPropAddon[$arProp["CODE"]]?></div>
							</div><?
							}?>
						</div>
					</div>
				</div>
			</div><?
			}?>
		</div>
	</div>
	<?=$arResult["NAV_STRING"]?>
</div>