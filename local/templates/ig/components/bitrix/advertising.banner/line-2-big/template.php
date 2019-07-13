<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arResult["BANNERS_PROPERTIES"])) { ?>
	<div class="tgbs tgbs--mm">
		<div class="tgbs__inner"><?
			foreach ($arResult["BANNERS_PROPERTIES"] as $arItem) {
				if($arI["IMAGE_ID"]>0) { ?>
				<div class="tgb tgb--1-2 tgb--h50 tgb--img-hover">
				<a class="tgb__inner" href="<?=$arItem["URL"]?>">
					<div class="tgb__image img-to-bg">
						<img data-lazy-src="<?=CFile::GetPath($arItem["IMAGE_ID"])?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="<?=$arItem["NAME"]?>">
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
			} ?>
		</div>
	</div><?
}