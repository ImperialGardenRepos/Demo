<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['FORM_HEADER'] = str_replace('<form ', '<form enctype="multipart/form-data" onsubmit="return false;" class="form--quote form-validate js-callback-form" ', $arResult['FORM_HEADER']);