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
					<h1><?=(empty($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"])? $arResult["NAME"]:$arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"])?></h1>
				</div>
				<div class="col col--stabs">
					<div class="nav stabs">
						<div class="stabs__list">
							<div class="stabs__item<?=($APPLICATION->GetCurDir()==$arParams["IBLOCK_URL"]?' active':'')?>">
								<a class="stabs__link" href="<?=$arParams["IBLOCK_URL"]?>">
									Все
								</a>
							</div><?
							foreach ($arResult["ROOT_SECTIONS"] as $arSec) {?>
							<div class="stabs__item<?=($arSec["ID"]==$arResult["SELECTED_ROOT_SECTION_ID"]?' active':'')?>">
								<a class="stabs__link" href="<?=$arSec["SECTION_PAGE_URL"]?>">
									<?=$arSec["NAME"]?>
								</a>
								</div><?
							}?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<nav class="tabs tabs--flex tabs--active-allowed">
			<div class="tabs__inner js-tabs-fixed-center">
				<div class="tabs__scroll js-tabs-fixed-center-scroll">
					<div class="tabs__scroll-inner">
						<ul class="tabs__list"><?
							foreach($arResult["SECTIONS"] as $arSection) {?>
							<li class="tabs__item<?=($arSection["ID"]==$arResult["SELECTED_SECTION_ID"]?' active':'')?>"><a class="tabs__link tabs__link--block" href="<?=$arSection["SECTION_PAGE_URL"]?>">
									<span class="link link--ib link--dotted"><?=$arSection["NAME"]?></span>
								</a>
							</li><?
							}?>
						</ul>
					</div>
				</div>
			</div>
		</nav><?
		if(isset($arResult["MAIN_ITEM"])) {
			$arItem = $arResult["MAIN_ITEM"];
		
			$arImages = array();
			if($arItem["DETAIL_PICTURE"]>0) {
				$arImages[] = $arItem["DETAIL_PICTURE"];
			}
			
			foreach($arItem["PROPERTIES"]["PHOTO"]["VALUE"] as $intPhotoID) {
				$arImages[] = $intPhotoID;
			}
			
			$strImages = '';
			$strStartImage = '';
			foreach($arImages as $intCnt => $intImageID) {
				$arFileTmp = CFile::ResizeImageGet(
					$intImageID,
					array("width" => 600, 'height' => 495),
					BX_RESIZE_IMAGE_EXACT,
					false
				);
				
				if($intCnt == 0) {
					$strStartImage = $arFileTmp["src"];
				}
				
				$strImages .= '<img'.($intCnt==0?' class="active"':'').' data-lazy-src="'.$arFileTmp["src"].'" src="'.SITE_TEMPLATE_PATH.'/img/blank.png" data-src-full="'.Cfile::GetPath($intImageID).'" alt="">';
			} ?>
		<div class="fcard fcard--project">
			<div class="fcard__grid">
				<div class="fcard__image">
					<div class="image-slider image-slider--autosize image-slider--autosize-h js-images-hover-slider js-images-popup-slider cursor-pointer">
						<?=$strImages?>
						<div class="image-slider__size">
							<img src="<?=$strStartImage?>" alt="">
						</div>
						<div class="image-slider__fullscreen js-images-popup-slider-open">
							<svg class="icon">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-fullscreen"></use>
							</svg>
						</div>
					</div>
				
				</div>
				<div class="fcard__header">
					<h2 class="fcard__title">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="link--bordered-pseudo">
							<?=$arItem["NAME"]?>
						</a>
					</h2>
				</div>
				<div class="fcard__main">
					<div class="pparams">
						<div class="pparam">
							<div class="pparam__title">Дата окончания работ</div>
							<div class="pparam__value"><?=$arItem["DISPLAY_ACTIVE_TO"]?> год</div>
						</div><?
						if($arItem["PROPERTIES"]["TOTAL_AREA"]["VALUE"]) {?>
						<div class="pparam">
							<div class="pparam__title">Общая площадь</div>
							<div class="pparam__value"><?=$arItem["PROPERTIES"]["TOTAL_AREA"]["VALUE"]?> га</div>
						</div><?
						}?>
					</div>
					
					<div class="p text">
						<p><?=$arItem["PREVIEW_TEXT"]?></p><?
						if($arItem["PROPERTIES"]["TASKS"]["VALUE"]) {?>
						<p><strong>Решенные задачи</strong></p>
						
						<ol class="list-cols-2"><?
							foreach($arItem["PROPERTIES"]["TASKS"]["VALUE"] as $strTask) {?>
							<li><?=$strTask?></li><?
							}?>
						</ol><?
						}?>
					</div>
				</div>
			</div>
		</div><?
		}
		
		if(!empty($arResult["ITEMS"])) { ?>
		<div class="tgbs tgbs--project tgbs--project-mm">
			<div class="tgbs__inner" id="container-<?=$arParams["PAGER_TITLE"]?>"><?
				foreach($arResult["ITEMS"] as $arItem) {
					$arFileTmp = CFile::ResizeImageGet(
						$arItem["PREVIEW_PICTURE"],
						array("width" => 600, 'height' => 525),
						BX_RESIZE_IMAGE_EXACT,
						false
					);?>
				<div class="tgb tgb--img-hover tgb--project">
					<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						
						<div class="tgb__image img-to-bg">
							<img data-lazy-src="<?=$arFileTmp["src"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
						</div>
						
						<div class="tgb__overlay">
							<div class="tgb__overlay-part">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__title h2"><?=$arItem["NAME"]?></div>
									<div class="pparams">
										<div class="pparam">
											<div class="pparam__title">Дата окончания работ</div>
											<div class="pparam__value"><?=$arItem["DISPLAY_ACTIVE_TO"]?> год</div>
										</div><?
										if($arItem["PROPERTIES"]["TOTAL_AREA"]["VALUE"]) {?>
											<div class="pparam">
											<div class="pparam__title">Общая площадь</div>
											<div class="pparam__value"><?=$arItem["PROPERTIES"]["TOTAL_AREA"]["VALUE"]?> га</div>
											</div><?
										}?>
									</div>
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
		</div>
		<?=$arResult["NAV_STRING"]?><?
		}?>
	</div>
</div>