<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Партнерам - садовый центр Imperial Garden");
$APPLICATION->SetTitle("Партнерам");
?>
	<div class="section section--grey section--article section--fullheight text">
		<div class="container">
			<div class="cols-wrapper cols-wrapper--h1-stabs">
				<div class="cols">
					<div class="col col--heading">
						<h1>Партнерам</h1>
					</div>
				</div>
			</div>
			<div class="p text"><? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
						"AREA_FILE_SHOW"   => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE"    => "",
						"PATH"             => "/local/inc_area/partneram/partneram_text.php"
					)); ?>
			</div>
			<? $APPLICATION->IncludeComponent("bitrix:advertising.banner", "line-2-big", Array(
					"CACHE_TIME" => "0",
					"CACHE_TYPE" => "N",
					"NOINDEX"    => "N",
					"QUANTITY"   => "10",
					"TYPE"       => "LINE_2"
				)); ?>
		</div>
	</div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>