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
			<h1><?=$arResult["NAME"]?></h1><?
			if($arResult["DISPLAY_PROPERTIES"]["TEXT_BEFORE_PIC"]["DISPLAY_VALUE"]) {?>
			<p><?=$arResult["DISPLAY_PROPERTIES"]["TEXT_BEFORE_PIC"]["DISPLAY_VALUE"]?></p><?
			}
			
			if($arResult["DETAIL_PICTURE"]["SRC"]) {?>
			<div class="image-slider image-slider--noshadow image-slider--autosize js-images-hover-slider js-images-popup-slider cursor-pointer">
				<img class="active" data-lazy-src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" src="/local/templates/ig/img/blank.png" data-src-full="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="">
				<div class="image-slider__size">
					<img src="/local/templates/ig/pic/vip-card.jpg" alt="">
				</div>
			</div><?
			}
			
			if($arResult["DISPLAY_PROPERTIES"]["TEXT_AFTER_PIC"]["DISPLAY_VALUE"]) {?>
				<p><?=$arResult["DISPLAY_PROPERTIES"]["TEXT_AFTER_PIC"]["DISPLAY_VALUE"]?></p><?
			}
			
			echo $arResult["DETAIL_TEXT"];
			
			?><?$APPLICATION->IncludeComponent(
				"bitrix:advertising.banner",
				"line-2",
				Array(
					"CACHE_TIME" => "0",
					"CACHE_TYPE" => "N",
					"NOINDEX" => "N",
					"QUANTITY" => "2",
					"TYPE" => "SECTION_LINK_2",
					"TITLE" => "А ещё"
				)
			);?>
		</div>
	</div>