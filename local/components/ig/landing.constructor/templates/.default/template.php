<?php
/**
 * @var $arResult
 */

//ToDo:: die without prolog
//ToDo:: edit and add item
foreach ($arResult['BLOCKS'] as $block) {
    $template = __DIR__ . '/_partials/' .strtolower($block['TEMPLATE']) . '.php';
    if(!file_exists($template)) {
        //ToDo:: throw 500
    }
    include $template;
}