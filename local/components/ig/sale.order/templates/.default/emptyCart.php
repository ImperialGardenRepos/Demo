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

if($arParams["IS_AJAX"]) { ?>
	<script>
		document.location.reload();
	</script><?
} else {
?><br><br><br>
<div class="bx-sbb-empty-cart-container">
	<div class="bx-sbb-empty-cart-image">
		<img src="" alt="">
	</div>
	<div class="bx-sbb-empty-cart-text">Ваша корзина пуста</div>
	<div class="bx-sbb-empty-cart-desc">
		<a href="/">Нажмите здесь</a>, чтобы продолжить покупки</div>
</div><?
}?>