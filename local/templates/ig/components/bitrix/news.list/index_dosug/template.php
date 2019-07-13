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

if(!empty($arResult["ITEMS"])) { ?>
	<div class="section section--block section--grey section--bring-to-front" id="entertainment" data-goto-offset-element=".header">
	<div class="container">
		<div class="text text-align-center">
			
			<h2 class="h1"><? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
					"AREA_FILE_SHOW"   => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE"    => "",
					"PATH"             => "/local/inc_area/index_dosug_title.php"
				)); ?></h2>
			
			<p><? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
					"AREA_FILE_SHOW"   => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE"    => "",
					"PATH"             => "/local/inc_area/index_dosug_text.php"
				)); ?></p>
		</div>
		<div class="tgbs tgbs--ent">
			<div class="tgbs__inner"><?
				foreach ($arResult["ITEMS"] as $arItem) {
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					
					$arClass = array();
					
					if(!empty($arItem["PROPERTIES"]["INDEX_ROW"]["VALUE_XML_ID"])) {
						$arClass[] = $arItem["PROPERTIES"]["INDEX_ROW"]["VALUE_XML_ID"];
					} ?>
					<div class="tgb tgb--img-hover tgb--ent<?=(empty($arClass)?'':' '.implode(" ", $arClass))?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							
							<div class="tgb__image img-to-bg">
								<img data-lazy-src="<?=$arItem["TILE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="<?=$arItem["NAME"]?>">
							</div>
							
							<div class="tgb__overlay">
								<div class="tgb__overlay-part">
									<div class="tgb__overlay-part-inner">
										<div class="tgb__title h2 uppercase"><?=$arItem["NAME"]?></div>
									</div>
								</div>
								<div class="tgb__overlay-part mobile-hide">
									<div class="tgb__overlay-part-inner"><?
										if($arItem["PROPERTIES"]["INDEX_TEXT"]["VALUE"]) { ?>
											<div class="tgb__summary text">
											<p><?=$arItem["PROPERTIES"]["INDEX_TEXT"]["VALUE"]?></p>
											</div><?
										} ?>
									</div>
								</div>
							</div>
						</a>
					</div><?
				} ?>
			</div>
		</div>
	
	</div>
	<div class="borders-saw"></div>
	<div class="borders-saw borders-saw--bottom"></div>
	</div><?
}