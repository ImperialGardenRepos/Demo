<?php


namespace ig\Seo;


use Bitrix\Main\Application;
use CHTTP;

class Status
{
    public static function setFinal404Error(): void
    {
        if (defined('STATUS_404') && STATUS_404 === true) {
            global $APPLICATION;
            CHTTP::SetStatus('404 Not Found');
            defined('ERROR_404') or define('ERROR_404', 'Y');

            if ($APPLICATION->RestartWorkarea()) {
                require Application::getDocumentRoot() . '/404.php';
                die();
            }
            return;
        }
    }
}