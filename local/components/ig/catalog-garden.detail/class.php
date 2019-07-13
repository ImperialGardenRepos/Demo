<?php

class CatalogItem extends \CBitrixComponent {
	public function getImagesBlock() {
		$strResult = '';
		
		
		$arImages = \ig\CHelper::getImagesArray(array(), $this -> arResult, array(
			"RESIZE" => array("WIDTH" => 390, "HEIGHT" => 390)
		));
		
		if(!empty($arImages)) {
			
			$intCnt = 0;
			foreach($arImages as $intImageID => $arImageData) {
				$strResult .= '<img'.($intCnt == 0 ? ' class="active"' : '').' data-lazy-src="'.\ig\CUtil::prepareImageUrl($arImageData["RESIZE"]["src"]).'" src="'.SITE_TEMPLATE_PATH.'/img/blank.png" data-src-full="'.\ig\CUtil::prepareImageUrl($arImageData["SRC"]).'" data-ihs-content="'.$arImageData["TITLE"].'" alt="'.$arImageData["TITLE"].'">';
				
				$intCnt++;
			}
			
			if(!empty($strResult)) {
				$strResult = '
			<div class="fcard__image">
				<div class="image-slider js-images-hover-slider js-images-popup-slider cursor-pointer">
					'.$strResult.'
					<div class="image-slider__fullscreen js-images-popup-slider-open">
						<svg class="icon">
							<use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-fullscreen"></use>
						</svg>
					</div>
				</div>
			</div>';
			}
		}
		
		return $strResult;
	}
}