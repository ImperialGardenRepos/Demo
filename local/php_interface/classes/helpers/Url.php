<?php

namespace ig\Helpers;

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;

class Url
{
    /**
     * Method removes params and /page-{page} part from request url and returns it
     * @return string
     * @throws SystemException
     */
    public static function getUrlWithoutParams(): string
    {
        $request = Application::getInstance()->getContext()->getRequest()->getRequestUri();
        $request = explode('?', $request);
        $request = array_shift($request);
        $request = preg_replace('/\/page-\d{1,}/','', $request);
        return $request;
    }
}