<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arResult["BANNERS_PROPERTIES"])) {
	foreach($arResult["BANNERS_PROPERTIES"] as $arI) {
		if($arI["IMAGE_ID"]>0) {?>
	<div class="tgbs tgbs--service">
		<div class="tgbs__inner">
			<div class="tgb tgb--service tgb--service-red tgb--service-long tgb--service-mview tgb--service-no-number">
				<a class="tgb__inner" href="<?=$arI["URL"]?>">
					<div class="tgb__overlay">
						<div class="tgb__title h4"><?=$arI["NAME"]?></div>
						<div class="tgb__summary">
							<p><?=(empty($arI["CODE"])?$arI["IMAGE_ALT"]:$arI["CODE"])?></p>
						</div>
					</div>
					<div class="tgb__image img-to-bg" style="background-position-x: right;">
						<img data-lazy-src="<?=CFile::GetPath($arI["IMAGE_ID"])?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
					</div>
				</a>
			</div>
		</div>
	</div><?
		} else {
			echo $arI["CODE"];
		}
	}
}