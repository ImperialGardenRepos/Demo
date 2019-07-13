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
?>
<div class="section section--grey section--article section--fullheight text">
	<div class="container">
		
		<h1><?=$arResult["NAME"]?></h1>
		
		
		<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/partners-text.php"
				)
			);?></p>
		
		<?
		if(!empty($arResult["ITEMS"])) {?>
		<div class="partners">
			<div class="partners__inner"><?
				foreach($arResult["ITEMS"] as $arItem) {?>
				<div class="partner">
					<div class="partner__inner"><?
						if($arItem["PREVIEW_PICTURE"]) {?>
						<div class="partner__image">
							<img data-lazy-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
						</div><?
						}?>
						<div class="partner__title"><?=$arItem["NAME"]?></div><?
						if($arItem["PROPERTIES"]["LINK"]["VALUE"]) {?>
						<div class="partner__summary"><a class="link--ib link--bordered text-ellipsis" target="_blank" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>"><?=str_replace(array("http://", "https://"), '', $arItem["PROPERTIES"]["LINK"]["VALUE"])?></a></div><?
						}?>
					</div>
				</div><?
				}?>
			</div>
		</div><?
		}?>
		<?=$arResult["NAV_STRING"];?>
	</div>
</div>