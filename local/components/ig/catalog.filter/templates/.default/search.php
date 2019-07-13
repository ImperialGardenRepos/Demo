<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(empty($arResult["SEARCH"]["DATA"])) { ?>
	<div class="ddbox__dropdown-container">
		<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
			<p>Ничего не найдено. Попробуйте расширить запрос.</p>
		</div>
		<div class="ddbox__dropdown-scroll-gradient"></div>
	</div><?
} else {
	?>
	<div class="ddbox__dropdown-container">
		<div class="ddbox__dropdown-inner js-ddbox-scrollbar">
			<div class="checkboxes">
				<div class="checkboxes__inner"><?
					foreach($arResult["SEARCH"]["DATA"] as $intRodID => $arRodData) { ?>
						<label class="plant-checkbox plant-checkbox--level0 checkbox checkbox--block">
							<div class="plant-checkbox__title"><a href="<?=$arRodData["URL"]?>" target="_blank" class="link--bordered"><?=$this -> __component -> formatHighlight($arRodData["NAME"], $arResult["SEARCH"]["QUERY"])?></a></div><?
							if(!empty($arRodData["NAME_LAT"])) { ?>
								<div class="plant-checkbox__subtitle"><?=$this -> __component -> formatHighlight($arRodData["NAME_LAT"], $arResult["SEARCH"]["QUERY"])?></div><?
							}?>
						</label>
						<?
						
						foreach($arRodData["VID"] as $intVidID => $arVidData) {
							$isHybrid = strpos($arVidData["NAME_LAT"], 'hybr')!==false;
							
							$strGroup = '';
							ob_start();
							foreach ($arVidData["SORT"] as $intSortID => $arSortData) {
								$strGroup = $arSortData["GROUP"];
								
								if($arSortData["IS_VIEW"] != 'Y') {
									$strName = $this -> __component -> formatHighlight($arSortData["NAME"], $arResult["SEARCH"]["QUERY"]);
									$strLatName = $this -> __component -> formatHighlight($arSortData["NAME_LAT"], $arResult["SEARCH"]["QUERY"]);
									?>
									<label class="plant-checkbox plant-checkbox--level2 checkbox checkbox--block checkbox-plain-js js-ddbox-input">
									<input type="checkbox" name="F[sortsID][]" value="<?=$intSortID?>" data-ddbox-value="<?=$arSortData["NAME"]?>y">
									<div class="checkbox__icon"></div>
									
									<div class="plant-checkbox__title">
									<a href="<?=$arSortData["URL"]?>" target="_blank" class="link--bordered"><?=($arSortData["IS_RUSSIAN"]=='Y'?$strName:$strLatName)?></a>
									</div><?
									if(!empty($arSortData["NAME_LAT"])) { ?>
										<div class="plant-checkbox__subtitle"><?=($arSortData["IS_RUSSIAN"]=='Y'?$strLatName:$strName)?></div><?
									} ?>
									</label><?
								}
							}
							$strSort = ob_get_contents();
							ob_end_clean();
							
							$strIcon = $arResult["OFFER_PARAMS"]["GROUP"]["VALUES"][$strGroup]["ICON"];
							?>
							<label class="plant-checkbox plant-checkbox--level1 checkbox checkbox--block checkbox-plain-js js-ddbox-input">
								<input type="checkbox" name="F[vidsID][]" value="<?=$intVidID?>" data-ddbox-value="<?=$arVidData["NAME"]?>"<?=($isHybrid?' disabled':'')?>>
								<div class="checkbox__icon"></div>
								
								<div class="plant-checkbox__icon">
									<svg class="icon">
										<use xlink:href="<?=$strIcon?>"></use>
									</svg>
								</div>
								<div class="plant-checkbox__title"><a href="<?=$arVidData["URL"]?>" target="_blank" class="link--bordered"><?=$this -> __component -> formatHighlight($arVidData["NAME"], $arResult["SEARCH"]["QUERY"])?></a></div><?
							if(!empty($arVidData["NAME_LAT"])) { ?>
								<div class="plant-checkbox__subtitle"><?=$this -> __component -> formatHighlight($arVidData["NAME_LAT"], $arResult["SEARCH"]["QUERY"])?></div><?
							}?>
							</label><?
							
							echo $strSort;
						}
					}?>
				</div>
			</div>
		</div>
		<div class="ddbox__dropdown-scroll-gradient"></div>
	</div>
	<div class="ddbox__dropdown-footer text-align-center">Выберите растения для сравнения или перейдите к описанию</div>
	<?
}
