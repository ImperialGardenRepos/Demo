<?php

namespace ig\Base;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CHTTP;
use ig\Helpers\DBG;
use ig\Highload\FilterAlias;
use ig\Highload\VirtualPage;

class Router
{
    private const CATALOG_BASE_URL = '/katalog/rasteniya/';

    /**
     * @param $folder404
     * @param $arVariables
     * @param int $iBlockId
     * @return bool|string
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function guessCatalogPath($folder404, &$arVariables, $iBlockId = CATALOG_IBLOCK_ID)
    {
        Loader::includeModule('iblock');

        $request = Context::getCurrent()->getRequest();
        if (!isset($arVariables) || !is_array($arVariables)) {
            $arVariables = [];
        }

        $requestURL = $request->getRequestedPageDirectory();


        $folder404 = str_replace("\\", '/', $folder404);
        if ($folder404 !== '/') {
            $folder404 = '/' . trim($folder404, "/ \t\n\r\0\x0B") . '/';
        }

        //SEF base URL must match curent URL (several components on the same page)
        if (strpos($requestURL . '/', $folder404) !== 0) {
            return false;
        }
        $requestArray = $request->toArray();
        if (array_key_exists('filterAlias', $requestArray)) {
            $isFilter = FilterAlias::isUniqueAliasExist($requestArray['filterAlias']);
            if ($isFilter === true) {
                $arVariables['SECTION_ID'] = 0;
                return 'smart_filter';
            }
            CHTTP::setStatus('404 Not Found');
            return false;
        }

        $currentPageUrl = substr($requestURL, strlen($folder404));
        $arPathParts = parse_url($currentPageUrl);
        $arPath = explode('/', $arPathParts['path']);

        /**
         * 404 on 4th level existence
         */
        if (!empty($arPath[3])) {
            CHTTP::setStatus('404 Not Found');
            return false;
        }

        $isPageFirst = isset($arPath[0]) && strpos($arPath[0], 'page-') !== false;
        $isPageSecond = isset($arPath[1]) && strpos($arPath[1], 'page-') !== false;
        $isPageThird = isset($arPath[2]) && strpos($arPath[2], 'page-') !== false;

        /**
         * 404 on repeating pagination
         */
        if (
            ($isPageFirst && $isPageSecond)
            || ($isPageFirst && $isPageThird)
            || ($isPageSecond && $isPageThird)
        ) {
            CHTTP::setStatus('404 Not Found');
            return false;
        }

        /**
         * Zero-level is catalog root section
         */
        if (
            (string)$arPath[0] === ''
            || preg_match('/page-\d+/', $arPath[0]) !== 0
        ) {
            return 'section';
        }

        /**
         * Get 1st section level. On 1st level we have no products.
         * It can be either filter or section.
         * First we check filter SEF.
         */
        $filterUrl = '/' . trim($requestURL, '/') . '/';
        $filterUrl = preg_replace('/(.*\/)page-\d+\//', '$1', $filterUrl);

        $filterModel = VirtualPage::getFirst(false,
            [
                'UF_URL' => $filterUrl,
            ],
            ['UF_PARAMS']
        );

        if ($filterModel !== null && $filterModel['UF_PARAMS'] !== '') {
            return 'smart_filter';
        }

        /**
         * Then section SEF.
         */
        $sectionModel = SectionTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => $iBlockId,
                    'DEPTH_LEVEL' => 1,
                    '=CODE' => $arPath[0]
                ],
                'select' => ['ID', 'CODE']
            ]
        );

        /**
         * If neither filter nor 1st level section exist, return false.
         */
        if ($sectionModel->getSelectedRowsCount() === 0) {
            return false;
        }

        $section = $sectionModel->fetch();
        $arVariables['SECTION_CODE'] = $section['CODE'];
        $arVariables['SECTION_ID'] = $section['ID'];
        $arVariables['SECTION_CODE_PATH'] = $section['CODE'];

        /**
         * If neither 2nd level exists nor 2nd level is pagination, return 1st level section.
         */
        if (
            !isset($arPath[1])
            || (bool)$arPath[1] === false
            || preg_match('/page-\d+/', $arPath[1]) !== 0
        ) {
            return 'section';
        }

        /**
         * If 2nd level exists, it can be section only.
         */
        $sectionCodeFirst = $section['CODE'];
        $sectionModel = SectionTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => $iBlockId,
                    'DEPTH_LEVEL' => 2,
                    'ACTIVE' => 'Y',
                    'IBLOCK_SECTION_ID' => $section['ID'],
                    '=CODE' => $arPath[1]
                ],
                'select' => ['ID', 'CODE']
            ]
        );

        /**
         * If no 2nd level section exists, return false.
         */

        if ($sectionModel->getSelectedRowsCount() === 0) {
            return false;
        }

        $section = $sectionModel->fetch();
        $arVariables['SECTION_CODE'] = $section['CODE'];
        $arVariables['SECTION_ID'] = $section['ID'];
        $arVariables['SECTION_CODE_PATH'] = $sectionCodeFirst . '/' . $section['CODE'];

        if (
            !isset($arPath[2])
            || (bool)$arPath[2] === false
            || preg_match('/page-\d+/', $arPath[2]) !== 0
        ) {
            return 'section';
        }

        /**
         * If 3rd level is defined, it is definitely a product.
         */
        $productModel = ElementTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => $iBlockId,
                    'ACTIVE' => 'Y',
                    'IBLOCK_SECTION_ID' => $section['ID'],
                    '=CODE' => $arPath[2]
                ],
                'select' => ['ID', 'CODE', 'IBLOCK_SECTION_ID']
            ]
        );

        /**
         * If no product exists, return false.
         */
        if ($productModel->getSelectedRowsCount() === 0) {
            return false;
        }

        /**
         * Finally return product.
         */
        $product = $productModel->fetch();
        $arVariables['ELEMENT_CODE'] = $product['CODE'];
        $arVariables['ELEMENT_ID'] = $product['ID'];
        return 'element';
    }

    /**
     * @param $arGroup
     * @return string
     */
    public static function getCatalogGroupPageUrl($arGroup): string
    {
        return self::CATALOG_BASE_URL . $arGroup['UF_CODE'] . '/';
    }
}
