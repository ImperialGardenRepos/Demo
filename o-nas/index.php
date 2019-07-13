<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О нас");
?>
	<div class="section section--bring-to-front section--hero">
		<div class="section__bg section__bg--w-overlay img-to-bg" style="background-position-x: right;">
			<img data-lazy-src="images/about-hero.jpg" src="/local/templates/ig/img/blank.png" alt="">
		</div>
		<div class="container">
			<div class="hero hero--default">
				<div class="hero__inner">
					<div class="hero__title"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/local/inc_area/onas-title.php"
							)
						);?></div>
					<div class="hero__summary mobile-hide">
						<p><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/onas-text.php"
								)
							);?></p>
					</div>
				</div>
				<nav class="tabs tabs--flex" data-goto-hash-change="true">
					<div class="tabs__inner js-tabs-fixed-center">
						<div class="tabs__scroll js-tabs-fixed-center-scroll">
							<div class="tabs__scroll-inner">
								<ul class="tabs__list">
									<li class="tabs__item">
										<a class="tabs__link tabs__link--block js-goto" href="#mission">
											<span class="link link--ib link--dotted">Миссия</span> </a></li>
									<li class="tabs__item">
										<a class="tabs__link tabs__link--block js-goto" href="#virtual-tour">
											<span class="link link--ib link--dotted">Виртуальный тур</span> </a></li>
									<li class="tabs__item">
										<a class="tabs__link tabs__link--block js-goto" href="#entertainment">
											<span class="link link--ib link--dotted">Досуг</span> </a></li>
									<li class="tabs__item">
										<a class="tabs__link tabs__link--block js-goto" href="#partners">
											<span class="link link--ib link--dotted">Партнеры</span> </a></li>
								</ul>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</div>
	</div>
	<div class="section section--block section--grey section--saw-before" id="mission" data-goto-offset-element=".header">
		<div class="container">
			<div class="p text">
				<div class="cols-wrapper cols-wrapper--mobile-plain">
					<div class="cols">
						<div class="col">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/onas-full-text.php"
								)
							);?>
						</div>
						<div class="col">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/local/inc_area/onas-video.php"
								)
							);?>
						</div>
					</div>
				</div>
			</div>
			
			<h2 class="text-align-center"><?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/inc_area/onas-reasons-title.php"
					)
				);?></h2>
			<div class="lnumbereds">
				<div class="lnumbereds__inner"><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/inc_area/onas-reasons.php"
						)
					);?>
				</div>
			</div>
		
		</div>
	</div>
	<div class="section section--block section--saw-before" id="virtual-tour" data-goto-offset-element=".header">
		<div class="container">
			
			<h2 class="h1 text-align-center"><?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/local/inc_area/onas-virttour-title.php"
					)
				);?></h2>
			
			<div class="p text text-align-center mw940 margin-auto">
				
				<p><?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/local/inc_area/onas-virttour-text.php"
						)
					);?></p>
			
			</div>
			
			<p>
				<a href="/o-nas/virtualnyy-tur/"><img class="mobile-hide" src="/local/templates/ig/pic/virtualnyy-tur_DT_1200x530.jpg" alt=""><img class="fullwidth mobile-show" src="/local/templates/ig/pic/virtualnyy-tur_MB_340x340.jpg" alt=""></a>
			</p>
		
		</div>
		<div class="borders-saw"></div>
		<div class="borders-saw borders-saw--bottom"></div>
	</div>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"index_dosug",
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "20",
		"IBLOCK_TYPE" => "onas",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "100",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "INDEX_TEXT",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "index_services"
	),
	false
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"partners_index",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("",""),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "24",
		"IBLOCK_TYPE" => "onas",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "8",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "23",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("LINK",""),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>