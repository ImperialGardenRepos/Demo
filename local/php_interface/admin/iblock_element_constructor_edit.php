<?

use Bitrix\Main;
use Bitrix\Main\Loader;

/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/iblock/prolog.php');

$fieldsCommon = [
];

$fieldsByBlock = [
    'banner' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'IMAGE',
        'LINK',
        'LINK_TEXT',
        'TEXT_VERTICAL_ALIGN',
        'TEXT_HORIZONTAL_ALIGN',
        'STRETCH_TO_FULL_WIDTH'
    ],
    'text' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'IMAGE',
        'TEXT_VERTICAL_ALIGN',
        'TEXT_HORIZONTAL_ALIGN',
        'IMAGE_FLOW',
        'IMAGE_MAX_WIDTH',
        'IMAGE_VERTICAL_ALIGN',
        'IMAGE_HORIZONTAL_ALIGN'
    ],
    'table' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'EXCEL_FILE'
    ],
    'product' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'PRODUCT_PLANT',
        'PRODUCT_GARDEN',
        'SORT_FIELD',
        'SORT_ORDER',
        'PRODUCT_TEMPLATE',
    ],
    'form' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'FORM_ID',
        'FORM_TEMPLATE',
    ],
    'map' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'MAP_POINT',
        'MAP_CENTER',
        'MAP_ZOOM',
        'LINKED_MAPS',
    ],
    'file' => [
        'BLOCK_HEADING',
        'BLOCK_TEXT',
        'HEADING_TYPE',
        'ICON',
        'FILE',
    ],
    'grid' => [
        'IMAGE',
        'LINK',
        'LINK_TEXT',
        'SUBLINKS',
        'SUBLINKS_TEXTS'
    ],
];

$typeField = null;

foreach ($PROP as $propertyArray) {
    if ($propertyArray['CODE'] === 'BLOCK_TYPE') {
        $typeField = $propertyArray;
        break;
    }
}
$actualBlockType = $typeField['VALUE'];
$actualBlockType = array_shift($actualBlockType);
if($actualBlockType === null) {
    $actualBlockType = '181';
}

$actualTypeFieldValue = CIBlockPropertyEnum::GetList([], ['ID' => $actualBlockType])->Fetch();
$actualTypeFieldValue = $actualTypeFieldValue['XML_ID'];
foreach ($PROP as $key => $property) {
    if (in_array($property['CODE'], $fieldsByBlock[$actualTypeFieldValue], true) || in_array($property['CODE'], $fieldsCommon, true)) {
        continue;
    }
    unset($PROP[$key]);
}
$PROP = array_values($PROP);
Loader::includeModule('iblock');

//////////////////////////
//START of the custom form
//////////////////////////

//We have to explicitly call calendar and editor functions because
//first output may be discarded by form settings

$nameFormat = CSite::GetNameFormat();

$tabControl->BeginPrologContent();
CJSCore::Init(array('date'));

//TODO: this code only for old html editor. Need remove after final cut old editor
if (
    $bFileman
    && (string)Main\Config\Option::get('iblock', 'use_htmledit') == 'Y'
    && (string)Main\Config\Option::get('fileman', 'use_editor_3', 'Y') != 'Y'
) {
    echo '<div style="display:none">';
    CFileMan::AddHTMLEditorFrame("SOME_TEXT", "", "SOME_TEXT_TYPE", "text",
        array('height' => 450, 'width' => '100%'),
        "N", 0, "", "", $arIBlock["LID"]
    );
    echo '</div>';
}

if ($arTranslit["TRANSLITERATION"] == "Y") {
    CJSCore::Init(array('translit'));
    ?>
    <script type="text/javascript">
        var linked =<?if ($bLinked) echo 'true'; else echo 'false';?>;

        function set_linked() {
            linked = !linked;

            var name_link = document.getElementById('name_link');
            if (name_link) {
                if (linked)
                    name_link.src = '/bitrix/themes/.default/icons/iblock/link.gif';
                else
                    name_link.src = '/bitrix/themes/.default/icons/iblock/unlink.gif';
            }
            var code_link = document.getElementById('code_link');
            if (code_link) {
                if (linked)
                    code_link.src = '/bitrix/themes/.default/icons/iblock/link.gif';
                else
                    code_link.src = '/bitrix/themes/.default/icons/iblock/unlink.gif';
            }
            var linked_state = document.getElementById('linked_state');
            if (linked_state) {
                if (linked)
                    linked_state.value = 'Y';
                else
                    linked_state.value = 'N';
            }
        }

        var oldValue = '';

        function transliterate() {
            if (linked) {
                var from = document.getElementById('NAME');
                var to = document.getElementById('CODE');
                if (from && to && oldValue != from.value) {
                    BX.translit(from.value, {
                        'max_len': <?echo intval($arTranslit['TRANS_LEN'])?>,
                        'change_case': '<?echo $arTranslit['TRANS_CASE']?>',
                        'replace_space': '<?echo $arTranslit['TRANS_SPACE']?>',
                        'replace_other': '<?echo $arTranslit['TRANS_OTHER']?>',
                        'delete_repeat_replace': <?echo $arTranslit['TRANS_EAT'] == 'Y' ? 'true' : 'false'?>,
                        'use_google': <?echo $arTranslit['USE_GOOGLE'] == 'Y' ? 'true' : 'false'?>,
                        'callback': function (result) {
                            to.value = result;
                            setTimeout('transliterate()', 250);
                        }
                    });
                    oldValue = from.value;
                } else {
                    setTimeout('transliterate()', 250);
                }
            } else {
                setTimeout('transliterate()', 250);
            }
        }

        transliterate();
    </script>
    <?
}
?>
    <script type="text/javascript">
        var InheritedPropertiesTemplates = new JCInheritedPropertiesTemplates(
            '<?echo $tabControl->GetName()?>_form',
            '<?=$selfFolderUrl?>iblock_templates.ajax.php?ENTITY_TYPE=E&IBLOCK_ID=<?echo intval($IBLOCK_ID)?>&ENTITY_ID=<?echo intval($ID)?>&bxpublic=y'
        );
        BX.ready(function () {
            setTimeout(function () {
                InheritedPropertiesTemplates.updateInheritedPropertiesTemplates(true);
            }, 1000);
        });
    </script>
