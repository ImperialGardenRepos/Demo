<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
use Bitrix\Sale,
	Bitrix\Main\Loader,
	Bitrix\Main\Page\Asset;

Loader::includeModule("sale");

$isVirtualTourPage = strpos($APPLICATION->GetCurDir(), '/o-nas/virtualnyy-tur/')===0;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arParams["IS_AJAX"] = ($request->isAjaxRequest());

$strPhone = \Bitrix\Main\Config\Option::get("grain.customsettings","phone");

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
?>
	<!DOCTYPE html>
	<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ru"> <![endif]-->
	<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="ru"> <![endif]-->
	<!--[if IE 8]>    <html class="no-js lt-ie9" lang="ru"> <![endif]-->
	<!--[if IE 9]>    <html class="no-js lte-ie9" lang="ru"> <![endif]-->
	<!--[if gt IE 9]><!--> <html class="no-js" lang="ru"> <!--<![endif]-->
	<head>
		<title><?$APPLICATION->ShowTitle()?></title>
		<? $APPLICATION->ShowMeta("keywords") ?>
		<? $APPLICATION->ShowMeta("description") ?>
		<? $APPLICATION->ShowCSS(); ?>
		<? $APPLICATION->ShowHeadStrings() ?>
		<? $APPLICATION->ShowHeadScripts() ?>
		
		<meta charset="<?=LANG_CHARSET;?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<meta name="format-detection" content="telephone=no">
		<?=\ig\CSeo::includeCss()?>
		<script data-skip-moving="true" src="<?=SITE_TEMPLATE_PATH?>/build/build-head.js"></script>
		
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script data-skip-moving="true" async src="https://www.googletagmanager.com/gtag/js?id=UA-140331330-1"></script>
		<script data-skip-moving="true">
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', 'UA-140331330-1');
		</script>
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
            
            ym(24650237, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true,
                webvisor:true,
                trackHash:true
            });
		</script>
		<!-- /Yandex.Metrika counter -->
	</head>
<body class="not-loaded " data-svg-sprite-url="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg" data-cart-url="/local/ajax/cart.php" data-favorites-url="/local/ajax/favorite.php">
<!-- Yandex.Metrika counter -->
<noscript><div><img src="https://mc.yandex.ru/watch/24650237" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<div id="panel"><?$APPLICATION->ShowPanel()?></div>
<div class="respon-meter"></div>

<div class="wrap<?=($isVirtualTourPage?' js-fix-100vh-min':'')?>" id="top"><?
	if($APPLICATION->GetProperty("hideTopBar") !== 'Y') {?>
	<div class="topbar mobile-hide">
		<div class="container">
			
			<div class="cols-wrapper cols-wrapper--topbar">
				<div class="cols cols--auto">
					
					<div class="col">
						<svg class="icon color-active">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-clock"></use>
						</svg>
						<span class="font-bold"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/worktime.php"
								)
							);?></span>
					</div>
					
					<div class="col">
						<svg class="icon color-active">
							<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-marker"></use>
						</svg>
						<span class="font-bold"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/address.php"
								)
							);?></span>
						<a href="/o-nas/kontakty/" class="link-after-text link--ib link--bordered">Как проехать</a>
					</div>
					
					<div class="col">
						
						<a class="hphone" href="tel:<?=str_replace(' ', '', $strPhone)?>">
							<svg class="icon color-active">
								<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-phone"></use>
							</svg>
							<span><?=$strPhone?></span>
						</a>
					
					</div>
				
				</div>
			</div>
		
		</div>
	</div><?
	}?>
	<div class="header-replace"></div>
	<header class="header">
		<div class="header__inner compensate-for-scrollbar">
			<div class="container">
				<div class="header__grid-wrapper">
					<div class="header__grid">
						<div class="header__cell header__cell--cbutton mobile-show-table-cell">
							<div class="btns btns--h">
								<a class="btn btn--icon btn--hicon js-menu-switcher" href="#">
									<div class="cbutton">
										<div class="cbutton__inner">
											<div class="cbutton__item"></div>
											<div class="cbutton__item"></div>
											<div class="cbutton__item"></div>
										</div>
									</div>
								</a>
								<a class="btn btn--icon btn--hicon js-toggle-popover" href="#" data-selector="#popover-contacts">
									<svg class="icon icon--marker">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-marker"></use>
									</svg>
								</a>
								<a class="btn btn--icon btn--hicon" href="tel:<?=str_replace(' ', '', $strPhone)?>">
									<svg class="icon icon--phone">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-phone"></use>
									</svg>
								</a>
							</div>
						</div>
						<div class="header__cell header__cell--logo">
							<a class="logo" href="/">
								<svg class="icon icon--logo">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-logo"></use>
								</svg>
								<svg class="icon icon--logo-short">
									<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-logo-short"></use>
								</svg>
							</a>
						</div>
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"horizontal_multilevel",
							Array(
								"ROOT_MENU_TYPE" => "top",
								"MAX_LEVEL" => "2",
								"CHILD_MENU_TYPE" => "subtop",
								"USE_EXT" => "Y",
								"MENU_CACHE_TYPE" => "A",
								"ALLOW_MULTI_SELECT" => "Y",
								"MENU_CACHE_TIME" => "360000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => Array()
							)
						);?>
						
						<div class="header__cell header__cell--icons">
							<div class="btns btns--h"><?
								if(false) {?>
								<a href="/personal/" class="btn btn--icon btn--hicon">
									<svg class="icon icon--user">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-user"></use>
									</svg>
								</a><?
								}?>
								<a class="btn btn--icon btn--hicon js-favorites-header js-toggle-popover" data-selector="#popover-favorites" href="#">
									<div class="btn-sticker js-favorites-value"><?=\ig\CFavorite::getInstance() -> getFavoriteCnt()?></div>
									<svg class="icon icon--heart">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-heart-outline"></use>
									</svg>
								</a>
								<a class="btn btn--icon btn--hicon js-cart-header js-toggle-popover" data-selector="#popover-cart" href="/personal/order/make/">
									<div class="btn-sticker js-cart-value"><?=count($basket->getQuantityList())?></div>
									<svg class="icon icon--cart">
										<use xlink:href="<?=SITE_TEMPLATE_PATH?>/build/svg/symbol/svg/sprite.symbol.svg#icon-cart"></use>
									</svg>
								</a>
							</div>
							<div class="header__popover js-favorites-popover js-fix-100vh js-fix-100vh-max" id="popover-favorites" data-popover-url="/local/ajax/favorite.php"></div>
							<div class="header__popover js-cart-popover js-fix-100vh js-fix-100vh-max" id="popover-cart" data-popover-url="/local/ajax/cart.php"></div>
							<div class="header__popover js-fix-100vh js-fix-100vh-max" id="popover-contacts" data-popover-url="/local/ajax/system.php?igs-action=getContacts"></div>
						</div>
				</div>
			</div>
		</div>
	</header>
	<div class="menu-overlay">
		<div class="menu-overlay__content">
			<div class="js-hmenu-mobile-place"></div>
		</div>
	</div>
	<div class="menu-overlay-bg"></div>
	<? $APPLICATION->ShowViewContent('before_breadcrumb'); ?>
	<?
	if(!$arParams["IS_AJAX"]) {
		$intNavStartFrom = intval($APPLICATION->GetProperty('intNavStartFrom'));
		
		$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", Array(
				"START_FROM" => $intNavStartFrom,
				"PATH"       => "",
				"SITE_ID"    => ""
			));
	}?>
	