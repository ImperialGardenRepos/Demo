<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arResult["BANNERS_PROPERTIES"])) { ?>
	<hr><?
	if($arParams["TITLE"]) { ?>
		<h2 class="text-align-center"><?=$arParams["TITLE"]?></h2><?
	}?>
	<div class="tgbs">
		<div class="tgbs__inner text-align-center"><?
			foreach ($arResult["BANNERS_PROPERTIES"] as $arI) {
				if($arI["IMAGE_ID"]>0) { ?>
				<div class="tgb tgb--img-hover tgb--1-3">
					<a class="tgb__inner" href="<?=$arI["URL"]?>">
						<div class="tgb__image img-to-bg">
							<img data-lazy-src="<?=CFile::GetPath($arI["IMAGE_ID"])?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
						</div>
						<div class="tgb__overlay">
							<div class="tgb__overlay-part">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__title h2"><?=$arI["NAME"]?></div>
								</div>
							</div>
							<div class="tgb__overlay-part mobile-hide">
								<div class="tgb__overlay-part-inner">
									<div class="tgb__summary text">
										<p><?=(empty($arI["CODE"])?$arI["IMAGE_ALT"]:$arI["CODE"])?></p>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div><?
				} else {
					echo $arI["CODE"];
				}
			}?>
		</div>
	</div><?
}
?>