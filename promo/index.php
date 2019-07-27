<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>

<?php
$APPLICATION->IncludeComponent(
    'ig:landing.constructor',
    '',
    [
        'IBLOCK_ID' => 34
    ],
    false
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>