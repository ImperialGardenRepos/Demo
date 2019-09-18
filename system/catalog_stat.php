<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("highload");

class skCatalogStat {
	public static function prepareOldUrl($strUrl) {
		return str_replace(array('www.imperialgarden.ru', "https"), array('old.imperialgarden.ru', "http"), $strUrl);
	}
}

//if(!$USER -> IsAuthorized()) {
//	$APPLICATION->AuthForm("У вас нет права доступа.");
//} else {
//	if(!$USER -> IsAdmin() && !in_array(12, CUser::GetUserGroup($USER -> GetID()))) {
//		$APPLICATION->AuthForm("У вас нет права доступа.");
//	}
//}

$intCatalogIblockID = \ig\CHelper::getIblockIdByCode('catalog');
$intOffersIblockID = \ig\CHelper::getIblockIdByCode('offers');

$arResult = array();

$rsGroup = \ig\Highload\Base::getList(\ig\Highload\Base::getHighloadBlockIDByName("Group"), array("UF_ACTIVE" => 1), array("UF_NAME", "UF_XML_ID"), array(), true);
while($arGroup = $rsGroup -> Fetch()) {
	$arResult["GROUP"][$arGroup["UF_XML_ID"]] = array(
		"NAME" => $arGroup["UF_NAME"],
		"ID" => $arGroup["ID"]
	);
}

if($_REQUEST["SORT_FIELD"]) {
	$strSortDir = ($_REQUEST["SORT_FIELD"]=='ASC'?'ASC':'DESC');
	
	if($_REQUEST["SORT_FIELD"] == 'INTL') {
		$arSectionSort = array(
			"UF_INT_LINKS_CNT" => $strSortDir,
		);
		
		$arElementSort = array(
			"PROPERTY__INT_LINKS_CNT" => $strSortDir,
		);
	}
}

//if(empty($_REQUEST["GROUP"])) {
//	foreach($arResult["GROUP"] as $strXmlID => $arGroup) {
//		$_REQUEST["GROUP"] = $strXmlID;
//		break;
//	}
//}

$arSortFilter = array("IBLOCK_ID" => $intCatalogIblockID);
if(!empty($_REQUEST["GROUP"])) $arSortFilter["PROPERTY_GROUP"] = $_REQUEST["GROUP"];
if(!empty($_REQUEST["ACTIVE"])) $arSortFilter["ACTIVE"] = $_REQUEST["ACTIVE"];
if($_REQUEST["PRIORITY_SET"]=='Y') $arSortFilter["!PROPERTY_PRIORITY"] = false;
if($_REQUEST["P_TEXT"] == 'Y') {
	$arSortFilter["!PREVIEW_TEXT"] = false;
} elseif($_REQUEST["P_TEXT"] == 'N') {
	$arSortFilter["PREVIEW_TEXT"] = false;
}

if($_REQUEST["P_PHOTO"] == 'Y') {
	$arSortFilter[] = array(
		"LOGIC" => "OR",
		array("!PROPERTY_PHOTO_WINTER" => false),
		array("!PROPERTY_PHOTO_AUTUMN" => false),
		array("!PROPERTY_PHOTO_SUMMER" => false),
		array("!PROPERTY_PHOTO_10YEARS" => false),
		array("!PROPERTY_PHOTO_FRUIT" => false),
		array("!PROPERTY_PHOTO_FLOWER" => false),
		array("!DETAIL_PICTURE" => false),
	);
} elseif($_REQUEST["P_PHOTO"] == 'N') {
	$arSortFilter["PROPERTY_PHOTO_WINTER"] = false;
	$arSortFilter["PROPERTY_PHOTO_AUTUMN"] = false;
	$arSortFilter["PROPERTY_PHOTO_SUMMER"] = false;
	$arSortFilter["PROPERTY_PHOTO_10YEARS"] = false;
	$arSortFilter["PROPERTY_PHOTO_FRUIT"] = false;
	$arSortFilter["PROPERTY_PHOTO_FLOWER"] = false;
	$arSortFilter["DETAIL_PICTURE"] = false;
}

