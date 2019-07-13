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

$obBasket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(),
	\Bitrix\Main\Context::getCurrent()->getSite());
$floatBasketPrice = $obBasket -> getPrice();

$obOrder = \Bitrix\Sale\Order::create(
		\Bitrix\Main\Context::getCurrent()->getSite(),
		\Bitrix\Sale\Fuser::getId()
);
$obOrder->setBasket($obBasket);
$obOrder->getDiscount()->calculate();

$floatBasketDiscountPrice = $obOrder->getPrice();

// скидки по общей сумме
$arDiscounts = array();
$rsDiscounts = $arDeliveryDiscount = \Bitrix\Sale\Internals\DiscountTable::getList(array(
	"filter" => array("XML_ID" => "DISCOUNT_%")
));
while($arDiscount = $rsDiscounts -> Fetch()) {
	$arDiscounts[intval($arDiscount["CONDITIONS_LIST"]["CHILDREN"][0]["DATA"]["Value"])] = $arDiscount;
}
ksort($arDiscounts);

// скидка на доставку
$arDeliveryDiscount = \Bitrix\Sale\Internals\DiscountTable::getList(array(
	"filter" => array("=XML_ID" => "FREE_DELIVERY")
)) -> fetch();
$floatFreeDeliveryLimit = $arDeliveryDiscount["CONDITIONS_LIST"]["CHILDREN"][0]["DATA"]["Value"];
?>
	<div class="fbar  fbar--mobile-static  js-fbar-sticky-wrapper" data-url="/local/ajax/system.php?igs-action=getOrderFloatBar&mobile_static" data-method="get">
	<div class="fbar__inner compensate-for-scrollbar  js-fbar-sticky">
		<div class="container">
			<div class="ach-items expand-it-wrapper">
				<div class="ach-title mobile-show">Спец. предложения</div>
				<div class="ach-toggler-overlay cursor-pointer js-toggle-element expand-it" data-toggle-class="expanded" data-toggle-element-class="expanded" data-toggle-selector=".ach-items--collapsible"></div>
				<div class="ach-toggler cursor-pointer js-toggle-element expand-it" data-toggle-class="expanded" data-toggle-element-class="expanded" data-toggle-selector=".ach-items--collapsible">
					<div class="ach-toggler__icon">
						<svg class="icon">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-up"></use>
						</svg>
					</div>
				</div>
				<div class="ach-items__content expand-it-container">
					<div class="ach-items__grid expand-it-inner">
						<div style="visibility: hidden !important;" class="ach-item">
							<div class="ach-item__icon">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-gift"></use>
								</svg>
							</div>
							<div class="ach-item__content">
								<div class="ach-item__title">
									Подарок
								</div>
								<div class="ach-item__summary">
									Можно выбрать, отложив в&nbsp;корзину товары на&nbsp;сумму:&nbsp;100&nbsp;000&nbsp;₽
								</div>
							</div>
						</div><?
						
						$floaDeliveryDelta = $floatFreeDeliveryLimit - $floatBasketPrice;
						if($floaDeliveryDelta<=0) {
							$bFreeDelivery = true;
							$strDeliveryTitle = 'Доставим бесплатно!';
							$strDeliveryText = 'Вы выполнили условия получения услуги бесплатной доставки';
						} else {
							$bFreeDelivery = false;
							$strDeliveryTitle = 'Доставка';
							$strDeliveryText = 'Будет бесплатной, когда в&nbsp;корзине товаров на&nbsp;сумму:&nbsp;'.\ig\CFormat::getFormattedPrice($floaDeliveryDelta, "RUB", array("RUB_SIGN" => '₽'));
						}
						
						 ?>
						<div class="ach-item" style="visibility: hidden !important;"><?
							if(false) {?>
							<div class="ach-item__icon<?=($bFreeDelivery?' color-green':'')?>">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-airplane"></use>
								</svg>
							</div>
							<div class="ach-item__content">
								<div class="ach-item__title">
									<?=$strDeliveryTitle?>
								</div>
								<div class="ach-item__summary"><?=$strDeliveryText?></div>
							</div><?
							}?>
						</div><?
						
						$arCurrentDiscount = array();
						$arNextDiscount = array();
						foreach($arDiscounts as $intSumm => $arD) {
							if($floatBasketPrice>=$intSumm) {
								$arCurrentDiscount = $arD;
							} else {
								if(empty($arNextDiscount)) {
									$arNextDiscount = $arD;
								}
							}
						}
						
						$strDiscountTitle = '';
						if(empty($arCurrentDiscount)) { // еще нет скидки
							$intNextDiscountSumm = $arNextDiscount["CONDITIONS_LIST"]["CHILDREN"][0]["DATA"]["Value"];
							
							$strDiscountTitle = 'Скидка на заказ';
							$strDiscountText = 'Применится, когда в&nbsp;корзине будет товаров на&nbsp;сумму:&nbsp;'.\ig\CFormat::getFormattedPrice($intNextDiscountSumm, "RUB", array("RUB_SIGN" => '₽'));
						} elseif(!empty($arCurrentDiscount) && empty($arNextDiscount)) {
							$intCurrentDiscount = $arCurrentDiscount["SHORT_DESCRIPTION_STRUCTURE"]["VALUE"];
							
							$strDiscountTitle = 'Скидка на заказ '.$intCurrentDiscount.'%';
							$strDiscountText = 'Вы получили максимально возможную скидку';
						} elseif(!empty($arCurrentDiscount) && !empty($arNextDiscount)) {
							$intCurrentDiscount = $arCurrentDiscount["SHORT_DESCRIPTION_STRUCTURE"]["VALUE"];
							$intNextDiscount = $arNextDiscount["SHORT_DESCRIPTION_STRUCTURE"]["VALUE"];
							$intNextDiscountSumm = $arNextDiscount["CONDITIONS_LIST"]["CHILDREN"][0]["DATA"]["Value"];
							
							$strDiscountTitle = 'Скидка на заказ '.$intCurrentDiscount.'%';
							$strDiscountText = 'Получите '.$intNextDiscount.'%, отложив в корзину товары на сумму:&nbsp;'.\ig\CFormat::getFormattedPrice($intNextDiscountSumm, "RUB", array("RUB_SIGN" => '₽'));
						}
						
						if(false) {
						?>
						<div<?=(empty($strDiscountTitle)?' style="visibility: hidden !important;"':'')?> class="ach-item">
							<div class="ach-item__icon<?=($intCurrentDiscount>0?' color-green':'')?>">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent-circle"></use>
								</svg>
							</div>
							<div class="ach-item__content">
								<div class="ach-item__title"><?=$strDiscountTitle?></div>
								<div class="ach-item__summary"><?=$strDiscountText?></div>
							</div>
						</div><?
						}?>
						
						<div class="ach-item">
							<div class="ach-item__icon<?=(count($arResult["BASKET_ITEMS"])>0?' color-green':'')?>">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
								</svg>
							</div>
							<div class="ach-item__content"><?
								if($floatBasketDiscountPrice>0) {?>
								<div class="ach-item__title">
									Товаров в&nbsp;корзине: <?=count($arResult["BASKET_ITEMS"])?>
								</div>
								<div class="ach-item__summary">
									На&nbsp;сумму: <?=\ig\CFormat::getFormattedPrice($floatBasketDiscountPrice, "RUB", array("RUB_SIGN" => '₽'))?>
									<br><a href="#form-cart-footer" class="link link--dotted js-goto js-goto-noactivate">Отправить заказ</a>
								</div><?
								} else {
									echo '<div class="ach-item__title">Корзина пуста</div><div class="ach-item__summary">Добавьте товары в корзину</div>';
								}?>
							</div>
						</div>
					</div>
				</div>
				<div class="ach-sitems">
					<div class="ach-sitems__grid"><?
						if(false) {?>
						<div class="ach-sitem">
							<div class="ach-sitem__icon">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-gift"></use>
								</svg>
							</div>
						</div><?
						}?>
						<div class="ach-sitem">
							<div class="ach-sitem__icon<?=($bFreeDelivery?' color-green':'')?>">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-airplane"></use>
								</svg>
							</div>
						</div><?
						if(!empty($strDiscountTitle)) { ?>
							<div class="ach-sitem">
							<div class="ach-sitem__icon<?=($intCurrentDiscount?' color-green':'')?>">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-percent-circle"></use>
								</svg>
							</div>
							</div><?
						}?>
						<div class="ach-sitem">
							<div class="ach-sitem__icon<?=($floatBasketDiscountPrice>0?' color-green':'')?>">
								<svg class="icon">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
								</svg>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		
		</div>
	</div>
</div>