<?
$tabControl->EndPrologContent();

$tabControl->BeginEpilogContent();

echo bitrix_sessid_post();
echo GetFilterHiddens("find_"); ?>
    <input type="hidden" name="linked_state" id="linked_state" value="<?
    if ($bLinked) echo 'Y'; else echo 'N'; ?>">
    <input type="hidden" name="Update" value="Y">
    <input type="hidden" name="from" value="<?
    echo htmlspecialcharsbx($from) ?>">
    <input type="hidden" name="WF" value="<?
    echo htmlspecialcharsbx($WF) ?>">
    <input type="hidden" name="return_url" value="<?
    echo htmlspecialcharsbx($return_url) ?>">
<?
if ($ID > 0 && !$bCopy) {
    ?><input type="hidden" name="ID" value="<?
    echo $ID ?>"><?
}
if ($bCopy) {
    ?><input type="hidden" name="copyID" value="<? echo $ID; ?>"><?
} elseif ($copyID > 0) {
    ?><input type="hidden" name="copyID" value="<? echo $copyID; ?>"><?
}

if ($bCatalog)
    CCatalogAdminTools::showFormParams();
?>
    <input type="hidden" name="IBLOCK_SECTION_ID" value="<?
    echo intval($IBLOCK_SECTION_ID) ?>">
    <input type="hidden" name="TMP_ID" value="<?
    echo intval($TMP_ID) ?>">
<?
$tabControl->EndEpilogContent();

$customTabber->SetErrorState($bVarsFromForm);

$arEditLinkParams = array(
    "find_section_section" => $find_section_section
);
if ($bAutocomplete) {
    $arEditLinkParams['lookup'] = $strLookup;
}
if ($adminSidePanelHelper->isPublicFrame()) {
    $arEditLinkParams["IFRAME"] = "Y";
    $arEditLinkParams["IFRAME_TYPE"] = "PUBLIC_FRAME";
}
$tabControl->Begin(array(
    "FORM_ACTION" => $selfFolderUrl . CIBlock::GetAdminElementEditLink($IBLOCK_ID, null, $arEditLinkParams)
));

$tabControl->BeginNextFormTab();
if ($ID > 0 && !$bCopy) {
    $p = CIblockElement::GetByID($ID);
    $pr = $p->ExtractFields("prn_");
} else {
    $pr = array();
}

if ($ID > 0 && !$bCopy) {
    if (Loader::includeModule('crm')) {
        $importProduct = \Bitrix\Crm\Order\Import\Internals\ProductTable::getRow([
            'select' => ['SETTINGS'],
            'filter' => [
                '=PRODUCT_ID' => CIBlockElement::GetRealElement($ID),
            ],
        ]);

        if (!empty($importProduct)) {
            $accountName = !empty($importProduct['SETTINGS']['account_name']) ? $importProduct['SETTINGS']['account_name'] : '';
            $linkToProduct = !empty($importProduct['SETTINGS']['permalink']) ? $importProduct['SETTINGS']['permalink'] : '';

            $tabControl->BeginCustomField('IMPORTED_FROM', GetMessage('IBLOCK_IMPORT_FROM') . ':');
            ?>
            <tr>
                <td width="40%"><?= $tabControl->GetCustomLabelHTML() ?></td>
                <td width="60%">
                    <style>
                        .adm-crm-order-instagram-icon {
                            width: 20px;
                            height: 20px;
                            display: inline-block;
                            vertical-align: middle;
                            margin-right: 5px;
                            background-image: url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%0A%20%20%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M10%2020c5.523%200%2010-4.477%2010-10S15.523%200%2010%200%200%204.477%200%2010s4.477%2010%2010%2010z%22%20fill%3D%22%23E85998%22/%3E%0A%20%20%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M12.027%206.628a.584.584%200%201%201%201.168%200%20.584.584%200%200%201-1.168%200zM7.355%204h4.672a3.219%203.219%200%200%201%203.213%203.213v1.392h-1.168V7.213a2.028%202.028%200%200%200-2.045-2.045H7.355a2.028%202.028%200%200%200-2.044%202.045v4.672c0%201.143.902%202.045%202.044%202.045h3.87v1.168h-3.87a3.22%203.22%200%200%201-3.212-3.213V7.213A3.219%203.219%200%200%201%207.355%204zm-.73%205.549a3.076%203.076%200%200%201%203.066-3.067c1.43%200%202.635.992%202.971%202.32-.45.195-.826.526-1.082.94a1.89%201.89%200%200%200-1.889-2.09%201.89%201.89%200%200%200-1.898%201.897%201.89%201.89%200%200%200%201.898%201.898c.653%200%201.221-.322%201.563-.816-.018.115-.03.232-.03.352v1.215a3.03%203.03%200%200%201-1.533.418%203.076%203.076%200%200%201-3.066-3.067zm6.104%202.696h.607v-.83c0-.152-.03-1.167%201.281-1.167h.924v1.056h-.68c-.134%200-.271.14-.271.243v.694h.951c-.039.532-.117%201.02-.117%201.02h-.838v3.015h-1.25V13.26h-.607v-1.015z%22%20fill%3D%22white%22/%3E%0A%3C/svg%3E%0A);
                            margin-top: -3px;
                            background-repeat: no-repeat;
                        }
                    </style>
                    <span class="adm-crm-order-instagram-icon"></span>
                    <?= $accountName ?>
                    <?= ($linkToProduct ? "<a href=\"{$linkToProduct}\" target=\"_blank\">" . GetMessage('IBLOCK_LINK_TO_MEDIA') . "</a>" : '') ?>
                </td>
            </tr>
            <?
            $tabControl->EndCustomField('IMPORTED_FROM', '');
        }
    }
}


