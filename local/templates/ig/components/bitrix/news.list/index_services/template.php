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

if(!empty($arResult["ITEMS"])) {?>
	<div class="section section--block section--grey section--bring-to-front" id="services" data-goto-offset-element=".header">
		<div class="container">
			<div class="text text-align-center">
				<h2 class="h1"><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/inc_area/index_services_title.php"
						)
					);?></h2>
				
			
				<p><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/inc_area/index_services_text.php"
						)
					);?></p>
			</div>
			<div class="tgbs tgbs--service">
				<div class="tgbs__inner"><?
					foreach($arResult["ITEMS"] as $arItem) {
						$arClass = array();
						if(!empty($arItem["PROPERTIES"]["BLOCK_COLOR_INDEX"]["VALUE_XML_ID"])) {
							$arClass[] = $arItem["PROPERTIES"]["BLOCK_COLOR_INDEX"]["VALUE_XML_ID"];
						}
						
						if(!empty($arItem["PROPERTIES"]["IMG_LEFT"]["VALUE_XML_ID"])) {
							$arClass[] = $arItem["PROPERTIES"]["IMG_LEFT"]["VALUE_XML_ID"];
						}
						
						if(!empty($arItem["PROPERTIES"]["IS_ROW"]["VALUE"])) {
							$arClass[] = 'tgb--service-long';
						} ?>
					<div class="tgb tgb--service <?=implode(" ", $arClass)?>">
						<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<div class="tgb__overlay">
								<div class="tgb__title h4">
									<span class="link link--bordered-pseudo"><?=$arItem["NAME"]?></span>
								</div>
								<div class="tgb__summary">
									<p><?=$arItem["PREVIEW_TEXT"]?></p>
								</div>
							</div>
							<div class="tgb__image img-to-bg">
								<img data-lazy-src="<?=$arItem["TILE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
							</div>
						
						</a>
					</div><?
					}?>
				</div>
			</div>
		</div>
		<div class="borders-saw"></div>
		<div class="borders-saw borders-saw--bottom"></div>
	</div><?
}