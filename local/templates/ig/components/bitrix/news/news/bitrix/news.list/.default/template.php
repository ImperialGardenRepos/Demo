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
			<div class="cols cols--auto">
				<div class="col col--heading">
					<h1><?=$arResult["PAGE_TITLE"]?></h1>
				</div><?
				if(!empty($arResult["YEARS"])) {?>
				<div class="col col--stabs">
					<div class="nav stabs">
						<div class="stabs__list"><?
							$bHasSelectedSection = false;
							foreach($arResult["YEARS"] as $arYear) {
								if($arYear["SELECTED"]) {
									$bHasSelectedSection = true;
								}?>
							<div class="stabs__item<?=($arYear["SELECTED"]?' active':'')?>">
								<a class="stabs__link" href="<?=$arYear["SECTION_PAGE_URL"]?>">
									<?=$arYear["NAME"]?>
								</a>
							</div><?
							}?>
							<div class="stabs__item<?=($bHasSelectedSection?'':' active')?>">
								<a class="stabs__link" href="<?=$arResult["LIST_PAGE_URL"]?>">
									Все
								</a>
							</div>
						</div>
					</div>
				
				</div><?
				}?>
			</div>
		</div><?
		if(!empty($arResult["MONTH"])) {?>
		<nav class="tabs tabs--flex tabs--active-allowed">
			<div class="tabs__inner js-tabs-fixed-center">
				<div class="tabs__scroll js-tabs-fixed-center-scroll">
					<div class="tabs__scroll-inner">
						<ul class="tabs__list"><?
							foreach($arResult["MONTH"] as $arMonth) {?>
							<li class="tabs__item<?=($arMonth["SELECTED"]?' active':'')?>"><a class="tabs__link tabs__link--block" href="<?=$arMonth["SECTION_PAGE_URL"]?>">
									<span class="link link--ib link--dotted"><?=$arMonth["NAME"]?></span>
								</a>
							</li><?
							}?>
						</ul>
					</div>
				</div>
			</div>
		</nav><?
		}
		
		if(!empty($arResult["ITEMS"])) {?>
		<div class="tgbs tgbs--mm">
			<div class="tgbs__inner" id="container-<?=$arParams["PAGER_TITLE"]?>"><?
				foreach($arResult["ITEMS"] as $arItem) {?>
				<div class="tgb tgb--1-2 tgb--h50 tgb--img-hover">
					<a class="tgb__inner" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						<div class="tgb__image img-to-bg">
							<img data-lazy-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
						</div>
						<div class="tgb__overlay">
							<div class="tgb__overlay-part">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
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
		}?>
		<?=$arResult["NAV_STRING"]?>
	</div>
</div><?
if(false) {?>

<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						border="0"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a>
			<?else:?>
				<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					style="float:left"
					/>
			<?endif;?>
		<?endif?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
			<?else:?>
				<b><?echo $arItem["NAME"]?></b><br />
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?endif?>
		<?foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;?>
	</p>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div><?
}?>