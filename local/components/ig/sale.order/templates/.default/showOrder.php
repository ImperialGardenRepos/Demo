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
<div class="section section--grey section--fullheight section--middle section--block text">
	<div class="container">
		
		<div class="section-center text-align-center">
			
			<div class="heading-icon color-active">
				<svg class="icon">
					<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use>
				</svg>
			</div>
			
			<h1 class="h2"><?=$arResult["ORDER"]["USER_NAME"]?>, спасибо! </h1>
			
			<p>Ваш заказ № <?=$arResult["ORDER"]["ID"]?> принят и будет обработан в ближайшее время.</p>
		
		</div>
	
	</div>
</div>
