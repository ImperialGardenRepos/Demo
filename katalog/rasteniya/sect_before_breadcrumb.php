<?php

use Bitrix\Main\Application;

$requestArray = Application::getInstance()->getContext()->getRequest()->toArray();

$APPLICATION->IncludeComponent(
    'ig:catalog.filter',
    '',
    [
        'IS_AJAX' => $requestArray['IS_AJAX'] === 'Y' ? 'Y' : 'N',
    ]
);