if($_REQUEST["OFFERS_MODE"]=='N') {
	$strSql = 'SELECT ID FROM b_iblock_element WHERE IBLOCK_ID=1 AND ID NOT IN (SELECT PROPERTY_2 as ID FROM b_iblock_element_prop_s2 WHERE PROPERTY_2 IS NOT NULL)';
	$arSortFilter["ID"] = array();
	$rsQuery = $DB->Query($strSql, false, $err_mess.__LINE__);
	while($arQuery = $rsQuery -> Fetch()) {
		$arSortFilter["ID"][] = $arQuery["ID"];
	}
} elseif($_REQUEST["OFFERS_MODE"]=='Y') {
	$strSql = 'SELECT ID FROM b_iblock_element WHERE IBLOCK_ID=1 AND ID IN (SELECT PROPERTY_2 as ID FROM b_iblock_element_prop_s2 WHERE PROPERTY_2 IS NOT NULL)';
	$arSortFilter["ID"] = array();
	$rsQuery = $DB->Query($strSql, false, $err_mess.__LINE__);
	while($arQuery = $rsQuery -> Fetch()) {
		$arSortFilter["ID"][] = $arQuery["ID"];
	}
}


$rsSec = CIBlockSection::GetList(Array(), array("IBLOCK_ID"=> $intCatalogIblockID, "DEPTH_LEVEL" => 1, "ACTIVE" => "Y"), false, array("ID"));
$arResult["ROD_CNT_A"] = $rsSec -> SelectedRowsCount();

$rsSec = CIBlockSection::GetList(Array(), array("IBLOCK_ID"=> $intCatalogIblockID, "DEPTH_LEVEL" => 1, "ACTIVE" => "N"), false, array("ID"));
$arResult["ROD_CNT_N"] = $rsSec -> SelectedRowsCount();

$rsSec = CIBlockSection::GetList(Array(), array("IBLOCK_ID"=> $intCatalogIblockID, "DEPTH_LEVEL" => 2, "ACTIVE" => "Y"), false, array("ID"));
$arResult["VID_CNT_A"] = $rsSec -> SelectedRowsCount();

$rsSec = CIBlockSection::GetList(Array(), array("IBLOCK_ID"=> $intCatalogIblockID, "DEPTH_LEVEL" => 2, "ACTIVE" => "N"), false, array("ID"));
$arResult["VID_CNT_N"] = $rsSec -> SelectedRowsCount();

$rsI = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => $intCatalogIblockID, "ACTIVE" => "Y"), array());
$arResult["SORT_CNT_A"] = $rsI;

$rsI = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => $intCatalogIblockID, "ACTIVE" => "N"), array());
$arResult["SORT_CNT_N"] = $rsI;

