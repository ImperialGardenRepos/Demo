<?php

namespace ig\Helpers;

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;

class Url
{
    /**
     * Method removes params and /page-{page} part from request url and returns it.
     *
     * @param array $allowedParams
     * @param bool $removePage
     * @return string
     * @throws SystemException
     */
    public static function getUrlWithoutParams(bool $removePage = true, array $allowedParams = []): string
    {
        $requestInstance = Application::getInstance()->getContext()->getRequest();
        $requestUrl = explode('?', $requestInstance->getRequestUri());
        $requestUrl = array_shift($requestUrl);
        if ($removePage === true) {
            $requestUrl = preg_replace('/\/page-\d{1,}/', '', $requestUrl);
        }

        if ($allowedParams !== []) {
            $params = [];
            $requestArray = $requestInstance->toArray();
            foreach ($requestArray as $key => $value) {
                if (in_array($key, $allowedParams, true)) {
                    $params[] = "{$key}={$value}";
                }
            }
            if ($params !== []) {
                $requestUrl .= '?' . implode('&', $params);
            }
        }
        return $requestUrl;
    }

    /**
     * Method returns current host.
     *
     * @param bool $addSlash
     * @param bool $forceSecure
     * @return string
     * @throws SystemException
     */
    public static function getHost(bool $addSlash = false, bool $forceSecure = false): string
    {
        $requestInstance = Application::getInstance()->getContext()->getRequest();
        $schema = 'https://';
        if ($forceSecure === false) {
            $schema = $requestInstance->isHttps() === true ? 'https://' : 'http://';
        }
        $host = $requestInstance->getHttpHost();
        if ($addSlash === true) {
            $host .= '/';
        }
        return $schema . $host;
    }
}