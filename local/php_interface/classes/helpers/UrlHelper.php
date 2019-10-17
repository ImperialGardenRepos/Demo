<?php

namespace ig\Helpers;

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;

class UrlHelper
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

    /**
     * @param string ...$parts
     * @return string
     */
    public static function buildFromParts(string ...$parts): string
    {
        $cleanArray = [];
        foreach ($parts as $urlSubstring) {
            $urlSubstring = trim($urlSubstring, "/ \t\n\r\0\x0B");
            $urlSubstring = preg_replace('/(\/+)/', '/', $urlSubstring);
            if ($urlSubstring === '') {
                continue;
            }
            $urlSubstring = explode('/', $urlSubstring);
            $cleanArray[] = $urlSubstring;
        }
        $cleanArray = array_merge(...$cleanArray);
        $cleanString = implode('/', $cleanArray);
        return "/{$cleanString}/";
    }

    /**
     * @param string $base
     * @param bool $removeBase
     * @return array
     * @throws SystemException
     */
    public static function getUrlArrayRaw(string $base = '/', bool $removeBase = true): array
    {
        $url = static::getUrlWithoutParams();
        $url = trim($url, "/ \t\n\r \v");
        if ($removeBase === true) {
            $base = trim($base, "/ \t\n\r \v");
        }

        if ($base === $url) {
            return [];
        }

        if ($base !== '') {
            $base .= '/';
            $base = preg_quote($base, '/');
            $url = preg_replace("/^{$base}/", '', $url);
        }
        if ($url === '') {
            return [];
        }
        return explode('/', $url);
    }


    /**
     * @param string $param
     * @return string|null
     * @throws SystemException
     */
    public static function getUrlParam(string $param): ?string
    {
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        if (array_key_exists($param, $request)) {
            return (string)$request[$param];
        }
        return null;
    }

    /**
     * @param string $base
     * @param array $params
     * @param bool $skipEmpty
     * @return string
     */
    public static function buildGetQuery(string $base, array $params, bool $skipEmpty = true): string
    {
        $queryArray = [];
        foreach ($params as $key => $value) {
            if ($skipEmpty === true && (string)$value === '') {
                continue;
            }
            $queryArray[] = "{$key}={$value}";
        }
        if ($queryArray === []) {
            return $base;
        }
        $queryString = implode('&', $queryArray);
        return "{$base}?{$queryString}";
    }

    /**
     * @param string $paginationId
     * @return int
     * @throws SystemException
     */
    public static function getPageNum(string $paginationId): int
    {
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        if(isset($request[$paginationId])) {
            return (int)$request[$paginationId];
        }
        return 1;
    }
}