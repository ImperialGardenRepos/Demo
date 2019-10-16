<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(empty($arResult["SEARCH"]["DATA"]["ITEMS"])) {?>
	<div class="ddbox__dropdown-container">
		<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
			<p>Ничего не найдено. Попробуйте расширить запрос.</p>
		</div>
		<div class="ddbox__dropdown-scroll-gradient"></div>
	</div><?
} else { ?>
	<div class="ddbox__dropdown-container">
		<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
			<div class="checkboxes">
				<div class="checkboxes__inner"><?
					foreach($arResult["SEARCH"]["DATA"]["SECTIONS"] as $arSection) {?>
						<div class="plant-checkbox plant-checkbox--level0 checkbox checkbox--block">
							<div class="plant-checkbox__title">Удобрение</div>
						</div><?
						foreach($arResult["SEARCH"]["DATA"]["ITEMS"][$arSection["ID"]] as $arProduct) {?>
							<div class="plant-checkbox plant-checkbox--noinput plant-checkbox--level1 checkbox checkbox--block">
							<div class="plant-checkbox__title"><a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" target="_blank" class="link--bordered"><?=$arProduct["NAME"]?></a></div>
							</div><?
						}
					}?>
				</div>
			</div>
		
		</div>
		<div class="ddbox__dropdown-scroll-gradient"></div>
	</div><?
}
