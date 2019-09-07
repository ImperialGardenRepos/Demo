<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if($arResult["NavPageCount"] >$arResult["NavPageNomer"]) { ?>
<div onclick="obHelper.processPagerShowMore($(this)); return false;" class="action-more js-pagination-more" data-url="<?=$arResult["sUrlPath"]?>?<?=$navQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>&ajax=Y&container-id=<?=$arParams["NAV_TITLE"]?>" data-continer-id="<?=$arParams["NAV_TITLE"]?>" id="show-more-<?=$arParams["NAV_TITLE"]?>">
	<button class="btn btn--more btn--fullwidth">
		<svg class="icon icon--eye">
			<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-eye"></use>
		</svg>
		<span class="link link--ib link--dotted">Показать ещё</span>
	</button>
</div><?
}