if(isset($_REQUEST["btnSearch"])) {
	$arSections = array();
	$strRodData = '';
	$rsSections = CIBlockSection::GetList(Array("left_margin" => "asc"), array("IBLOCK_ID" => $intCatalogIblockID), false, array(
		"ID",
		"NAME",
		"ACTIVE",
		"DEPTH_LEVEL",
		"DESCRIPTION",
		"CODE",
		"IBLOCK_ID",
		"SECTION_PAGE_URL",
		"UF_OLD_URL",
		"UF_INT_LINKS_CNT"
	));
	while ($arSection = $rsSections->GetNext()) {
		if($arSection["DEPTH_LEVEL"] == 1) {
			$strRodData = '
<td><a target="_blank" href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID='.$arSection["IBLOCK_ID"].'&type=catalog&ID='.$arSection["ID"].'&lang=ru&find_section_section=0&from=iblock_section_admin">'.$arSection["NAME"].'</a> ['.$arSection["ACTIVE"].']</td>
<td>'.intval($arSection["UF_INT_LINKS_CNT"]).'</td>
<td>'.$arSection["CODE"].'</td>
<td>'.(empty($arSection["DESCRIPTION"])?'N':'Y').'</td>
<td>'.(empty($arSection["UF_OLD_URL"]) ? '&nbsp;' : '<a target="_blank" href="'.\skCatalogStat::prepareOldUrl($arSection["UF_OLD_URL"]).'">--></a>').'</td>
<td><a target="_blank" href="'.$arSection["SECTION_PAGE_URL"].'">--></a></td>';
		} else {
			$arSections[$arSection["ID"]] = $strRodData.'<td><a target="_blank" href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID='.$arSection["IBLOCK_ID"].'&type=catalog&ID='.$arSection["ID"].'&lang=ru&find_section_section=0&from=iblock_section_admin">'.$arSection["NAME"].' </a>['.$arSection["ACTIVE"].']</td><td>'.intval($arSection["UF_INT_LINKS_CNT"]).'</td><td>'.$arSection["CODE"].'</td><td>'.(empty($arSection["DESCPITION"])?'N':'Y').'</td><td>'.(empty($arSection["UF_OLD_URL"]) ? '&nbsp;' : '<a target="_blank" href="'.\skCatalogStat::prepareOldUrl($arSection["UF_OLD_URL"]).'">--></a>').'</td><td><a target="_blank" href="'.$arSection["SECTION_PAGE_URL"].'">--></a></td>';
		}
	}
	
	$arProductID = array();
	$rsI = CIBlockElement::GetList(Array("IBLOCK_SECTION_ID" => "ASC"), $arSortFilter, false, false, array(
		"ID",
		"IBLOCK_ID",
		"NAME",
		"PROPERTY_GROUP",
		"ACTIVE",
		"IBLOCK_SECTION_ID",
		"CODE",
		"PREVIEW_TEXT",
		"PROPERTY_PHOTO_WINTER",
		"PROPERTY_PHOTO_AUTUMN",
		"PROPERTY_PHOTO_SUMMER",
		"PROPERTY_PHOTO_10YEARS",
		"PROPERTY_PHOTO_FRUIT",
		"PROPERTY_PHOTO_FLOWER",
		"DETAIL_PICTURE",
		"PROPERTY_RECOMMENDED",
		"PROPERTY_PRIORITY",
		"PROPERTY_IS_RUSSIAN",
		"DETAIL_PAGE_URL",
		"PROPERTY_OLD_URL",
		"PROPERTY_INT_LINKS_CNT"
	));
	$strTmp = '';
	$arTmp = array();
	while ($arI = $rsI->GetNext()) {
		$arProductID[] = $arI["ID"];
		
		if($arI["DETAIL_PICTURE"]>0 || $arI["PROPERTY_PHOTO_WINTER_VALUE"]>0 || $arI["PROPERTY_PHOTO_AUTUMN_VALUE"] || $arI["PROPERTY_PHOTO_SUMMER_VALUE"] || $arI["PROPERTY_PHOTO_10YEARS_VALUE"] || $arI["PROPERTY_PHOTO_FRUIT_VALUE"]|| $arI["PROPERTY_PHOTO_FLOWER_VALUE"]) {
			$bHasPicture = true;
		} else {
			$bHasPicture = false;
		}
		
		$strTmp .= '<tr>'.$arSections[$arI["IBLOCK_SECTION_ID"]].'
		<td><a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arI["IBLOCK_ID"].'&type=catalog&ID='.$arI["ID"].'&lang=ru&find_section_section=-1&WF=Y">'.$arI["NAME"].'</a></td>
		<td>'.intval($arI["PROPERTY_INT_LINKS_CNT_VALUE"]).'</td>
		<td>'.(empty($arI["PROPERTY_OLD_URL_VALUE"]) ? '&nbsp;' : '<a target="_blank" href="'.\skCatalogStat::prepareOldUrl($arI["PROPERTY_OLD_URL_VALUE"]).'">--></a>').'</td>
		<td><a target="_blank" href="'.\ig\CHelper::prepareCatalogDetailUrl($arI).'">--></a></td>
		<td>'.$arI["CODE"].'</td>
		<td>'.(empty($arI["PREVIEW_TEXT"])?'N':'Y').'</td>
		<td>'.($bHasPicture?'Y':'N').'</td>
		<td>'.$arResult["GROUP"][$arI["PROPERTY_GROUP_VALUE"]]["NAME"].'</td>
		<td>'.(empty($arI["PROPERTY_IS_RUSSIAN_VALUE"]) ? '' : 'Да').'</td>
		<td>'.$arI["ACTIVE"].'</td>
		<td>'.intval($arI["PROPERTY_PRIORITY_VALUE"]).'</td>
		<td>'.(empty($arI["PROPERTY_RECOMMENDED_VALUE"]) ? '' : 'Да').'</td>
		#OFFERS_CNT_'.$arI["ID"].'#
	</tr>
	#OFFERS_'.$arI["ID"].'#';
	}
	$strNav = $rsI->GetPageNavStringEx($navComponentObject, 'Страница', false, false);
	
	if(!empty($arProductID)) {
		$strVidSql = 'SELECT COUNT(DISTINCT IBLOCK_SECTION_ID) as CNT FROM b_iblock_element WHERE ID IN('.implode(',', $arProductID).')';
		$res = $DB->Query($strVidSql, false, $err_mess.__LINE__);
		$ar = $res->fetch();
		$arResult["VID_CNT_F"] = $ar["CNT"];
		
		$strSql = 'SELECT COUNT(DISTINCT IBLOCK_SECTION_ID) as CNT FROM b_iblock_section WHERE ID IN(SELECT DISTINCT IBLOCK_SECTION_ID FROM b_iblock_element WHERE ID IN('.implode(',', $arProductID).'))';
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		$ar = $res->fetch();
		$arResult["ROD_CNT_F"] = $ar["CNT"];
	}
}
?>
	<style>
		.stat {
			border-collapse: collapse;
		}
		
		.stat th {
			padding: 4px;
			border: 1px solid #949b9f;
		}
		
		.stat td {
			padding: 2px;
			border: 1px solid #949b9f;
		}
		
		.stat_inner {
			width: 100%;
			margin-bottom: 20px;
		}
		
		.js-sortable {
			color: #0a7ddd;
		}
		
		.js-sortable:hover {
			cursor: pointer;
		}
		
		th.sorted[data-order="1"],
		th.sorted[data-order="-1"] {
			position: relative;
		}
		
		th.sorted[data-order="1"]::after,
		th.sorted[data-order="-1"]::after {
			right: -4px;
			position: absolute;
		}
		
		th.sorted[data-order="-1"]::after {
			content: "▼"
		}
		
		th.sorted[data-order="1"]::after {
			content: "▲"
		}
	</style>
	<form method="get">
		Группа <select name="GROUP">
			<option value="">Все</option><?
			foreach($arResult["GROUP"] as $strXmlID => $arGroup) { ?>
				<option<?=($_REQUEST["GROUP"]==$strXmlID?' selected':'')?> value="<?=$strXmlID?>"><?=$arGroup["NAME"]?></option>
			<?}?></select><br>
		Активность <select name="ACTIVE">
			<option value=""></option>
			<option<?=($_REQUEST["ACTIVE"]=='Y'?' selected':'')?> value="Y">Да</option>
			<option<?=($_REQUEST["ACTIVE"]=='N'?' selected':'')?> value="N">Нет</option>
		</select><br>
		Указан приоритет <input type="checkbox" name="PRIORITY_SET"<?=($_REQUEST["PRIORITY_SET"]=='Y'?' checked':'')?> value="Y"><br>
		Показывать ТП <input type="checkbox" name="SHOW_OFFERS"<?=($_REQUEST["SHOW_OFFERS"]=='Y'?' checked':'')?> value="Y"><br>
		У Т есть ТП <select name="OFFERS_MODE">
			<option value="">Все</option>
			<option<?=($_REQUEST["OFFERS_MODE"]=='Y'?' selected':'')?> value="Y">Да</option>
			<option<?=($_REQUEST["OFFERS_MODE"]=='N'?' selected':'')?> value="N">Нет</option>
		</select><br>
		У Т есть описание <select name="P_TEXT">
			<option value="">Все</option>
			<option<?=($_REQUEST["P_TEXT"]=='Y'?' selected':'')?> value="Y">Да</option>
			<option<?=($_REQUEST["P_TEXT"]=='N'?' selected':'')?> value="N">Нет</option>
		</select><br>
		У Т есть фото <select name="P_PHOTO">
			<option value="">Все</option>
			<option<?=($_REQUEST["P_PHOTO"]=='Y'?' selected':'')?> value="Y">Да</option>
			<option<?=($_REQUEST["P_PHOTO"]=='N'?' selected':'')?> value="N">Нет</option>
		</select><br>
		У ТП есть фото <select name="O_PHOTO">
			<option value="">Все</option>
			<option<?=($_REQUEST["O_PHOTO"]=='Y'?' selected':'')?> value="Y">Да</option>
			<option<?=($_REQUEST["O_PHOTO"]=='N'?' selected':'')?> value="N">Нет</option>
		</select><br><?
		if(false) { ?><br>
		Сортировка <select name="SORT_FIELD">
			<option value="">-</option>
			<option<?=($_REQUEST["SORT_FIELD"]=='INTL'?' selected':'')?> value="Y">По внешним ссылкам</option>
		</select> <select name="SORT_DIR">
			<option<?=($_REQUEST["SORT_DIR"]=='ASC'?' selected':'')?> value="Y">по возрастанию</option>
			<option<?=($_REQUEST["SORT_DIR"]=='DESC'?' selected':'')?> value="N">по убыванию</option>
		</select><br><?
		}?>
		<br><input type="submit" name="btnSearch" value="Отправить">
	</form><br><br><br>
	<table class="stat">
		<tr>
			<th>&nbsp;</th>
			<th>Всего</th>
			<th>Активных</th>
			<th>Неактивных</th>
			<th>По фильтру</th>
		</tr>
		<tr>
			<td>Родов</td>
			<td><?=$arResult["ROD_CNT_A"]+$arResult["ROD_CNT_N"]?></td>
			<td><?=$arResult["ROD_CNT_A"]?></td>
			<td><?=$arResult["ROD_CNT_N"]?></td>
			<td><?=(isset($arResult["ROD_CNT_F"])?$arResult["ROD_CNT_F"]:'-')?></td>
		</tr>
		<tr>
			<td>Видов</td>
			<td><?=$arResult["VID_CNT_A"]+$arResult["VID_CNT_N"]?></td>
			<td><?=$arResult["VID_CNT_A"]?></td>
			<td><?=$arResult["VID_CNT_N"]?></td>
			<td><?=(isset($arResult["VID_CNT_F"])?$arResult["VID_CNT_F"]:'-')?></td>
		</tr>
		<tr>
			<td>Сортов</td>
			<td><?=$arResult["SORT_CNT_A"]+$arResult["SORT_CNT_N"]?></td>
			<td><?=$arResult["SORT_CNT_A"]?></td>
			<td><?=$arResult["SORT_CNT_N"]?></td>
			<td><?=(isset($arProductID)?count($arProductID):'-')?></td>
		</tr>
	</table><br><br><br>
