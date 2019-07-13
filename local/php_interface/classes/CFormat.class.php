<?
namespace ig;

class CFormat {
	// $arData = array(array("SRC" => '/img.jpg', "DESCRIPTION"=> 'text'));
	public static function getSlider($arData) {
		$strResult = '';
		$strFirstImage = '';
		$strTmp = '';
		
		foreach($arData as $arImage) {
			if(!empty($arImage["SRC"])) {
				if(empty($strFirstImage)) {
					$strFirstImage = $arImage["SRC"];
				}
				
				$strTmp .= ' <img'.(empty($strTmp) ? ' class="active"' : '').' data-lazy-src="'.$arImage["SRC"].'" src="'.SITE_TEMPLATE_PATH.'/img/blank.png" data-src-full="'.$arImage["SRC"].'" data-ihs-content="'.$arImage["DESCTIPTION"].'" alt="">';
			}
		}
		
		if(!empty($strTmp)) {
		$strResult = '
		<div class="image-slider image-slider--autosize js-images-hover-slider js-images-popup-slider cursor-pointer">
            '.$strTmp.'
            <div class="image-slider__size">
                <img src="'.$strFirstImage.'" alt="">
            </div>
        </div>';
		}
		
		return $strResult;
	}
	
	public static function getFormattedPrice($floatPrice, $strCurrency='RUB', $arParams = array()) {
		$strRubSign = !isset($arParams["RUB_SIGN"]) ? '<span class="font-light">₽</span>' : $arParams["RUB_SIGN"];
		
		$strResult = \CCurrencyLang::CurrencyFormat($floatPrice, $strCurrency);
		$strResult = str_replace(' руб.', (strlen($strRubSign)>0?' '.$strRubSign:''), $strResult);
		
		return $strResult;
	}
	
	public static function formatNumber($intNumber) {
		$strResult = number_format($intNumber, 0, '.', ' ');
		
		return $strResult;
	}
	
	public static function formatPropertyName($strName) {
		$arSearchReplace = array(' ext' => '', 'ext' => '');
		
		return str_replace(array_keys($arSearchReplace), array_values($arSearchReplace), $strName);
	}
	
	public static function formatPropertyValue($strCode, $varValue, $arProperty = array(), $arParams = array()) {
		$strResult = '';
		
		if($strCode == 'RIPENING' || $strCode == 'FLOWERING') {
			$intMin = min($varValue);
			$intMax = max($varValue);
			
			if($intMin == $intMax) {
				return \ig\CHelper::intToMonth($intMin);
			} else {
				return \ig\CHelper::intToMonth($intMin).'&mdash;'.\ig\CHelper::intToMonth($intMax);
			}
		} elseif(strpos($strCode, 'COLOR') !== false) {
			$strTmp = '';
			
			if($arParams["IS_TABLE"] == 'Y') {
				
				foreach($varValue as $strColorCode) {
					$strColorName = \ig\CHelper::getGroupPropertiesValues($strColorCode)["UF_NAME"];
					
					if($strColorCode == 'ffffff' || $strColorCode == 'fff6ef') {
						$bAddBorder = true;
					} else {
						$bAddBorder = false;
					}
					
					$strTmp .= '<span class="inline-list__item"><span class="icon-circle icon-circle--medium'.($bAddBorder?' checkbox-color--bordered':'').'" style="background-color: #'.$strColorCode.';"></span> '.$strColorName.'</span>';
				}
				
				if(!empty($strTmp)) {
					$strTmp = '<span class="inline-list">'.$strTmp.'</span>';
				}
			} else {
			
//				<div class="ptgb__title">
//                                <span class="icon-circle icon-circle--medium" style="background-color: #1fda28;"></span> Зеленый
//                            </div>
//
//				<div class="ptgb__title">
//                                <span class="icon-circle icon-circle--medium" style="background-color: #1fda28;"></span>
//                                <span class="icon-circle icon-circle--medium" style="background-color: #e80;"></span>
//                            </div>
				
				foreach($varValue as $strColorCode) {
					$strColorName = \ig\CHelper::getGroupPropertiesValues($strColorCode)["UF_NAME"];
					
					if($strColorCode == 'ffffff' || $strColorCode == 'fff6ef') {
						$bAddBorder = true;
					} else {
						$bAddBorder = false;
					}
					
					$strTmp .= '<span title="'.$strColorName.'" class="checkbox-color icon-circle icon-circle--medium'.($bAddBorder?' checkbox-color--bordered':'').'" style="background-color: #'.$strColorCode.';"></span>';
					if($arParams["COLOR_EXT"] == 'Y' && count($varValue)<2) {
						$strTmp .= '<label>'.$strColorName.'</label>';
					}
				}
			}
			
			$strResult = $strTmp;
		} elseif(is_array($varValue)) {
			if($arProperty["USER_TYPE"] == 'directory') {
				$arTmp = array();
				foreach($varValue as $strValue) {
					$strValueName = \ig\CHelper::getGroupPropertiesValues($strValue)["UF_NAME"];
					if(!empty($strValueName)) {
						$arTmp[] = $strValueName;
					}
				}
				
				$strResult = implode(", ", $arTmp);
			} else {
				return implode(', ', $varValue);
			}
		} else {
			$strResult = \ig\CHelper::getGroupPropertiesValues($varValue)["UF_NAME"];
		}
		
		if(preg_match("#от (.+) до (.+)#iu", $strResult, $arM)) {
			$strResult = $arM[1].'&mdash;'.$arM[2];
		}
		
		return $strResult;
	}
	
