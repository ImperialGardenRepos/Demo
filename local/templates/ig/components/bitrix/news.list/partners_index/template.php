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
<div class="section section--block" id="partners" data-goto-offset-element=".header">
	<div class="container">
		<h2 class="h1 text-align-center"><?=$arResult["NAME"]?></h2>
		<div class="p text text-align-center mw940 margin-auto">
			<p><?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/inc_area/partners-text.php"
					)
				);?><br>
				<a class="link--ib link--bordered" href="<?=$arResult["LIST_PAGE_URL"]?>">Все партнеры</a></p>
		</div><?
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
	</div>
</div>