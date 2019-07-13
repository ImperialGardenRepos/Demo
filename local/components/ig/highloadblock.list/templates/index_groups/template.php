<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

if(!empty($arResult["ITEMS"])) { ?>
	<div class="tgbs tgbs--category">
	<div class="tgbs__inner"><?
		$arResult["ITEMS"] = array_reverse($arResult["ITEMS"]);
		foreach($arResult["ITEMS"] as $arRow) {
			if($arRow["UF_PHOTO"]>0) {
				$arFileTmp = CFile::ResizeImageGet(
					$arRow["UF_PHOTO"],
					array("width" => 185, 'height' => 185),
					BX_RESIZE_IMAGE_EXACT,
					false
				);
				
				$strImage = $arFileTmp["src"];
			} else {
				$strImage = '';
			}
			
			if($arRow["URL"]) {
				$strLink = $arRow["URL"];
			} else {
				$strLink = '/katalog/rasteniya/?frmCatalogFilterSent=Y&F[PROPERTY_GROUP]='.$arRow["UF_XML_ID"];
			}
			?>
			<div class="tgb tgb--img-hover tgb--category">
			<a class="tgb__inner" href="<?=$strLink?>">
				<div class="tgb__image img-to-bg">
					<img data-lazy-src="<?=$strImage?>" src="<?=SITE_TEMPLATE_PATH?>/img/blank.png" alt="">
				</div>
				<div class="tgb__overlay">
					<div class="tgb__title"><?=$arRow["UF_NAME"]?></div>
				</div>
			</a>
			</div><?
		}?>
	</div>
	</div><?
}