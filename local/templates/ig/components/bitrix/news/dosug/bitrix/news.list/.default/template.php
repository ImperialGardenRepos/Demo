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
		<div class="cols-wrapper cols-wrapper--h1-stabs">
			<div class="cols">
				<div class="col col--heading">
					<h1><?=$arResult["NAME"]?></h1>
				</div>
			</div>
		</div><?
		if($arResult["NAV_RESULT"] -> NavPageNomer < 2) {?>
		<div class="p text"><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/dosug_text.php"
				)
			);?>
		</div><?
		}
		if(!empty($arResult["ITEMS"])) {?>
		<div class="tgbs tgbs--mm">
			<div class="tgbs__inner" id="container-<?=$arParams["PAGER_TITLE"]?>"><?
			foreach($arResult["ITEMS"] as $arItem) {
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
				<div class="tgb tgb--1-2 tgb--h50 tgb--img-hover">
					<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						<div class="tgb__image img-to-bg">
							<img data-lazy-src="<?=$arItem["TILE"]["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="<?=$arItem["NAME"]?>">
						</div>
						<div class="tgb__overlay">
							<div class="tgb__overlay-part">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__title h3"><?=$arItem["NAME"]?></div>
								</div>
							</div>
							<div class="tgb__overlay-part mobile-hide">
								<div class="tgb__overlay-part-inner">
									<div class="text">
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
			echo $arResult["NAV_STRING"];
		}?>
	</div>
</div>
