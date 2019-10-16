<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if (!empty($arResult)) {?>
	<div class="footer__col footer__col--faq"><?
		$arTmp = array();
		foreach ($arResult as $arItem) {
			if($arItem["PARAMS"]["NOINDEX"] == 'Y') {
				$arTmp[] = '<noindex><a'.($arItem["PARAMS"]["LINK_PARAMS"]?' '.$arItem["PARAMS"]["LINK_PARAMS"]:'').' href="'.$arItem["LINK"].'" class="link--ib link--bordered">'.$arItem["TEXT"].'</a></noindex>';
			} else {
				$arTmp[] = '<a'.($arItem["PARAMS"]["LINK_PARAMS"]?' '.$arItem["PARAMS"]["LINK_PARAMS"]:'').' href="'.$arItem["LINK"].'" class="link--ib link--bordered">'.$arItem["TEXT"].'</a>';
			}
		}
		
		echo implode('<br>', $arTmp);
		?>
	</div><?
}?>