<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
if(false) {
	?>
	<div class="pager">
		<a href="#" class="pager__item pager__item--arrow">
			<svg class="icon">
				<use xlink:href="build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
			</svg>
		</a> <a href="#" class="pager__item">1</a> <a href="#" class="pager__item active">2</a>
		<a href="#" class="pager__item">3</a> <a href="#" class="pager__item mobile-hide">4</a>
		<a href="#" class="pager__item mobile-hide">5</a> <span class="pager__item mobile-hide">...</span>
		<a href="#" class="pager__item mobile-hide">34</a> <a href="#" class="pager__item pager__item--arrow">
			<svg class="icon">
				<use xlink:href="build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-right"></use>
			</svg>
		</a>
	</div>
	<?
}

?>
<div class="pager">
<?

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
if($arResult["bDescPageNumbering"] === true) {
	$bFirst = true;
	if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
		if($arResult["bSavePage"]):
?>
			
			<a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
<?
		else:
			if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):
?>
			<a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>
<?
			else:
?>
			<a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
<?
			endif;
		endif;
		
		if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
			$bFirst = false;
			if($arResult["bSavePage"]):
?>
			<a class="modern-page-first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">1</a>
<?
			else:
?>
			<a class="modern-page-first" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
<?
			endif;
			if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
/*?>
			<span class="modern-page-dots">...</span>
<?*/
?>	
			<a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=intVal($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2)?>">...</a>
<?
			endif;
		endif;
	endif;
	do
	{
		$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
		
		if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
?>
		<span class="<?=($bFirst ? "modern-page-first " : "")?>modern-page-current"><?=$NavRecordGroupPrint?></span>
<?
		elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
?>
		<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$NavRecordGroupPrint?></a>
<?
		else:
?>
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
			?> class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$NavRecordGroupPrint?></a>
<?
		endif;
		
		$arResult["nStartPage"]--;
		$bFirst = false;
	} while($arResult["nStartPage"] >= $arResult["nEndPage"]);
	
	if ($arResult["NavPageNomer"] > 1):
		if ($arResult["nEndPage"] > 1):
			if ($arResult["nEndPage"] > 2):
/*?>
		<span class="modern-page-dots">...</span>
<?*/
?>
		<a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] / 2)?>">...</a>
<?
			endif;
?>
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a>
<?
		endif;
	
?>
		<a class="modern-page-next"href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_next")?></a>
<?
	endif; 

} else {
	$bFirst = true;
	
	if($arResult["NavPageNomer"]>1):
		if($arResult["bSavePage"]):
			?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] - 1)?>" class="pager__item pager__item--arrow">
				<svg class="icon">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
				</svg>
			</a><?
		else:
			if($arResult["NavPageNomer"]>2):
				?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] - 1)?>" class="pager__item pager__item--arrow">
					<svg class="icon">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
					</svg>
				</a><?
			else:
				?>
				<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="pager__item pager__item--arrow">
					<svg class="icon">
						<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-left"></use>
					</svg>
				</a><?
			endif;
		
		endif;
		
		if($arResult["nStartPage"]>1):
			$bFirst = false;
			if($arResult["bSavePage"]):
				?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" class="pager__item">1</a>
			<?
			else:
				?>
				<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="pager__item">1</a>
			<?
			endif;
			if($arResult["nStartPage"]>2):
				?>
				<span class="pager__item mobile-hide">...</span>
			<?
			endif;
		endif;
	endif;
	
	do {
		if($arResult["nStartPage"] == $arResult["NavPageNomer"]):
			?>
			<a href="#" onclick="return false;" class="pager__item active"><?=$arResult["nStartPage"]?></a>
		<?
		elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
			?>
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="pager__item"><?=$arResult["nStartPage"]?></a>
		<?
		else:
			?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="pager__item"><?=$arResult["nStartPage"]?></a>
		<?
		endif;
		$arResult["nStartPage"]++;
		$bFirst = false;
	} while ($arResult["nStartPage"]<=$arResult["nEndPage"]);
	
	if($arResult["NavPageNomer"]<$arResult["NavPageCount"]):
		if($arResult["nEndPage"]<$arResult["NavPageCount"]):
			if($arResult["nEndPage"]<($arResult["NavPageCount"] - 1)): ?>
				<span class="pager__item">...</span><?
			endif;
			?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>" class="pager__item"><?=$arResult["NavPageCount"]?></a>
		<?
		endif;
		?>
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] + 1)?>" class="pager__item pager__item--arrow">
			<svg class="icon">
				<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-arrow-right"></use>
			</svg>
		</a>
	<?
	endif;
} ?>
</div>