<?
if(isset($_REQUEST["btnSearch"])) { ?>
	
	<table class="stat js-table_sort">
		<thead>
			<tr>
				<th>Род</th>
				<th class="js-sortable">int-L</th>
				<th>Род (код)</th>
				<th>Descr</th>
				<th>Old url</th>
				<th>New url</th>
				<th>Вид</th>
				<th class="js-sortable">int-L</th>
				<th>Вид (код)</th>
				<th>Descr</th>
				<th>Old url</th>
				<th>New url</th>
				<th>Название вида\сорта</th>
				<th class="js-sortable">int-L</th>
				<th>Old url</th>
				<th>New url</th>
				<th>Название (код)</th>
				<th>PDescr</th>
				<th>Pic</th>
				<th>Группа</th>
				<th>Русорт</th>
				<th>Акт</th>
				<th>Приор</th>
				<th>Хит</th>
				<th>Кол-во ТП</th>
				<th>Кол-во акт. ТП</th>
				<th>Кол-во неакт. ТП</th>
			</tr>
		</thead>
		<tbody>
		<?
		
		
		$arSR = array();
		if(!empty($arProductID)) {
			
			$arOfferFilter = array(
				"IBLOCK_ID" => $intOffersIblockID,
				"PROPERTY_CML2_LINK" => $arProductID["ID"]
			);
			
			if($_REQUEST["O_PHOTO"] == 'Y') {
				$arOfferFilter["!DETAIL_PICTURE"] = false;
			} elseif($_REQUEST["O_PHOTO"] == 'N') {
				$arOfferFilter["DETAIL_PICTURE"] = false;
			}
			
			$rsI = CIBlockElement::GetList(Array("ID" => "ASC"), $arOfferFilter, false, false, array(
				"ID",
				"IBLOCK_ID",
				"ACTIVE",
				"PROPERTY_CML2_LINK",
				"NAME",
				"DETAIL_PICTURE",
				"PROPERTY_AVAILABLE",
				"PROPERTY_ACTION",
				"PROPERTY_NEW",
				"PROPERTY_PACK",
				"PROPERTY_HEIGHT_NOW_EXT",
				"CATALOG_GROUP_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID"),
				"CATALOG_GROUP_".\ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")
			));
			while ($arI = $rsI->Fetch()) {
				
				if($_REQUEST["SHOW_OFFERS"] == 'Y') {
					$arSR["#OFFERS_".$arI["PROPERTY_CML2_LINK_VALUE"]."#"] .= '
		<tr>
			<td><a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arI["IBLOCK_ID"].'&type=catalog&ID='.$arI["ID"].'&lang=ru&find_section_section=-1&WF=Y">'.$arI["NAME"].'</td></td>
			<td>'.$arI["ACTIVE"].'</td>
			<td>'.$arI["PROPERTY_PACK_VALUE"].'</td>
			<td>'.\ig\CHelper::getGroupPropertiesValues($arI["PROPERTY_HEIGHT_NOW_EXT_VALUE"])["UF_NAME"].'</td>
			<td>'.(empty($arI["PROPERTY_ACTION_VALUE"]) ? '' : 'Да').'</td>
			<td>'.(empty($arI["PROPERTY_NEW_VALUE"]) ? '' : 'Да').'</td>
			<td>'.(empty($arI["DETAIL_PICTURE"]) ? '' : 'Да').'</td>
			<td>'.$arI["PROPERTY_AVAILABLE_VALUE"].'</td>
			<td>'.$arI["CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_BASE_PRICE_ID")].'</td>
			<td>'.$arI["CATALOG_PRICE_".\ig\CRegistry::get("CATALOG_ACTION_PRICE_ID")].'</td>
		</tr>';
				}
				if(is_string($arTmp[$arI["PROPERTY_CML2_LINK_VALUE"]][$arI["ACTIVE"]])) {
					$arTmp[$arI["PROPERTY_CML2_LINK_VALUE"]][$arI["ACTIVE"]] = array();
				}
				
				$arTmp[$arI["PROPERTY_CML2_LINK_VALUE"]][$arI["ACTIVE"]]++;
			}
			
			foreach ($arTmp as $intCatalogID => $arCatalogData) {
				$arSR["#OFFERS_CNT_".$intCatalogID."#"] = '<td>'.($arCatalogData["Y"] + $arCatalogData["N"]).'</td><td>'.$arCatalogData["Y"].'</td><td>'.$arCatalogData["N"].'</td>';
			}
		}
		
		foreach ($arProductID as $intID) {
			if(!isset($arSR["#OFFERS_CNT_".$intID."#"])) {
				$arSR["#OFFERS_CNT_".$intID."#"] = '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
			}
			
			if(!isset($arSR["#OFFERS_".$intID."#"]) || $_REQUEST["SHOW_OFFERS"] != 'Y') {
				$arSR["#OFFERS_".$intID."#"] = '';
			} else {
				$arSR["#OFFERS_".$intID."#"] = '<tr><td colspan="2">&nbsp;</td><td colspan="100%"><table class="stat stat_inner"><tr><th>Название</th><th>int-L</th><th>Активность</th><th>Упаковка</th><th>Высота сейчас</th><th>Акция</th><th>Новинка</th><th>Pic</th><th>Наличие</th><th>Цена (осн)</th><th>Цена (акц)</th></tr>'.$arSR["#OFFERS_".$intID."#"].'</table></td></tr>';
			}
		}
		
		$strTmp = str_replace(array_keys($arSR), array_values($arSR), $strTmp);
		
		echo $strTmp;
		
		?>
		</tbody>
	</table><br><br><br><?
	echo $strNav;?>
	<script>
        document.addEventListener('DOMContentLoaded', () => {
            const getSort = ({ target }) => {
                const order = (target.dataset.order = -(target.dataset.order || -1));
                const index = [...target.parentNode.cells].indexOf(target);
                const collator = new Intl.Collator(['en', 'ru'], { numeric: true });
                const comparator = (index, order) => (a, b) => order * collator.compare(
                    a.children[index].innerHTML,
                    b.children[index].innerHTML
                );
                
                for(const tBody of target.closest('table').tBodies)
                    tBody.append(...[...tBody.rows].sort(comparator(index, order)));
                
                for(const cell of target.parentNode.cells) {
                    cell.classList.toggle('sorted', cell === target);
                }
            };
            
            document.querySelectorAll('.js-table_sort .js-sortable').forEach(tableTH => tableTH.addEventListener('click', () => getSort(event)));
            
        });
	</script>
	<?
}
?>