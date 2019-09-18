<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class CatalogFilter extends \CBitrixComponent {
	public static $arRequestFilter = array();
	
	private static function getFilterAliasHL() {
		return \ig\Highload\Base::getHighloadBlockIDByName('CatalogFilterAlias');
	}
	
	private function canStoreStat() {
		return true;
	}
	
	private function getSectionModel() {
		return \Bitrix\Iblock\Model\Section::compileEntityByIblock(\ig\CHelper::getIblockIdByCode('catalog'));
	}
	
	public function formatHighlight($strText, $varHighlight) {
		if(!is_array($varHighlight)) {
			$arHighlight = array($varHighlight);
		} else $arHighlight = $varHighlight;
		
		foreach ($arHighlight as $singleKeyword) {
			if(preg_match_all("/" . trim($singleKeyword) . "/iu", $strText, $arMatches)) {
				foreach($arMatches[0] as $strMatch) {
					$highlightedKeyword = '<span class="text-hightlight">' . $strMatch . '</span>';
					$strText = str_replace($strMatch, $highlightedKeyword, $strText);
				}
			}
			
			$strSwitched = \ig\CUtil::toCyrillic($singleKeyword);
			if(preg_match_all("/" . trim($strSwitched) . "/iu", $strText, $arMatches)) {
				foreach($arMatches[0] as $strMatch) {
					$highlightedKeyword = '<span class="text-hightlight">' . $strMatch . '</span>';
					$strText = str_replace($strMatch, $highlightedKeyword, $strText);
				}
			}
		}
		
		return $strText;
	}
	
	public function getSearchResult($arSearchData) {
		$arResult = array();
		
		$arProductID = array();
		$arSectionID = array();
		foreach($arSearchData as $arItem) {
			$arProductID[] = $arItem["cat_id"];
			$arSectionID[] = $arItem["cat_section_2"];
		}
		
		$rsI = \CIBlockElement::GetList(Array(), array(
			"ACTIVE" => "Y",
			"ID" => $arProductID,
			"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog-garden')
		), false, false, array(
			"ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID"
		));
		while ($arI = $rsI->GetNext()) {
			$arResult["ITEMS"][$arI["IBLOCK_SECTION_ID"]][] = $arI;
		}
		
		$rsSec = CIBlockSection::GetList(Array("SORT"=>"ASC", "NAME" => "ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=> \ig\CHelper::getIblockIdByCode('catalog-garden'), "ID" => $arSectionID), false, array("NAME", "ID"));
		while($arSec = $rsSec -> GetNext()) {
			$arResult["SECTIONS"][] = $arSec;
		}
		
		return $arResult;
	}
	
	public function searchEntity($strQuery) {
		$strQuery = trim($strQuery);
		
		$obSearch = new \ig\sphinx\CatalogGardenOffers();
		
		$arSearchParams = array(
			"MATCH_FILTER" => array("NAME" => $strQuery),
			"LIMIT" => 20,
			"GROUP" => array('NAME'),
			"SELECT" => array("NAME", "CAT_ID", "CAT_SECTION_2"),
			"ORDER" => array("NAME" => "ASC")
		);
		
		$rsSearch = $obSearch -> search($arSearchParams);
		$arResult = $obSearch -> fetchAll($rsSearch);
		
		if(empty($arResult)) {
			$arSearchParams["MATCH_FILTER"] = array("NAME" => $strQuery."*");
			$rsSearch = $obSearch -> search($arSearchParams);
			$arResult = $obSearch -> fetchAll($rsSearch);
		}
		
		if(empty($arResult)) {
			$arSearchParams["MATCH_FILTER"] = array("NAME" => "*".$strQuery."*");
			$rsSearch = $obSearch -> search($arSearchParams);
			$arResult = $obSearch -> fetchAll($rsSearch);
		}
		
		return $arResult;
	}
	
	public function searchSort($strQuery, $arParams = array()) {
		$arResult = array();
		
		$strSwitched = \ig\CUtil::toCyrillic($strQuery);
		
		$arFilter = array(
			"ACTIVE" => "Y",
			"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog'),
			array(
				"LOGIC" => "OR",
				array("NAME" => "%".$strQuery."%"),
				array("PROPERTY_NAME_LAT" => "%".$strQuery."%"),
				array("PROPERTY_SYNONYM_RUS" => "%".$strQuery."%"),
				array("PROPERTY_SYNONYM_LAT" => "%".$strQuery."%"),
				array("NAME" => "%".$strSwitched."%"),
				array("PROPERTY_NAME_LAT" => "%".$strSwitched."%"),
				array("PROPERTY_SYNONYM_RUS" => "%".$strSwitched."%"),
				array("PROPERTY_SYNONYM_LAT" => "%".$strSwitched."%")
			)
		);
		
		if($arParams["FOUND"]["TYPE"] == 'VID') {
			$arFilter["SECTION_ID"] = $arParams["FOUND"]["ITEMS"];
		} elseif ($arParams["FOUND"]["TYPE"] == 'ROD') {
			$rsVid = \Bitrix\Iblock\SectionTable::getList(array(
				"filter" => array(
					"ACTIVE" => "Y",
					"IBLOCK_SECTION_ID" => $arParams["FOUND"]["ITEMS"],
					"IBLOCK_ID" => \ig\CHelper::getIblockIdByCode('catalog')
				),
				"select" => array("ID")
			));
			while($arVid = $rsVid -> fetch()) {
				$arFilter["SECTION_ID"][] = $arVid["ID"];
			}
		}
		
		$rsI = CIBlockElement::GetList(Array("NAME" => "ASC"), $arFilter, false, false, array(
			"ID", "IBLOCK_ID", "NAME"
		));
		while ($arI = $rsI->Fetch()) {
			$arResult[] = $arI["ID"];
		}
		
		if(!empty($arResult)) {
			$arOffersExists = \CCatalogSKU::getExistOffers($arResult, \ig\CHelper::getIblockIdByCode('catalog'));
			$arResult = array();
			foreach($arOffersExists as $intID => $bOfferExist) {
				if($bOfferExist) {
					$arResult[] = $intID;
				}
			}
		}
		
		return $arResult;
	}
	
	public function searchRod($strQuery) {
		$arResult = array();
		
		$strSwitched = \ig\CUtil::toCyrillic($strQuery);
		
		$sectionModel = $this -> getSectionModel();
		$rsSections = $sectionModel::getList(
			array(
				"select" => array(
					"NAME",
					"ID",
					"IBLOCK_ID"
				),
				"order" => array("NAME" => "ASC"),
				"filter" => array(
					'IBLOCK_ID' => \ig\CHelper::getIblockIdByCode('catalog'),
					"DEPTH_LEVEL" => 1,
					"ACTIVE" => "Y",
					array(
						"LOGIC" => "OR",
						array("%NAME" => $strQuery),
						array("%UF_SYNONYM_RUS" => $strQuery),
						array("%UF_NAME_LAT" => $strQuery),
						array("%UF_SYNONYM_LAT" => $strQuery),
						array("%NAME" => $strSwitched),
						array("%UF_SYNONYM_RUS" => $strSwitched),
						array("%UF_NAME_LAT" => $strSwitched),
						array("%UF_SYNONYM_LAT" => $strSwitched)
					)
				)
			)
		);
		
		while($arSection = $rsSections -> fetch()) {
			$arResult[] = $arSection["ID"];
		}
		
		return $arResult;
	}
	
	public function searchVid($strQuery, $arParams = array()) {
		$arResult = array();
		
		$strSwitched = \ig\CUtil::toCyrillic($strQuery);
		$sectionModel = $this -> getSectionModel();
		
		$arFilter = array(
			'IBLOCK_ID' => \ig\CHelper::getIblockIdByCode('catalog'),
			"DEPTH_LEVEL" => 2,
			"ACTIVE" => "Y",
			array(
				"LOGIC" => "OR",
				array("%NAME" => $strQuery),
				array("%UF_SYNONYM_RUS" => $strQuery),
				array("%UF_NAME_LAT" => $strQuery),
				array("%UF_SYNONYM_LAT" => $strQuery),
				array("%NAME" => $strSwitched),
				array("%UF_SYNONYM_RUS" => $strSwitched),
				array("%UF_NAME_LAT" => $strSwitched),
				array("%UF_SYNONYM_LAT" => $strSwitched)
			)
		);
		if($arParams["FOUND"]["TYPE"] == 'ROD') {
			$arFilter["IBLOCK_SECTION_ID"] = $arParams["FOUND"]["ITEMS"];
		}
		
		$rsSections = $sectionModel::getList(
			array(
				"select" => array(
					"NAME",
					"ID",
					"IBLOCK_ID",
				),
				"order" => array("NAME" => "ASC"),
				"filter" => $arFilter
			)
		);
		
		while($arSection = $rsSections -> fetch()) {
			$arResult[] = $arSection["ID"];
		}
		
		return $arResult;
	}
	
	public function getFilterByAlias($strAlias) {
		$arResult = array();
		$strAlias = trim($strAlias);
		
		if(strlen($strAlias)>0) {
			$arFilter = \ig\Highload\Base::getFirst(\CatalogFilter::getFilterAliasHL(), array("UF_XML_ID" => $strAlias), array("ID", "UF_REQUEST", "UF_ALIAS_USE_CNT"));
			if($arFilter["ID"]>0) {
				$this -> processUseFilterByAlias($arFilter["ID"], $arFilter["UF_ALIAS_USE_CNT"]);
				$arResult = unserialize($arFilter["UF_REQUEST"]);
			}
		}
		
		return $arResult;
	}
	
	public function processUseFilter($arFilterParams) {
		$strReturn = '';
		ksort($arFilterParams);
		
		$strFilterSerialized = serialize($arFilterParams);
		$strFilterHash = md5($strFilterSerialized);
		
		$arFilter = \ig\Highload\Base::getFirst(\CatalogFilter::getFilterAliasHL(), array("UF_XML_ID" => $strFilterHash), array("ID", "UF_USE_CNT"));
		if($arFilter["ID"]>0) { // reuse
			if($this -> canStoreStat()) {
				\ig\Highload\Base::update(\CatalogFilter::getFilterAliasHL(), array("ID" => $arFilter["ID"]), array("UF_USE_CNT" => ($arFilter["UF_USE_CNT"] + 1)));
			}
			$strReturn = $strFilterHash;
		} else { // new filter
			$arNewFilter = array(
				"UF_XML_ID" => $strFilterHash,
				"UF_REQUEST" => $strFilterSerialized,
				"UF_USE_CNT" => ($this -> canStoreStat()?1:0),
				"UF_ALIAS_USE_CNT" => 0
			);
			
			$rsAdd = \ig\Highload\Base::add(\CatalogFilter::getFilterAliasHL(), $arNewFilter);
			if($rsAdd>0) {
				$strReturn = $strFilterHash;
			}
		}
		
		return $strReturn;
	}
	
	public function processUseFilterByAlias($strFilterID, $intOldCnt = false) {
		if($this -> canStoreStat()) {
			if(is_numeric($strFilterID)) {
				$arFilter = array("ID" => $strFilterID);
			} else {
				$arFilter = array("UF_XML_ID" => $strFilterID);
			}
			
			if(is_numeric($intOldCnt)) {
				\ig\Highload\Base::update(\CatalogFilter::getFilterAliasHL(), $arFilter, array("UF_ALIAS_USE_CNT" => ($intOldCnt + 1)));
			} else {
				$arResult = \ig\Highload\Base::getFirst(\CatalogFilter::getFilterAliasHL(), array("UF_XML_ID" => $strAlias), array(
					"ID",
					"UF_ALIAS_USE_CNT"
				));
				if($arResult["ID"]>0) {
					\ig\Highload\Base::update(\CatalogFilter::getFilterAliasHL(), $arFilter, array("UF_ALIAS_USE_CNT" => ($arResult["UF_ALIAS_USE_CNT"] + 1)));
				}
			}
		}
	}
	
	public static function hasSelected($strCode) {
		return !empty(self::$arRequestFilter[$strCode]);
	}
	
	public function getSelected($strCode, $strValue, $return = '') {
		$formValue = self::$arRequestFilter[$strCode];
		
		if(is_array($formValue)) {
			$bFound = in_array($strValue, $formValue);
		} else {
			$bFound = $formValue == $strValue;
		}
		
		if(strlen($return)>0) {
			if($bFound) {
				return $return;
			}
		} else return $bFound;
	}

	public function getPropertyHtml($strType, $strName, $arParams = array()) {
		$strResult = '';
		$strValues = '';
		
		if($strType == 'radio' || $strType == 'checkbox') {
			$bIsRadio = $strType == 'radio';
			$bIsCheckbox = $strType == 'checkbox';
			
			if(strlen($arParams["DEFAULT"]["NAME"])>0) {
				$bDefaultValueSet = true;
			}
			
			$intCnt = 0;
			$bAllValuesDisabled = true;
			$bHasSelected = \CatalogFilter::hasSelected($strName);
			foreach($arParams["VALUES"] as $arValue) {
				$strValue = (empty($arValue["VALUE"])?$arValue["XML_ID"]:$arValue["VALUE"]);
				
				$bSelected = self::getSelected($strName, $strValue);
				
				$intCount = intval($arParams["COUNT"][$arValue["SPHINX_VALUE"]]);
				
				if($strName == 'PROPERTY_GROUP') {
					$bDisabled = false;
				} else {
					$bDisabled = $arValue["DISABLED"] == 'Y' || $intCount == 0;
				}
				
				$intPropertyCurrentCount = isset($arParams["COUNT"]["TOTAL_UNFILTERED"]) ? $arParams["COUNT"]["TOTAL_UNFILTERED"] : $arParams["CURRENT_COUNT"];
				
				if($bIsRadio) {
					$intOffersDelta = $intCount - $intPropertyCurrentCount;
				} else { // checkbox
					if($bHasSelected) {
						
						if($bSelected) {
							$intOffersDelta = -1*($intPropertyCurrentCount - $intCount);
						} else {
							$intOffersDelta = $intCount;
						}
					} else {
						$intOffersDelta = $intCount - $intPropertyCurrentCount;
					}
				}
				
				if(!$bDisabled) {
					$bAllValuesDisabled = false;
				}
				// ($bSelected && !empty($arParams["PRODUCTS_COUNT"])?' data-product-found-cnt="'.$arParams["PRODUCTS_COUNT"].'"':'')
				// $arParams["PRODUCTS_COUNT"]
				
				if($bSelected && !empty($arParams["PRODUCTS_COUNT"])) {
					if($arValue["ICON"]) {
						$strProductsCount = ' data-ddbox-value="<svg class=\'icon icon--ddbox\'><use xlink:href=\''.$arValue["ICON"].'\'></use></svg><span class=\'ddbox-icon-title\'>'.$arValue["NAME"].'</span> <span class=\'relative js-filter-w-loader\'>('.$arParams["PRODUCTS_COUNT"].')</span>"';
					} else {
						$strProductsCount = ' data-ddbox-value="'.$arValue["NAME"].' <span class=\'relative js-filter-w-loader\'>('.$arParams["PRODUCTS_COUNT"].')</span>"';
					}
				} else {
					$strProductsCount = '';
				}
				
				$strValues .= '
					<label class="checkbox checkbox--block'.($bIsRadio?' checkbox--radio':'').' checkbox-plain-js js-ddbox-input"'.($bDisabled?' disabled':'').'>
						<input type="'.$strType.'"'.$strProductsCount.' name="F['.$strName.']'.($bIsCheckbox?'[]':'').'" value="'.$strValue.'" '.($bDisabled?' disabled':'').($bSelected?' checked':'').((!$bDefaultValueSet && $intCnt==0 && $bIsRadio)?' data-default-checked':'').'>
						<div class="checkbox__icon">';
				
				if($strType == 'checkbox') {
//					$strValues .= '
//					<svg class="icon icon--tick">
//						<use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-tick"></use>
//					</svg>';
				}
				
				if($bIsRadio && $bSelected) {
					$strCntData = '';
				} else {
					$strCntData = ($intOffersDelta>0?'+'.$intOffersDelta:$intOffersDelta);
				}
				
				$strValues .= '
						</div>
						<span class="count">'.($strCntData!=0?$strCntData:'').'</span>
						<span class="js-ddbox-value">';
						
				if(!empty($arValue["ICON"])) {
					$strValues .= '
							<svg class="icon icon--ddbox">
	                            <use xlink:href="'.$arValue["ICON"].'"></use>
	                        </svg>
	                        <span class="ddbox-icon-title">'.$arValue["NAME"].'</span>';
				} elseif(!empty($arValue["COLOR"])) {
					if($arValue["COLOR"] == 'drK4dD37') {
						$strStyle = 'background-image: conic-gradient(from 45deg, #0076ff, #00ff80);';
					} else {
						$strStyle = 'background-color: #'.$arValue["COLOR"].';';
					}
					
					if($arValue["COLOR"] == 'ffffff' || $arValue["COLOR"] == 'fff6ef') {
						$bAddBorder = true;
					} else {
						$bAddBorder = false;
					}
					
					$strValues .= '
							<span class="checkbox-color'.($bAddBorder?' checkbox-color--bordered':'').'" style="'.$strStyle.'"></span>
							<span class="ddbox-icon-title">'.$arValue["NAME"].'</span>';
				} else {
					$strValues .= $arValue["NAME"];
				}
				
				$strValues .= '</span>
					</label>';
				
				$intCnt++;
			}
			
			$strDefaultValueNameCnt = '';
			if(strlen($arParams["DEFAULT"]["NAME"])>0) {
				$intOffersDelta = $arParams["DEFAULT"]["COUNT"]-$intPropertyCurrentCount;
				
				if($bIsRadio) {
					if(!empty($arParams["PRODUCTS_COUNT"])) {
						$strProductsCount = ' data-ddbox-value="'.$arValue["NAME"].' <span class=\'relative js-filter-w-loader\'>('.$arParams["PRODUCTS_COUNT"].')</span>"';
						
						$strDefaultValueNameCnt = ' <span class=\'relative js-filter-w-loader ss123\'>('.$arParams["PRODUCTS_COUNT"].')</span>';
					} else {
						$strProductsCount = '';
					}
					
					$strOffersDelta = '';
					
					if($intOffersDelta != 0) {
						if($intOffersDelta>0) {
							$strOffersDelta = '+'.$intOffersDelta;
						} else {
							$strOffersDelta = $intOffersDelta;
						}
					}
					
					$strValues = '
				<label class="checkbox checkbox--block'.($bIsRadio ? ' checkbox--radio' : '').' checkbox-plain-js js-ddbox-input">
					<input type="'.$strType.'"'.$strProductsCount.' name="F['.$strName.']"'.($bHasSelected ? '' : ' checked').' value="'.$arParams["DEFAULT"]["VALUE"].'" data-default-checked>
					<div class="checkbox__icon"></div>
					<span class="count">'.$strOffersDelta.'</span>
					<span class="js-ddbox-value">'.$arParams["DEFAULT"]["NAME"].'</span>
				</label>'.$strValues;
				}
				
				$strDefaultValueName = $arParams["DEFAULT"]["NAME"];
			}
			
			if(!empty($strValues)) {
				$strValues = '
					<div class="checkboxes">
						<div class="checkboxes__inner">'.$strValues.'</div>
					</div>';
			}
		} elseif($strType == 'period') {
			if(strlen($arParams["MIN"])==0) $arParams["MIN"] = 0;
			if(strlen($arParams["MAX"])==0) $arParams["MAX"] = 11;
			
			$strDefaultValueName = $arParams["DEFAULT"]["NAME"];
			if(empty($strDefaultValueName)) {
				$strDefaultValueName = 'Любой';
			}
			
			$intValueFrom = (isset(self::$arRequestFilter[$strName]["FROM"])?intval(self::$arRequestFilter[$strName]["FROM"]):$arParams["MIN"]);
			$intValueTo = (isset(self::$arRequestFilter[$strName]["TO"])?intval(self::$arRequestFilter[$strName]["TO"]):$arParams["MAX"]);
			
			$dataDefaultValue = '['.$arParams["MIN"].','.$arParams["MAX"].']';
			$strValues = '
				<div class="js-range range-slider-wrapper">
					<div class="range-slider js-range-slider" data-min="'.$arParams["MIN"].'" data-max="'.$arParams["MAX"].'" data-minValue="'.$intValueFrom.'" data-maxValue="'.$intValueTo.'" data-step="1" data-suffix="" data-decimals="0" data-margin="1" data-range-format-function="index_to_month_name"></div>
					
					<div class="js-range-input hidden">
						<input type="text" class="textfield js-ddbox-input js-range-input-get-min" name="F['.$strName.'][FROM]" data-prefix="От " data-suffix="" data-reduce="0" value="'.$intValueFrom.'" data-default-value="'.$arParams["MIN"].'">
					</div>
					
					<div class="js-range-input hidden">
						<input type="text" class="textfield js-ddbox-input js-range-input-get-max" name="F['.$strName.'][TO]" data-prefix="До " data-suffix="" data-reduce="0" value="'.$intValueTo.'" data-default-value="'.$arParams["MAX"].'">
					</div>
					
					<div class="range-slider__values">
						<div class="range-slider__value range-slider__value--min js-range-min-set"></div>
						<div class="range-slider__value range-slider__value--sep">
							&mdash;
						</div>
						<div class="range-slider__value range-slider__value--max js-range-max-set"></div>
					</div>
				</div>';
		}
		
		if(strlen($strValues) > 0) {
			if(!empty($strDefaultValueNameCnt)) {
				$strDefaultValueName .= $strDefaultValueNameCnt;
			}
			
			$strBlockParams = $arParams["BLOCK_PARAMS"];
			if($arParams["DISABLED"] == 'Y' || $bAllValuesDisabled) {
				if(strpos($strBlockParams, 'class') !== 'false') {
					$strBlockParams = str_replace('class="', 'class="disabled ', $strBlockParams);
				} else {
					$strBlockParams .= ' class="disabled"';
				}
			}
			
			$strResult = '
			<div'.(!empty($strBlockParams)?' '.$strBlockParams:'').'>
				<div class="ddbox__selection">
					<div class="ddbox__label">'.$arParams["BLOCK_TITLE"].'</div>
					<div class="ddbox__reset">
						<svg class="icon icon--cross">
							<use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-cross"></use>
						</svg>
					</div>
					<div class="ddbox__arrow">
						<svg class="icon icon--chevron-down">
							<use xlink:href="'.SITE_TEMPLATE_PATH.'/build/svg/symbol/svg/sprite.symbol.svg#icon-chevron-down"></use>
						</svg>
					</div>
					<div class="ddbox__value js-ddbox-selection" data-default-value="'.$dataDefaultValue.'"></div>
					<div class="ddbox__value js-ddbox-placeholder'.($bAllValuesDisabled || $bHasSelected || $arParams["DISABLED"] == 'Y'?' active':'').'">'.$strDefaultValueName.'</div>
				</div>
				<div class="ddbox__dropdown">
					<div class="ddbox__dropdown-inner js-scrollbar">
						'.$strValues.'
					</div>
					<div class="ddbox__dropdown-scroll-gradient"></div>
				</div>
			</div>';
		}
		
		return $strResult;
	}
}
