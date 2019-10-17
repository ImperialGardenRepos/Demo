<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Заказ, оплата, доставка - садовый центр Imperial Garden");
$APPLICATION->SetTitle("Заказ, оплата, доставка");
?><div class="section section--grey section--article section--fullheight section--saw-before text">
	<div class="container">
		<h1><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/dostavka/title.php"
				)
			);?></h1>
		<nav class="tabs tabs--flex" data-goto-hash-change="true">
			<div class="tabs__inner js-tabs-fixed-center">
				<div class="tabs__scroll js-tabs-fixed-center-scroll">
					<div class="tabs__scroll-inner">
						<ul class="tabs__list">
							<li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#how-to-order">
									<span class="link link--ib link--dotted">Как сделать заказ</span>
								</a></li>
							<li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#how-to-pay">
									<span class="link link--ib link--dotted">Как оплатить покупку</span>
								</a></li>
							<li class="tabs__item"><a class="tabs__link tabs__link--block js-goto" href="#delivery">
									<span class="link link--ib link--dotted">Доставка</span>
								</a></li>
						</ul>
					</div>
				</div>
			</div>
		</nav>
		
		<div id="how-to-order" data-goto-offset-element=".header"></div>
		
		<h2>Как сделать заказ</h2>
		
		<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/local/inc_area/dostavka/zakaz-text.php"
				)
			);?></p>
		
		<div class="stgbs">
			<div class="stgbs__inner">
				
				<div class="stgb">
					<div class="stgb__inner">
						<div class="stgb__icon color-active">
							<svg class="icon icon--monitor">
								<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-monitor"></use>
							</svg>
						</div>
						<div class="stgb__content">
							<div class="stgb__title">На сайте</div>
							<div class="stgb__summary text mw320 margin-auto">
								<p><?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "inc",
											"EDIT_TEMPLATE" => "",
											"PATH" => "/local/inc_area/dostavka/zakaz-text-site.php"
										)
									);?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="stgb">
					<div class="stgb__inner">
						<div class="stgb__icon color-active">
							<svg class="icon icon--phone-call">
								<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-phone-call"></use>
							</svg>
						</div>
						<div class="stgb__content">
							<div class="stgb__title">По телефону</div>
							<div class="stgb__summary text mw320 margin-auto">
								<p><?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "inc",
											"EDIT_TEMPLATE" => "",
											"PATH" => "/local/inc_area/dostavka/zakaz-text-phone.php"
										)
									);?></p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="stgb">
					<div class="stgb__inner">
						<div class="stgb__icon color-active">
							<svg class="icon icon--mail-opened">
								<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-mail-opened"></use>
							</svg>
						</div>
						<div class="stgb__content">
							<div class="stgb__title">По эл. почте</div>
							<div class="stgb__summary text mw320 margin-auto">
								<p><?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "inc",
											"EDIT_TEMPLATE" => "",
											"PATH" => "/local/inc_area/dostavka/zakaz-text-email.php"
										)
									);?></p>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		</div>
	
	
	</div>
</div>
	
	
	<div class="section section--block section--saw-before text" id="how-to-pay" data-goto-offset-element=".header">
		<div class="container">
			
			<h2>Как оплатить покупки</h2>
			
			<p><?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/inc_area/dostavka/zakaz-payment.php"
					)
				);?></p>
			
			<div class="stgbs">
				<div class="stgbs__inner">
					
					<div class="stgb">
						<div class="stgb__inner">
							<div class="stgb__icon color-active">
								<svg class="icon icon--cash">
									<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-cash"></use>
								</svg>
							</div>
							<div class="stgb__content">
								<div class="stgb__title">Оплата наличными</div>
								<div class="stgb__summary text mw320 margin-auto">
									<p><?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "inc",
												"EDIT_TEMPLATE" => "",
												"PATH" => "/local/inc_area/dostavka/zakaz-payment-cash.php"
											)
										);?></p>
								</div>
							</div>
						</div>
					</div>
					
					<div class="stgb">
						<div class="stgb__inner">
							<div class="stgb__icon color-active">
								<svg class="icon">
									<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-card-terminal"></use>
								</svg>
							</div>
							<div class="stgb__content">
								<div class="stgb__title">Оплата картой</div>
								<div class="stgb__summary text mw320 margin-auto">
									<p><?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "inc",
												"EDIT_TEMPLATE" => "",
												"PATH" => "/local/inc_area/dostavka/zakaz-payment-card.php"
											)
										);?></p>
								</div>
							</div>
						</div>
					</div>
					
					<div class="stgb">
						<div class="stgb__inner">
							<div class="stgb__icon color-active">
								<svg class="icon">
									<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-bill"></use>
								</svg>
							</div>
							<div class="stgb__content">
								<div class="stgb__title">Безналичный расчет</div>
								<div class="stgb__summary text mw320 margin-auto">
									<p><?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "inc",
												"EDIT_TEMPLATE" => "",
												"PATH" => "/local/inc_area/dostavka/zakaz-payment-invoice.php"
											)
										);?></p>
								</div>
							</div>
						</div>
					</div>
				
				</div>
			</div>
		
		
		</div>
		<div class="borders-saw"></div>
		<div class="borders-saw borders-saw--bottom"></div>
	</div>
	
	
	<div class="section section--block section--grey section--saw-before text" id="delivery" data-goto-offset-element=".header">
		<div class="container">
			
			<h2>Доставка</h2>
			
			<p><?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/inc_area/dostavka/zakaz-delivery.php"
					)
				);?></p>
			
			<div class="stgbs">
				<div class="stgbs__inner">
					
					<div class="stgb stgb--1-2">
						<div class="stgb__inner">
							<div class="stgb__icon color-active">
								<svg class="icon icon--cart-boxes">
									<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-cart-boxes"></use>
								</svg>
							</div>
							<div class="stgb__content">
								<div class="stgb__title">Самовывоз</div>
								<div class="stgb__summary text mw320 margin-auto">
									<p><?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "inc",
												"EDIT_TEMPLATE" => "",
												"PATH" => "/local/inc_area/dostavka/zakaz-delivery-pickup.php"
											)
										);?> </p>
								</div>
							</div>
						</div>
					</div>
					
					<div class="stgb stgb--1-2">
						<div class="stgb__inner">
							<div class="stgb__icon color-active">
								<svg class="icon icon--truck">
									<use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-truck"></use>
								</svg>
							</div>
							<div class="stgb__content">
								<div class="stgb__title">Курьерская служба</div>
								<div class="stgb__summary text mw320 margin-auto">
									<p><?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "inc",
												"EDIT_TEMPLATE" => "",
												"PATH" => "/local/inc_area/dostavka/zakaz-delivery-courier.php"
											)
										);?></p>
								</div>
							</div>
						</div>
					</div>
				
				</div>
			</div>
		
		</div>
		<div class="borders-saw"></div>
		<div class="borders-saw borders-saw--bottom"></div>
	</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>