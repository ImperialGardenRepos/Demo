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

if(!empty($arResult['SECTIONS'])) { ?>
	<div class="section section--block bg-pattern bg-pattern--grey-leaves-2 section--saw-before" id="objects" data-goto-offset-element=".header">
		<div class="container">
			<div class="text text-align-center">
				<h2 class="h1"><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/inc_area/index_objects_title.php"
						)
					);?></h2>
				
				<p><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/inc_area/index_objects_text.php"
						)
					);?></p>
			</div>
			<div class="tgbs tgbs--ent">
				<div class="tgbs__inner"><?
					foreach ($arResult['SECTIONS'] as $arSection) {
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
						
						if(!empty($arSection["UF_LINK"])) {
							$arSection["SECTION_PAGE_URL"] = $arSection["UF_LINK"];
						}
						
						if($arSection["PICTURE"]["ID"]>0) {
							$arFileTmp = CFile::ResizeImageGet(
								$arSection["PICTURE"]["ID"],
								array("width" => 600, 'height' => 420),
								BX_RESIZE_IMAGE_EXACT,
								false
							);
							
							$strImage = $arFileTmp["src"];
						} else {
							$strImage = '';
						}
						?>
						<div  id="<? echo $this->GetEditAreaId($arSection['ID']); ?>" class="tgb tgb--img-hover tgb--ent tgb--ent-1-2">
							<a class="tgb__inner" href="<?=$arSection["SECTION_PAGE_URL"]?>">
								
								<div class="tgb__image img-to-bg">
									<img data-lazy-src="<?=$strImage?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
								</div>
								<div class="tgb__overlay">
									<div class="tgb__overlay-part">
										<div class="tgb__overlay-part-inner">
											<div class="tgb__title h2 uppercase"><?=$arSection["NAME"]?></div>
										</div>
									</div>
									<div class="tgb__overlay-part mobile-hide">
										<div class="tgb__overlay-part-inner">
											<div class="tgb__summary text">
												<p><?=$arSection["DESCRIPTION"]?></p>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div><?
					}?>
				</div>
			</div>
		
		</div>
	</div>
	<?
}