$tabControl->AddCheckBoxField("ACTIVE", GetMessage("IBLOCK_FIELD_ACTIVE") . ":", false, array("Y", "N"), $str_ACTIVE == "Y");

if ($arTranslit["TRANSLITERATION"] == "Y") {
    $tabControl->BeginCustomField("NAME", GetMessage("IBLOCK_FIELD_NAME") . ":", true);
    ?>
    <tr id="tr_NAME">
        <td><?
            echo $tabControl->GetCustomLabelHTML() ?></td>
        <td style="white-space: nowrap;">
            <input type="text" size="50" name="NAME" id="NAME" maxlength="255" value="<?
            echo $str_NAME ?>"><img id="name_link" title="<?
            echo GetMessage("IBEL_E_LINK_TIP") ?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?
            if ($bLinked) echo 'link.gif'; else echo 'unlink.gif'; ?>" onclick="set_linked()"/>
        </td>
    </tr>
    <?
    $tabControl->EndCustomField("NAME",
        '<input type="hidden" name="NAME" id="NAME" value="' . $str_NAME . '">'
    );
} else {
    $tabControl->AddEditField("NAME", GetMessage("IBLOCK_FIELD_NAME") . ":", true, array("size" => 50, "maxlength" => 255), $str_NAME);
}

$tabControl->BeginCustomField("SECTIONS", GetMessage("IBLOCK_SECTION"), $arIBlock["FIELDS"]["IBLOCK_SECTION"]["IS_REQUIRED"] === "Y");
?>
    <tr id="tr_SECTIONS">
        <?
        if ($arIBlock["SECTION_CHOOSER"] != "D" && $arIBlock["SECTION_CHOOSER"] != "P"):?>

            <?
            $l = CIBlockSection::GetTreeList(Array("IBLOCK_ID" => $IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL")); ?>
            <td width="40%" class="adm-detail-valign-top"><?
                echo $tabControl->GetCustomLabelHTML() ?></td>
            <td width="60%">
                <select name="IBLOCK_SECTION[]" size="14" multiple onchange="onSectionChanged()">
                    <option value="0"<?
                    if (is_array($str_IBLOCK_ELEMENT_SECTION) && in_array(0, $str_IBLOCK_ELEMENT_SECTION)) echo " selected" ?>><?
                        echo GetMessage("IBLOCK_UPPER_LEVEL") ?></option>
                    <?
                    while ($ar_l = $l->GetNext()):
                        ?>
                        <option value="<?
                        echo $ar_l["ID"] ?>"<?
                        if (is_array($str_IBLOCK_ELEMENT_SECTION) && in_array($ar_l["ID"], $str_IBLOCK_ELEMENT_SECTION)) echo " selected" ?>><?
                        echo str_repeat(" . ", $ar_l["DEPTH_LEVEL"]) ?><?
                        echo $ar_l["NAME"] ?></option><?
                    endwhile;
                    ?>
                </select>
            </td>

        <? elseif ($arIBlock["SECTION_CHOOSER"] == "D"): ?>
            <td width="40%" class="adm-detail-valign-top"><?
                echo $tabControl->GetCustomLabelHTML() ?></td>
            <td width="60%">
                <table class="internal" id="sections">
                    <?
                    if (is_array($str_IBLOCK_ELEMENT_SECTION)) {
                        $i = 0;
                        foreach ($str_IBLOCK_ELEMENT_SECTION as $section_id) {
                            $rsChain = CIBlockSection::GetNavChain($IBLOCK_ID, $section_id);
                            $strPath = "";
                            while ($arChain = $rsChain->GetNext())
                                $strPath .= $arChain["NAME"] . "&nbsp;/&nbsp;";
                            if (strlen($strPath) > 0) {
                                ?>
                                <tr>
                                <td nowrap><?
                                    echo $strPath ?></td>
                                <td>
                                    <input type="button" value="<?
                                    echo GetMessage("MAIN_DELETE") ?>" OnClick="deleteRow(this)">
                                    <input type="hidden" name="IBLOCK_SECTION[]" value="<?
                                    echo intval($section_id) ?>">
                                </td>
                                </tr><?
                            }
                            $i++;
                        }
                    }
                    ?>
                    <tr>
                        <td>
                            <script type="text/javascript">
                                function deleteRow(button) {
                                    var my_row = button.parentNode.parentNode;
                                    var table = document.getElementById('sections');
                                    if (table) {
                                        for (var i = 0; i < table.rows.length; i++) {
                                            if (table.rows[i] == my_row) {
                                                table.deleteRow(i);
                                                onSectionChanged();
                                            }
                                        }
                                    }
                                }

                                function addPathRow() {
                                    var table = document.getElementById('sections');
                                    if (table) {
                                        var section_id = 0;
                                        var html = '';
                                        var lev = 0;
                                        var oSelect;
                                        while (oSelect = document.getElementById('select_IBLOCK_SECTION_' + lev)) {
                                            if (oSelect.value < 1)
                                                break;
                                            html += oSelect.options[oSelect.selectedIndex].text + '&nbsp;/&nbsp;';
                                            section_id = oSelect.value;
                                            lev++;
                                        }
                                        if (section_id > 0) {
                                            var cnt = table.rows.length;
                                            var oRow = table.insertRow(cnt - 1);

                                            var i = 0;
                                            var oCell = oRow.insertCell(i++);
                                            oCell.innerHTML = html;

                                            var oCell = oRow.insertCell(i++);
                                            oCell.innerHTML =
                                                '<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">' +
                                                '<input type="hidden" name="IBLOCK_SECTION[]" value="' + section_id + '">';
                                            onSectionChanged();
                                        }
                                    }
                                }

                                function find_path(item, value) {
                                    if (item.id == value) {
                                        var a = Array(1);
                                        a[0] = item.id;
                                        return a;
                                    } else {
                                        for (var s in item.children) {
                                            if (ar = find_path(item.children[s], value)) {
                                                var a = Array(1);
                                                a[0] = item.id;
                                                return a.concat(ar);
                                            }
                                        }
                                        return null;
                                    }
                                }

                                function find_children(level, value, item) {
                                    if (level == -1 && item.id == value)
                                        return item;
                                    else {
                                        for (var s in item.children) {
                                            if (ch = find_children(level - 1, value, item.children[s]))
                                                return ch;
                                        }
                                        return null;
                                    }
                                }

                                function change_selection(name_prefix, prop_id, value, level, id) {
                                    var lev = level + 1;
                                    var oSelect;

                                    while (oSelect = document.getElementById(name_prefix + lev)) {
                                        jsSelectUtils.deleteAllOptions(oSelect);
                                        jsSelectUtils.addNewOption(oSelect, '0', '(<?echo GetMessage("MAIN_NO")?>)');
                                        lev++;
                                    }

                                    oSelect = document.getElementById(name_prefix + (level + 1))
                                    if (oSelect && (value != 0 || level == -1)) {
                                        var item = find_children(level, value, window['sectionListsFor' + prop_id]);
                                        for (var s in item.children) {
                                            var obj = item.children[s];
                                            jsSelectUtils.addNewOption(oSelect, obj.id, obj.name, true);
                                        }
                                    }
                                    if (document.getElementById(id))
                                        document.getElementById(id).value = value;
                                }

                                function init_selection(name_prefix, prop_id, value, id) {
                                    var a = find_path(window['sectionListsFor' + prop_id], value);
                                    change_selection(name_prefix, prop_id, 0, -1, id);
                                    for (var i = 1; i < a.length; i++) {
                                        if (oSelect = document.getElementById(name_prefix + (i - 1))) {
                                            for (var j = 0; j < oSelect.length; j++) {
                                                if (oSelect[j].value == a[i]) {
                                                    oSelect[j].selected = true;
                                                    break;
                                                }
                                            }
                                        }
                                        change_selection(name_prefix, prop_id, a[i], i - 1, id);
                                    }
                                }

                                var sectionListsFor0 = {id: 0, name: '', children: Array()};

                                <?
                                $rsItems = CIBlockSection::GetTreeList(Array("IBLOCK_ID" => $IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));
                                $depth = 0;
                                $max_depth = 0;
                                $arChain = array();
                                while ($arItem = $rsItems->GetNext()) {
                                    if ($max_depth < $arItem["DEPTH_LEVEL"]) {
                                        $max_depth = $arItem["DEPTH_LEVEL"];
                                    }
                                    if ($depth < $arItem["DEPTH_LEVEL"]) {
                                        $arChain[] = $arItem["ID"];

                                    }
                                    while ($depth > $arItem["DEPTH_LEVEL"]) {
                                        array_pop($arChain);
                                        $depth--;
                                    }
                                    $arChain[count($arChain) - 1] = $arItem["ID"];
                                    echo "sectionListsFor0";
                                    foreach ($arChain as $i)
                                        echo ".children['" . intval($i) . "']";

                                    echo " = { id : " . $arItem["ID"] . ", name : '" . CUtil::JSEscape($arItem["NAME"]) . "', children : Array() };\n";
                                    $depth = $arItem["DEPTH_LEVEL"];
                                }
                                ?>
                            </script>
                            <?
                            for ($i = 0; $i < $max_depth; $i++)
                                echo '<select id="select_IBLOCK_SECTION_' . $i . '" onchange="change_selection(\'select_IBLOCK_SECTION_\',  0, this.value, ' . $i . ', \'IBLOCK_SECTION[n' . $key . ']\')"><option value="0">(' . GetMessage("MAIN_NO") . ')</option></select>&nbsp;';
                            ?>
                            <script type="text/javascript">
                                init_selection('select_IBLOCK_SECTION_', 0, '', 0);
                            </script>
                        </td>
                        <td><input type="button" value="<?
                            echo GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD") ?>" onClick="addPathRow()"></td>
                    </tr>
                </table>
            </td>

        <? else: ?>
            <td width="40%" class="adm-detail-valign-top"><?
                echo $tabControl->GetCustomLabelHTML() ?></td>
            <td width="60%">
                <table id="sections" class="internal">
                    <?
                    if (is_array($str_IBLOCK_ELEMENT_SECTION)) {
                        $i = 0;
                        foreach ($str_IBLOCK_ELEMENT_SECTION as $section_id) {
                            $rsChain = CIBlockSection::GetNavChain($IBLOCK_ID, $section_id);
                            $strPath = "";
                            while ($arChain = $rsChain->GetNext())
                                $strPath .= $arChain["NAME"] . "&nbsp;/&nbsp;";
                            if (strlen($strPath) > 0) {
                                ?>
                                <tr>
                                <td><?
                                    echo $strPath ?></td>
                                <td>
                                    <input type="button" value="<?
                                    echo GetMessage("MAIN_DELETE") ?>" OnClick="deleteRow(this)">
                                    <input type="hidden" name="IBLOCK_SECTION[]" value="<?
                                    echo intval($section_id) ?>">
                                </td>
                                </tr><?
                            }
                            $i++;
                        }
                    }
                    ?>
                </table>
                <script type="text/javascript">
                    function deleteRow(button) {
                        var my_row = button.parentNode.parentNode;
                        var table = document.getElementById('sections');
                        if (table) {
                            for (var i = 0; i < table.rows.length; i++) {
                                if (table.rows[i] == my_row) {
                                    table.deleteRow(i);
                                    onSectionChanged();
                                }
                            }
                        }
                    }

                    function InS<?echo md5("input_IBLOCK_SECTION")?>(section_id, html) {
                        var table = document.getElementById('sections');
                        if (table) {
                            if (section_id > 0 && html) {
                                var cnt = table.rows.length;
                                var oRow = table.insertRow(cnt - 1);

                                var i = 0;
                                var oCell = oRow.insertCell(i++);
                                oCell.innerHTML = html;

                                var oCell = oRow.insertCell(i++);
                                oCell.innerHTML =
                                    '<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">' +
                                    '<input type="hidden" name="IBLOCK_SECTION[]" value="' + section_id + '">';
                                onSectionChanged();
                            }
                        }
                    }
                </script>
                <input name="input_IBLOCK_SECTION" id="input_IBLOCK_SECTION" type="hidden">
                <input type="button" value="<?
                echo GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD") ?>..."
                       onClick="jsUtils.OpenWindow('<?= $selfFolderUrl ?>iblock_section_search.php?lang=<?
                       echo LANGUAGE_ID ?>&amp;IBLOCK_ID=<?
                       echo $IBLOCK_ID ?>&amp;n=input_IBLOCK_SECTION&amp;m=y&amp;iblockfix=y&amp;tableId=iblocksection-<?= $IBLOCK_ID; ?>', 900, 700);">
            </td>
        <? endif; ?>
    </tr>
    <input type="hidden" name="IBLOCK_SECTION[]" value="">
    <script type="text/javascript">
        function onSectionChanged() {
            <?
            $additionalParams = '';
            if ($bCatalog) {
                $catalogParams = array('TMP_ID' => $TMP_ID);
                CCatalogAdminTools::addTabParams($catalogParams);
                if (!empty($catalogParams)) {
                    foreach ($catalogParams as $name => $value) {
                        if ($additionalParams != '')
                            $additionalParams .= '&';
                        $additionalParams .= urlencode($name) . "=" . urlencode($value);
                    }
                    unset($name, $value);
                }
                unset($catalogParams);
            }
            ?>
            var form = BX('<?echo CUtil::JSEscape($tabControl->GetFormName())?>'),
                url = '<?echo CUtil::JSEscape($APPLICATION->GetCurPageParam($additionalParams))?>',
                selectedTab = BX(s = '<?echo CUtil::JSEscape("form_element_" . $IBLOCK_ID . "_active_tab")?>'),
                groupField;

            if (selectedTab && selectedTab.value) {
                url += '&<?echo CUtil::JSEscape("form_element_" . $IBLOCK_ID . "_active_tab")?>=' + selectedTab.value;
            }
            <?if($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT")):?>
            groupField = new JCIBlockGroupField(form, 'tr_IBLOCK_ELEMENT_PROPERTY', url);
            groupField.reload();
            <?endif;
            if($arIBlock["FIELDS"]["IBLOCK_SECTION"]["DEFAULT_VALUE"]["KEEP_IBLOCK_SECTION_ID"] === "Y"):?>
            InheritedPropertiesTemplates.updateInheritedPropertiesValues(false, true);
            <?endif?>
        }
    </script>
<?
$hidden = "";
if (is_array($str_IBLOCK_ELEMENT_SECTION))
    foreach ($str_IBLOCK_ELEMENT_SECTION as $section_id)
        $hidden .= '<input type="hidden" name="IBLOCK_SECTION[]" value="' . intval($section_id) . '">';
$tabControl->EndCustomField("SECTIONS", $hidden);
if (
    $arShowTabs['sections']
    && $arIBlock["FIELDS"]["IBLOCK_SECTION"]["DEFAULT_VALUE"]["KEEP_IBLOCK_SECTION_ID"] === "Y"
) {
    $arDropdown = array();
    if ($str_IBLOCK_ELEMENT_SECTION) {
        $sectionList = CIBlockSection::GetList(
            array("left_margin" => "asc"),
            array("=ID" => $str_IBLOCK_ELEMENT_SECTION),
            false,
            array("ID", "NAME")
        );
        while ($section = $sectionList->Fetch())
            $arDropdown[$section["ID"]] = htmlspecialcharsEx($section["NAME"]);
    }
    $tabControl->BeginCustomField("IBLOCK_ELEMENT_SECTION_ID", GetMessage("IBEL_E_MAIN_IBLOCK_SECTION_ID") . ":", false);
    ?>
    <tr id="tr_IBLOCK_ELEMENT_SECTION_ID">
        <td class="adm-detail-valign-top"><?
            echo $tabControl->GetCustomLabelHTML() ?></td>
        <td>
            <div id="RESULT_IBLOCK_ELEMENT_SECTION_ID">
                <select name="IBLOCK_ELEMENT_SECTION_ID" id="IBLOCK_ELEMENT_SECTION_ID"
                        onchange="InheritedPropertiesTemplates.updateInheritedPropertiesValues(false, true)">
                    <?
                    foreach ($arDropdown as $key => $val):?>
                        <option value="<?
                        echo $key ?>" <?
                        if ($str_IBLOCK_SECTION_ID == $key) echo 'selected' ?>><?
                            echo $val ?></option>
                    <? endforeach ?>
                </select>
            </div>
            <script type="text/javascript">
                window.ipropTemplates[window.ipropTemplates.length] = {
                    "ID": "IBLOCK_ELEMENT_SECTION_ID",
                    "INPUT_ID": "IBLOCK_ELEMENT_SECTION_ID",
                    "RESULT_ID": "RESULT_IBLOCK_ELEMENT_SECTION_ID",
                    "TEMPLATE": ""
                };
                window.ipropTemplates[window.ipropTemplates.length] = {
                    "ID": "CODE",
                    "INPUT_ID": "CODE",
                    "RESULT_ID": "",
                    "TEMPLATE": ""
                };
                <?
                if (COption::GetOptionString('iblock', 'show_xml_id') == 'Y')
                {
                ?>
                window.ipropTemplates[window.ipropTemplates.length] = {
                    "ID": "XML_ID",
                    "INPUT_ID": "XML_ID",
                    "RESULT_ID": "",
                    "TEMPLATE": ""
                };
                <?
                }
                ?>
            </script>
        </td>
    </tr>
    <?
    $tabControl->EndCustomField("IBLOCK_ELEMENT_SECTION_ID",
        '<input type="hidden" name="IBLOCK_ELEMENT_SECTION_ID" id="IBLOCK_ELEMENT_SECTION_ID" value="' . $str_IBLOCK_SECTION_ID . '">'
    );
}

if (COption::GetOptionString("iblock", "show_xml_id", "N") == "Y") {
    if ($bCopy || $ID == 0) {
        $tabControl->BeginCustomField("XML_ID", GetMessage("IBLOCK_FIELD_XML_ID") . ":", $arIBlock["FIELDS"]["XML_ID"]["IS_REQUIRED"] === "Y");
        ?>
        <tr id="tr_XML_ID">
        <td><span id="hint_XML_ID"></span>
            <script type="text/javascript">
                BX.hint_replace(BX('hint_XML_ID'), '<?=CUtil::JSEscape(htmlspecialcharsbx(GetMessage('IBLOCK_FIELD_HINT_XML_ID')))?>');
            </script> <?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td>
            <input type="text" name="XML_ID" id="XML_ID" size="20" maxlength="255" value="<?= $str_XML_ID; ?>">
        </td>
        </tr><?
        $tabControl->EndCustomField("XML_ID", '<input type="hidden" name="XML_ID" id="XML_ID" value="' . $str_XML_ID . '">');
    } else {
        $tabControl->AddEditField("XML_ID", GetMessage("IBLOCK_FIELD_XML_ID") . ":", $arIBlock["FIELDS"]["XML_ID"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255, "id" => "XML_ID"), $str_XML_ID);
    }
}

$tabControl->AddEditField("SORT", GetMessage("IBLOCK_FIELD_SORT") . ":", $arIBlock["FIELDS"]["SORT"]["IS_REQUIRED"] === "Y", array("size" => 7, "maxlength" => 10), $str_SORT);
if (!empty($PROP)):
    if ($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT")) {
        $arPropLinks = array("IBLOCK_ELEMENT_PROP_VALUE");
        if (is_array($str_IBLOCK_ELEMENT_SECTION) && !empty($str_IBLOCK_ELEMENT_SECTION)) {
            foreach ($str_IBLOCK_ELEMENT_SECTION as $section_id) {
                foreach (CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $section_id) as $PID => $arLink)
                    $arPropLinks[$PID] = "PROPERTY_" . $PID;
            }
        } else {
            foreach (CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, 0) as $PID => $arLink)
                $arPropLinks[$PID] = "PROPERTY_" . $PID;
        }
        $tabControl->AddFieldGroup("IBLOCK_ELEMENT_PROPERTY", GetMessage("IBLOCK_ELEMENT_PROP_VALUE"), $arPropLinks, $bPropertyAjax);
    }

    $tabControl->AddSection("IBLOCK_ELEMENT_PROP_VALUE", GetMessage("IBLOCK_ELEMENT_PROP_VALUE"));
    $tabControl->BeginCustomField("PROPERTY_" . $typeField["ID"], $typeField["NAME"], $typeField["IS_REQUIRED"] === "Y");
    ?>
    <tr id="tr_PROPERTY_<?
    echo $typeField["ID"]; ?>">
        <td class="adm-detail-valign-top" width="40%"><?
            if ($typeField["HINT"] != ""):
                ?><span id="hint_<?
            echo $typeField["ID"]; ?>"></span>
                <script type="text/javascript">BX.hint_replace(BX('hint_<?echo $typeField["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($typeField["HINT"]))?>');</script>&nbsp;<?
            endif; ?><?
            echo $tabControl->GetCustomLabelHTML(); ?>:
        </td>
        <td width="60%"><?
            _ShowPropertyField('PROP[' . $typeField["ID"] . ']', $typeField, $typeField["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID <= 0) && (!$bPropertyAjax)), $bVarsFromForm || $bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy); ?></td>
    </tr>
    <?
    $hidden = "";
    $values = $typeField["~VALUE"];
    $start = 1;
    foreach ($values as $key => $val) {
        if ($bCopy) {
            $key = "n" . $start;
            $start++;
        }

        if (is_array($val) && array_key_exists("VALUE", $val)) {
            $hidden .= _ShowHiddenValue('PROP[' . $typeField["ID"] . '][' . $key . '][VALUE]', $val["VALUE"]);
            $hidden .= _ShowHiddenValue('PROP[' . $typeField["ID"] . '][' . $key . '][DESCRIPTION]', $val["DESCRIPTION"]);
        } else {
            $hidden .= _ShowHiddenValue('PROP[' . $typeField["ID"] . '][' . $key . '][VALUE]', $val);
            $hidden .= _ShowHiddenValue('PROP[' . $typeField["ID"] . '][' . $key . '][DESCRIPTION]', "");
        }
    }
    $tabControl->EndCustomField("PROPERTY_" . $typeField["ID"], $hidden);
    foreach ($PROP as $prop_code => $prop_fields):

        $prop_values = $prop_fields["VALUE"];
        $tabControl->BeginCustomField("PROPERTY_" . $prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"] === "Y");
        ?>
        <tr id="tr_PROPERTY_<?
        echo $prop_fields["ID"]; ?>"<?
        if ($prop_fields["PROPERTY_TYPE"] == "F"):?> class="adm-detail-file-row"<? endif ?>>
            <td class="adm-detail-valign-top" width="40%"><?
                if ($prop_fields["HINT"] != ""):
                    ?><span id="hint_<?
                echo $prop_fields["ID"]; ?>"></span>
                    <script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
                endif; ?><?
                echo $tabControl->GetCustomLabelHTML(); ?>:
            </td>
            <td width="60%"><?
                _ShowPropertyField('PROP[' . $prop_fields["ID"] . ']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID <= 0) && (!$bPropertyAjax)), $bVarsFromForm || $bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy); ?></td>
        </tr>
        <?php
        $hidden = "";
        if (!is_array($prop_fields["~VALUE"]))
            $values = Array();
        else
            $values = $prop_fields["~VALUE"];
        $start = 1;
        foreach ($values as $key => $val) {
            if ($bCopy) {
                $key = "n" . $start;
                $start++;
            }

            if (is_array($val) && array_key_exists("VALUE", $val)) {
                $hidden .= _ShowHiddenValue('PROP[' . $prop_fields["ID"] . '][' . $key . '][VALUE]', $val["VALUE"]);
                $hidden .= _ShowHiddenValue('PROP[' . $prop_fields["ID"] . '][' . $key . '][DESCRIPTION]', $val["DESCRIPTION"]);
            } else {
                $hidden .= _ShowHiddenValue('PROP[' . $prop_fields["ID"] . '][' . $key . '][VALUE]', $val);
                $hidden .= _ShowHiddenValue('PROP[' . $prop_fields["ID"] . '][' . $key . '][DESCRIPTION]', "");
            }
        }
        $tabControl->EndCustomField("PROPERTY_" . $prop_fields["ID"], $hidden);
    endforeach;
endif;


$bDisabled =
    ($view == "Y")
    || ($bWorkflow && $prn_LOCK_STATUS == "red")
    || (
        (($ID <= 0) || $bCopy)
        && !CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, $MENU_SECTION_ID, "section_element_bind")
    )
    || (
        (($ID > 0) && !$bCopy)
        && !CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $ID, "element_edit")
    )
    || (
        $bBizproc
        && !$canWrite
    );

if ($adminSidePanelHelper->isSidePanelFrame()):
    $tabControl->Buttons(array("disabled" => $bDisabled));
elseif (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1):
    ob_start();
    ?>
    <input <?
    if ($bDisabled) echo "disabled"; ?> type="submit" class="adm-btn-save" name="save" id="save" value="<?
    echo GetMessage("IBLOCK_EL_SAVE") ?>">
    <? if (!$bAutocomplete) {
    ?><input <?
    if ($bDisabled) echo "disabled"; ?> type="submit" class="button" name="apply" id="apply" value="<?
    echo GetMessage('IBLOCK_APPLY') ?>"><?
}
    ?>
    <input <?
    if ($bDisabled) echo "disabled"; ?> type="submit" class="button" name="dontsave" id="dontsave" value="<?
    echo GetMessage("IBLOCK_EL_CANC") ?>">
    <? if (!$bAutocomplete) {
    ?><input <?
    if ($bDisabled) echo "disabled"; ?> type="submit" class="adm-btn-add" name="save_and_add" id="save_and_add"
                                        value="<?
                                        echo GetMessage("IBLOCK_EL_SAVE_AND_ADD") ?>"><?
}
    $buttons_add_html = ob_get_contents();
    ob_end_clean();
    $tabControl->Buttons(false, $buttons_add_html);
elseif (!$bPropertyAjax && (!isset($_REQUEST['nobuttons']) || $_REQUEST['nobuttons'] !== "Y")):

    $wfClose = "{
		title: '" . CUtil::JSEscape(GetMessage("IBLOCK_EL_CANC")) . "',
		name: 'dontsave',
		id: 'dontsave',
		action: function () {
			var FORM = this.parentWindow.GetForm();
			FORM.appendChild(BX.create('INPUT', {
				props: {
					type: 'hidden',
					name: this.name,
					value: 'Y'
				}
			}));
			this.disableUntilError();
			this.parentWindow.Submit();
		}
	}";
    $save_and_add = "{
		title: '" . CUtil::JSEscape(GetMessage("IBLOCK_EL_SAVE_AND_ADD")) . "',
		name: 'save_and_add',
		id: 'save_and_add',
		className: 'adm-btn-add',
		action: function () {
			var FORM = this.parentWindow.GetForm();
			FORM.appendChild(BX.create('INPUT', {
				props: {
					type: 'hidden',
					name: 'save_and_add',
					value: 'Y'
				}
			}));

			this.parentWindow.hideNotify();
			this.disableUntilError();
			this.parentWindow.Submit();
		}
	}";
    $cancel = "{
		title: '" . CUtil::JSEscape(GetMessage("IBLOCK_EL_CANC")) . "',
		name: 'cancel',
		id: 'cancel',
		action: function () {
			BX.WindowManager.Get().Close();
			if(window.reloadAfterClose)
				top.BX.reload(true);
		}
	}";
    $editInPanelParams = array(
        'WF' => ($WF == 'Y' ? 'Y' : null),
        'find_section_section' => $find_section_section,
        'menu' => null
    );
    if (!empty($arMainCatalog))
        $editInPanelParams = CCatalogAdminTools::getFormParams($editInPanelParams);
    $edit_in_panel = "{
		title: '" . CUtil::JSEscape(GetMessage('IBLOCK_EL_EDIT_IN_PANEL')) . "',
		name: 'edit_in_panel',
		id: 'edit_in_panel',
		className: 'adm-btn-add',
		action: function () {
			location.href = '" . $selfFolderUrl . CIBlock::GetAdminElementEditLink(
            $IBLOCK_ID,
            $ID,
            $editInPanelParams
        ) . "';
		}
	}";
    unset($editInPanelParams);
    $tabControl->ButtonsPublic(array(
        '.btnSave',
        ($ID > 0 && $bWorkflow ? $wfClose : $cancel),
        $edit_in_panel,
        $save_and_add
    ));
endif;

$tabControl->Show();
if (
    (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1)
    && CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, "iblock_edit")
    && !$bAutocomplete && !$adminSidePanelHelper->isSidePanel()
) {

    echo
    BeginNote(),
    GetMessage("IBEL_E_IBLOCK_MANAGE_HINT"),
        ' <a href="' . $selfFolderUrl . 'iblock_edit.php?type=' . htmlspecialcharsbx($type) . '&amp;lang=' . LANGUAGE_ID . '&amp;ID=' . $IBLOCK_ID . '&amp;admin=Y&amp;return_url=' . urlencode($selfFolderUrl . CIBlock::GetAdminElementEditLink($IBLOCK_ID, $ID, array("WF" => ($WF == "Y" ? "Y" : null), "find_section_section" => $find_section_section, "IBLOCK_SECTION_ID" => $find_section_section, "return_url" => (strlen($return_url) > 0 ? $return_url : null)))) . '">',
    GetMessage("IBEL_E_IBLOCK_MANAGE_HINT_HREF"),
    '</a>',
    EndNote();
}

//////////////////////////
//END of the custom form
//////////////////////////