	public static function formatPlantTitle($strSort = '', $strRod = '', $strVid = '') {
		$arResult = array();
		
		if(!empty($strRod)) {
			$arResult[] = trim($strRod);
		}
		
		if(!empty($strVid)) {
			$arResult[] = trim($strVid);
		}
		
		if(!empty($strSort)) {
			if(!empty($arResult)) {
				$arResult[] = "'".$strSort."'";
			} else {
				$arResult[] = $strSort;
			}
		}
		
		return implode(' ', $arResult);
	}
	
	public static function formatContainerPack($arOffer, $arSort) {
		$strResult = '';
		
		if(strlen($arOffer["PROPERTIES"]["CONTAINER"]["VALUE"])>0) {
			$strResult .= str_replace('(С)', "C".$arOffer["PROPERTIES"]["CONTAINER"]["VALUE"], $arOffer["PROPERTIES"]["PACK"]["VALUE"]);
		} else {
			$strResult .= $arOffer["PROPERTIES"]["PACK"]["VALUE"];
		}
		
		return $strResult;
	}
	
	public static function formatListMainProperties(&$arSort, $arParams = array()) {
		$arGroupMainProperties = \ig\CHelper::getGroupMainProperties($arSort["PROPERTIES"]["GROUP"]["VALUE"]);
		
		$strResult = '';
		
		foreach($arGroupMainProperties as $arGroupProperty) {
			$propertyValue = $arSort["PROPERTIES"][$arGroupProperty["UF_CODE"]]["VALUE"];
			
			if(
				(is_array($propertyValue) && count($propertyValue)>0)
					||
				(!is_array($propertyValue) && strlen($propertyValue)>0)
			) {
				$arPropertyValue =  \ig\CHelper::getGroupPropertiesValues($propertyValue);
				
				$arSort["PROPERTIES"][$arGroupProperty["UF_CODE"]]["DISPLAYED"] = 'Y';
				
				$strResult .= '
				<div class="ptgb">
					<div class="ptgb__inner">
						<div class="ptgb__icon">';
				
				if(!empty($arPropertyValue["UF_ICON"])) {
					$strResult .= '
							<svg class="icon">
								<use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg'.$arPropertyValue["UF_ICON"].'"></use>
							</svg>';
				};
				
				$strResult .= '
						</div>
						<div class="ptgb__content">
							<div class="ptgb__subtitle">'.\ig\CFormat::formatPropertyName($arGroupProperty["UF_NAME"]).'</div>
							<div class="ptgb__title'.(!empty($arParams["TITLE_CLASS"])?' '.$arParams["TITLE_CLASS"]:'').'">'.\ig\CFormat::formatPropertyValue($arGroupProperty["UF_CODE"], $propertyValue, false, $arParams).'</div>
						</div>
					</div>
				</div>';
			}
		}
		
		return $strResult;
	}
}