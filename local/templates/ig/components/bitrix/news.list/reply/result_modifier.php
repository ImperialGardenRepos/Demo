<?
// get sections
$rsSec = CIBlockSection::GetList(Array("SORT"=>"ASC", "NAME" => "ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]), false, array("NAME", "CODE", "SECTION_PAGE_URL"));
while($arSec = $rsSec -> GetNext()) {
	$arResult["SECTIONS"][] = $arSec;
}