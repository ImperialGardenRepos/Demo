<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/search/prolog.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/search/admin/search_reindex.php");

require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/admin/CCatalogSphinxIndexer.class.php');

/** @global CMain $APPLICATION */
global $APPLICATION;
/** @var CAdminMessage $message */

$POST_RIGHT = $APPLICATION->GetGroupRight("search");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$res = false;

$obIndexer = new \ig\CCatalogSphinxIndexer();

$bFull = !isset($_REQUEST["Full"]) || $_REQUEST["Full"] != "N";
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["Reindex"] == "Y") {
    CUtil::JSPostUnescape();
    @set_time_limit(0);

    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_js.php");

    if (array_key_exists("NS", $_POST) && is_array($_POST["NS"])) {
        $NS = $_POST["NS"];
    } else {
        $NS = [];
        $max_execution_items = intval($max_execution_items);
        COption::SetOptionString("search_sphinx", "max_execution_items", $max_execution_items);
        if ($max_execution_items <= 0)
            $max_execution_items = '';
    }

    //Check for expired session and set clear flag
    //in order to not accidetialy clear search index
    if (
        $bFull
        && $NS["CLEAR"] != "Y"
        && !check_bitrix_sessid()
    ) {
        $NS["CLEAR"] = "Y";
    }

    $res = $obIndexer->reindexCatalog(
        $bFull,
        COption::GetOptionInt("search_sphinx", "max_execution_items"),
        $NS
    );

    if (is_array($res)):
        $jsNS = CUtil::PhpToJSObject(["NS" => $res]);
        $urlNS = "";
        foreach ($res as $key => $value)
            $urlNS .= "&" . urlencode("NS[" . $key . "]") . "=" . urlencode($value);
        if ($bFull)
            $urlNS .= "&Full=Y";

        if ($res["ENTITY"] == 'IE')
            $strEntityName = ' элемент каталога ';

        $strMoreInfo = 'Последний проиндексированный элемент: ' . $strEntityName . ', ID=' . intval($res["ID"]) . '.<br><br>';

        CAdminMessage::ShowMessage([
            "MESSAGE" => GetMessage("SEARCH_REINDEX_IN_PROGRESS"),
            "DETAILS" => GetMessage("SEARCH_REINDEX_TOTAL") . " <b>" . $res["CNT"] . "</b><br>
				" . $strMoreInfo . "
				<a id=\"continue_href\" onclick=\"savedNS=" . $jsNS . "; ContinueReindex(); return false;\" href=\"" . htmlspecialcharsbx("search_sphinx_reindex.php?Continue=Y&lang=" . urlencode(LANGUAGE_ID) . $urlNS) . "\">" . GetMessage("SEARCH_REINDEX_NEXT_STEP") . "</a>",
            "HTML" => true,
            "TYPE" => "PROGRESS",
        ]);
        ?>
        <script>
            CloseWaitWindow();
            DoNext(<?echo $jsNS?>);
        </script>
    <?
    else:
        CAdminMessage::ShowMessage([
            "MESSAGE" => GetMessage("SEARCH_REINDEX_COMPLETE"),
            "DETAILS" => "Проиндексировано элементов: <b>" . $res . "</b>",
            "HTML" => true,
            "TYPE" => "OK",
        ]);
        ?>
        <script>
            CloseWaitWindow();
            EndReindex();
            var search_message = BX('search_message');
            if (search_message)
                search_message.style.display = 'none';
        </script>
    <?endif;
    require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_admin_js.php");
} else {

    $APPLICATION->SetTitle(GetMessage('Переиндексация каталога sphinx'));

    $aTabs = [
        ["DIV" => "edit1", "TAB" => GetMessage("SEARCH_REINDEX_TAB"), "ICON" => "main_user_edit", "TITLE" => 'Переиндексация каталога sphinx'],
    ];
    $tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

    if (is_object($message))
        echo '<div id="search_message">', $message->Show(), '</div>';
    ?>
    <script language="JavaScript">
        var savedNS;
        var stop;
        var interval = 0;

        function StartReindex() {
            stop = false;
            document.getElementById('reindex_result_div').innerHTML = '';
            document.getElementById('stop_button').disabled = false;
            document.getElementById('start_button').disabled = true;
            document.getElementById('continue_button').disabled = true;
            DoNext();
        }

        function DoNext(NS) {
            var queryString = 'Reindex=Y'
                + '&lang=<?echo htmlspecialcharsbx(LANG)?>';

            if (!NS) {
                interval = document.getElementById('max_execution_items').value;
                queryString += '&<?echo bitrix_sessid_get()?>'
                queryString += '&max_execution_items=' + interval;
            }

            if (document.getElementById('Full').checked) {
                queryString += '&Full=Y';
            } else {
                queryString += '&Full=N';
            }

            savedNS = NS;

            if (!stop) {
                ShowWaitWindow();
                BX.ajax.post(
                    'catalog_sphinx_reindex.php?' + queryString,
                    NS,
                    function (result) {
                        document.getElementById('reindex_result_div').innerHTML = result;
                        var href = document.getElementById('continue_href');
                        if (!href) {
                            CloseWaitWindow();
                            StopReindex();
                        }
                    }
                );
            }

            return false;
        }

        function StopReindex() {
            stop = true;
            document.getElementById('stop_button').disabled = true;
            document.getElementById('start_button').disabled = false;
            document.getElementById('continue_button').disabled = false;
        }

        function ContinueReindex() {
            stop = false;
            document.getElementById('stop_button').disabled = false;
            document.getElementById('start_button').disabled = true;
            document.getElementById('continue_button').disabled = true;
            DoNext(savedNS);
        }

        function EndReindex() {
            stop = true;
            document.getElementById('stop_button').disabled = true;
            document.getElementById('start_button').disabled = false;
            document.getElementById('continue_button').disabled = true;
        }
    </script>

    <div id="reindex_result_div" style="margin:0px"></div>

    <script>
        function Full_OnClick(full_checked) {
            if (full_checked)
                alert('Индекс будет полностью очищенен и корректный поиск будет возможен только после завершения полной индексации');
        }
    </script>
    <form method="POST" action="<?
    echo $APPLICATION->GetCurPage() ?>?lang=<?
    echo htmlspecialcharsbx(LANG) ?>" name="fs1">
        <?
        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>
        <tr>
            <td width="40%">Очистить индекс</td>
            <td width="60%"><input type="checkbox" name="Full" id="Full" value="N" OnClick="Full_OnClick(this.checked)">
            </td>
        </tr>
        <tr>
            <td><?
                echo GetMessage("SEARCH_REINDEX_STEP") ?></td>
            <td><input type="text" name="max_execution_items" id="max_execution_items" size="3" value="500"> элементов
            </td>
        </tr>

        <?
        $tabControl->Buttons();
        ?>
        <input type="button" id="start_button" value="<?
        echo GetMessage("SEARCH_REINDEX_REINDEX_BUTTON") ?>" OnClick="StartReindex();" class="adm-btn-save">
        <input type="button" id="stop_button" value="<?= GetMessage("SEARCH_REINDEX_STOP") ?>" OnClick="StopReindex();"
               disabled>
        <input type="button" id="continue_button" value="<?= GetMessage("SEARCH_REINDEX_CONTINUE") ?>"
               OnClick="ContinueReindex();" disabled>
        <?
        $tabControl->End();
        ?>
    </form>
    <?
    if ($Continue == "Y"):?>
        <script language="JavaScript">
            savedNS = <?echo CUtil::PhpToJSObject(["NS" => $_GET["NS"]]);?>;
            <?if($_GET["Full"] == "Y"):?>
            document.getElementById('Full').checked = false;
            Full_OnClick(false);
            <?endif;?>
            ContinueReindex();
        </script>
    <?endif ?>

    <?
    //echo BeginNote(),'Заметки на полях',EndNote();

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}
?>
