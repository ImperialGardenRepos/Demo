<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var $arResult
 */

//ToDo:: edit and add item
foreach ($arResult['BLOCKS'] as $block) {
    $template = __DIR__ . '/_partials/_' . strtolower($block['TEMPLATE']) . '.php';
    if (!file_exists($template)) {
        //ToDo:: throw 500
    }
    include $template;
}