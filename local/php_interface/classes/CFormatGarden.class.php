<?
namespace ig;

class CFormatGarden extends CFormat {
	public static function getParamsBlockHtml($arProduct, $arOffer) {
		$strResult = '
		<div class="icard__offer-params active">
			<div class="ptgbs ptgbs--params">';
		
		$arSortProp = $arSort["PROPERTIES"];
		$arOfferProp = $arOffer["PROPERTIES"];
		
		if(!empty($arOfferProp["SIZE"]["VALUE"])) {
			$strResult .= '
				<div class="ptgb ptgb--param-volume">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Объем</div>
							<div class="ptgb__title">'.$arOfferProp["SIZE"]["VALUE"].' '.$arOfferProp["UNIT"]["VALUE"].'</div>
						</div>
					</div>
				</div>';
		}
		
		$strResult .= '
				<div class="ptgb mobile-show">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">
								Наличие
							</div>
							<div class="ptgb__title">
								<div class="tags">
									
									<div class="tag tag--circled'.(\ig\CHelper::isAvailable($arOffer["PROPERTIES"]["AVAILABLE"]["VALUE"]) ? ' color-green' : '').'">
										'.$arOffer["PROPERTIES"]["AVAILABLE"]["VALUE"].'
									</div>
								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
		
		return $strResult;
	}
	
	public function getFullParamsBlockHtml($arSort, $arOffer)
	{
		$arSortProp = $arSort["PROPERTIES"];
		$arOfferProp = $arOffer["PROPERTIES"];
		
		if(is_object($this)) {
			$intProductID = $this->arParams["PRODUCT_ID"];
		} else {
			$intProductID = 0;
		}
		
		$strResult = '
		<div class="icard__offer-params'.($intProductID>0 ? '' : ' active').'">
			
			<div class="ptgbs ptgbs--params">
				
				<div class="ptgb ptgb ptgb--param-pack">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Объем</div>
							<div class="ptgb__title">'.$arOfferProp["SIZE"]["VALUE"].' '.$arOfferProp["UNIT"]["VALUE"].'</div>
						</div>
					</div>
				</div>
				<div class="ptgb mobile-show">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Наличие</div>
							<div class="ptgb__title">
								<div class="tags">
									
									<div class="tag tag--circled'.(\ig\CHelper::isAvailable($arOffer["PROPERTIES"]["AVAILABLE"]["VALUE"]) ? ' color-green' : '').'">
										'.$arOffer["PROPERTIES"]["AVAILABLE"]["VALUE"].'
									</div>
								
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ptgb ptgb--param-price">
					<div class="ptgb__inner">
						<div class="ptgb__content">
							<div class="ptgb__subtitle">Цена шт.</div>
							<div class="ptgb__title nowrap">
								
								<div class="icard__price'.(isset($arOffer["BASE_PRICE_VALUE"]) ? ' color-active' : '').'">
									<span class="font-bold">'.\ig\CFormat::getFormattedPrice($arOffer["MIN_PRICE_VALUE"], "RUB", array("RUB_SIGN" => '')).'</span>
									<span class="font-light">₽</span>
								</div>';
		
		$strResult .= '
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tags">
				<div class="tag tag--circled'.(\ig\CHelper::isAvailable($arOffer["PROPERTIES"]["AVAILABLE"]["VALUE"]) ? ' color-green' : '').' mobile-hide">
					'.$arOffer["PROPERTIES"]["AVAILABLE"]["VALUE"].'
				</div>
				<div class="tag mobile-show-inline-block">Хит сезона</div>';
		
		if(!empty($arOfferProp["ACTION"]["VALUE"])) {
			$strResult .= '<div class="tag">Скидка</div>';
		}
		
		if(!empty($arOfferProp["NEW"]["VALUE"])) {
			$strResult .= '<div class="tag">Новинка</div>';
		}
		
		$strResult .= '
			</div>
		</div>';
		
		return $strResult;
	}
}