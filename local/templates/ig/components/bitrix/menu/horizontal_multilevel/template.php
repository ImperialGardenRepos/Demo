<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?

if (!empty($arResult)) {
	$previousLevel = 0;?>
	<div class="header__cell header__cell--hmenu mobile-hide">
		<div class="hmenu" data-place='{"0": ".js-hmenu-mobile-place", "640": ".js-hmenu-place"}'>
			<div class="hmenu__inner"><?
				foreach ($arResult as $arItem) {
					if(!empty($arItem["PARAMS"]["LINK"])) {
						$arItem["LINK"] = $arItem["PARAMS"]["LINK"];
					}
					
					if($previousLevel && $arItem["DEPTH_LEVEL"]<$previousLevel) {
						echo '
									
									</div>
								</div>
							</div>
						</div>';
					}
					
					if($arItem["DEPTH_LEVEL"] == 1) {
						if($arItem["IS_PARENT"]) { ?>
							<div class="hmenu__item <?=($arItem["SELECTED"]?' active':'')?> expand-it-wrapper expand-it-wrapper-collapse-siblings touch-focused">
							<a title="<?=$arItem["TEXT"]?>" class="hmenu__item-inner expand-it-pseudo" href="<?=$arItem["LINK"]?>"> <?=$arItem["TEXT"]?>
								<div class="hmenu__item-icon">
									<svg class="icon icon--arrow-down">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-down"></use>
									</svg>
								</div>
							</a>
							
							<div class="hmenu__submenu expand-it-container">
								<div class="hmenu__submenu-inner expand-it-inner">
									<div class="hmenu__submenu-list"><?
						} else { ?>
							<div class="hmenu__item <?=($arItem["SELECTED"]?' active':'')?>">
								<a title="<?=$arItem["TEXT"]?>" class="hmenu__item-inner" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
							</div><?
						}
					} else { ?>
						<div class="hmenu__submenu-item<?=($arItem["SELECTED"]?' active':'')?>">
							<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
						</div><?
					}
					
					$previousLevel = $arItem["DEPTH_LEVEL"];
				}
	
				if($previousLevel>1) {
					echo '
							</div>
						</div>
					</div>
				</div>';
				}?>
			</div>
		</div>
		<div class="js-hmenu-place"></div>
	</div><?
}?>