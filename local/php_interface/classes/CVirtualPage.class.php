<?php

namespace ig;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\IO\File;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class CVirtualPage extends CHighload
{
    public static $intHLID = 0;

    /**
     *
     */
    public static function setHLID(): void
    {
        static::$intHLID = CHighload::getHighloadBlockIDByName('VirtualPages');
    }

    /**
     * @param array $arData
     */
    public static function setMeta(array $arData): void
    {
        global $APPLICATION;

        if (isset($arData['UF_TITLE'])) {
            $APPLICATION->SetPageProperty('title', $arData['UF_TITLE']);
        }

        if (isset($arData['UF_DESCRIPTION'])) {
            $APPLICATION->SetPageProperty('description', $arData['UF_DESCRIPTION']);
        }

        if (isset($arData['UF_KEYWORDS'])) {
            $APPLICATION->SetPageProperty('keywords', $arData['UF_KEYWORDS']);
        }
    }

    /**
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function checkVirtualPage(): void
    {

        if (!Context::getCurrent()->getRequest()->isAdminSection()) {
            $strUrl = Context::getCurrent()->getRequest()->getRequestedPageDirectory() . '/';

            $arVirtualPage = static::getFirst(false, ['UF_ACTIVE' => 1, '=UF_URL' => $strUrl]);

            if ($arVirtualPage['ID'] > 0) {
                CRegistry::add('VIRT_PAGE', $arVirtualPage);

                if (!empty($arVirtualPage['UF_PARAMS'])) {
                    parse_str($arVirtualPage['UF_PARAMS'], $arGetParams);
                    Context::getCurrent()->getRequest()->set($arGetParams);

                    $_REQUEST = array_merge($_REQUEST, $arGetParams);
                    $_GET = array_merge($_GET, $arGetParams);
                }

                if ((string)$arVirtualPage['UF_REAL_PAGE'] !== '') {
                    $strDocRoot = Context::getCurrent()->getServer()->getDocumentRoot();
                    $strPhysPage = $strDocRoot . $arVirtualPage['UF_REAL_PAGE'];
                    if (File::isFileExists($strPhysPage)) {
                        require_once $strDocRoot . '/bitrix/modules/main/include/prolog_after.php';
                        static::setMeta($arVirtualPage);

                        /** @noinspection PhpIncludeInspection */
                        require $strPhysPage;
                        die();
                    }
                }

                static::setMeta($arVirtualPage);
            }
        }
    }
}