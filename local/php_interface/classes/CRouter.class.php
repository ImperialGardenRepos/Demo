<?

namespace ig;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CHTTP;

class CRouter
{
    private static $strCatalogBaseUrl = '/katalog/rasteniya/';


    /**
     * @param $folder404
     * @param $arUrlTemplates
     * @param $arVariables
     * @param mixed $requestURL
     * @return bool|string
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function guessCatalogGardenPath($folder404, $arUrlTemplates, &$arVariables, $requestURL = false)
    {
        Loader::includeModule('iblock');

        $strComponentPage = 'sections';

        if (!isset($arVariables) || !is_array($arVariables)) {
            $arVariables = [];
        }

        if ($requestURL === false) {
            $requestURL = Context::getCurrent()->getRequest()->getRequestedPageDirectory();
        }

        $folder404 = str_replace("\\", '/', $folder404);
        if ($folder404 !== '/') {
            $folder404 = '/' . trim($folder404, "/ \t\n\r\0\x0B") . '/';
        }

        //SEF base URL must match current URL (several components on the same page)
        if (strpos($requestURL . '/', $folder404) !== 0) {
            return false;
        }

        $currentPageUrl = substr($requestURL, strlen($folder404));
        $arPathParts = parse_url($currentPageUrl);
        $arPath = explode('/', $arPathParts['path']);

        if (!empty($arPath[3])) {
            CHTTP::setStatus('404 Not Found');
            return false;
        }

        if ((string)$arPath[1] !== '' && (string)$arPath[1] === (string)$arPath[2]) {
            $strRedirectUrl = $folder404 . implode('/', array_slice($arPath, 0, count($arPath) - 2)) . '/';
            LocalRedirect($strRedirectUrl);
        }

        $intIblockID = CHelper::getIblockIdByCode('catalog-garden');

        if (
            ((string)$arPath[0] !== '')
            && $arSection1 = SectionTable::getList(
                [
                    'filter' => [
                        'IBLOCK_ID' => $intIblockID,
                        'DEPTH_LEVEL' => 1,
                        '=CODE' => $arPath[0]
                    ],
                    'select' => ['ID', 'CODE']
                ]
            )->fetch()
        ) {
            $strComponentPage = 'section';
            $arVariables['SECTION_CODE'] = $arSection1['CODE'];
            $arVariables['SECTION_ID'] = $arSection1['ID'];
            $arVariables['SECTION_CODE_PATH'] = $arSection1['CODE'];

            if ((string)$arPath[1] !== '' && preg_match('/page-\d+/', $arPath[1]) === 0) {
                if ($arSection2 = SectionTable::getList([
                    'filter' => [
                        'IBLOCK_ID' => $intIblockID,
                        'DEPTH_LEVEL' => 2,
                        'ACTIVE' => 'Y',
                        'IBLOCK_SECTION_ID' => $arSection1['ID'],
                        '=CODE' => $arPath[1]
                    ],
                    'select' => ['ID', 'CODE']
                ])->fetch()) {
                    $strComponentPage = 'section';
                    $arVariables['SECTION_CODE'] = $arSection2['CODE'];
                    $arVariables['SECTION_ID'] = $arSection2['ID'];
                    $arVariables['SECTION_CODE_PATH'] = $arSection1['CODE'] . '/' . $arSection2['CODE'];

                    if ((string)$arPath[2] !== '') {
                        if ($arProduct = ElementTable::getList([
                            'filter' => [
                                'IBLOCK_ID' => $intIblockID,
                                'ACTIVE' => 'Y',
                                'IBLOCK_SECTION_ID' => $arSection2['ID'],
                                '=CODE' => $arPath[2]
                            ],
                            'select' => ['ID', 'CODE']
                        ])->fetch()) {
                            $strComponentPage = 'element';
                            $arVariables['ELEMENT_CODE'] = $arProduct['CODE'];
                            $arVariables['ELEMENT_ID'] = $arProduct['ID'];
                        } else {
                            $arVariables['ELEMENT_ID'] = -1;
                        }
                    }
                } else {
                    $arVariables['SECTION_ID'] = -1;
                }
            }
        }

        return $strComponentPage;
    }

    /**
     * @param $folder404
     * @param $arUrlTemplates
     * @param $arVariables
     * @param bool $requestURL
     * @return bool|string
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function guessCatalogPath($folder404, $arUrlTemplates, &$arVariables, $requestURL = false)
    {
        Loader::includeModule('iblock');

        if (!isset($arVariables) || !is_array($arVariables)) {
            $arVariables = array();
        }

        if ($requestURL === false) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $requestURL = Context::getCurrent()->getRequest()->getRequestedPageDirectory();
        }

        $folder404 = str_replace("\\", '/', $folder404);
        if ($folder404 !== '/') {
            $folder404 = '/' . trim($folder404, "/ \t\n\r\0\x0B") . '/';
        }

        //SEF base URL must match curent URL (several components on the same page)
        if (strpos($requestURL . '/', $folder404) !== 0) {
            return false;
        }

        $currentPageUrl = substr($requestURL, strlen($folder404));
        $arPathParts = parse_url($currentPageUrl);
        $arPath = explode('/', $arPathParts['path']);

        if (!empty($arPath[3])) {
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
        $filterUrl = preg_replace('/(.*\/)page-\d+\//','$1',$filterUrl);
        $filterModel = CVirtualPage::getFirst(false,
            [
                'UF_URL' => $filterUrl,
            ],
            ['ID']
        );
        if ($filterModel !== null) {
            return 'smart_filter';
        }

        /**
         * Then section SEF.
         */
        $sectionModel = SectionTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => CATALOG_IBLOCK_ID,
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
                    'IBLOCK_ID' => CATALOG_IBLOCK_ID,
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
                    'IBLOCK_ID' => CATALOG_IBLOCK_ID,
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

    public static function getUrlInfo($strUrl = '')
    {
        $arResult = array();

        if (empty($strUrl)) {
            $strUrl = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getRequestedPageDirectory();
        }

        // search seo pages here
        if (false) {
            $arResult = array(
                "FOLDER" => self::$strCatalogBaseUrl,
                "VARIABLES" => array(
                    "SEO_URL" => '',
                    "SEO_ID" => 12
                )
            );
        }

        return $arResult;
    }

    public
    static function getCatalogBaseUrl()
    {
        return self::$strCatalogBaseUrl;
    }

    public
    static function getCatalogGroupPageUrl($arGroup)
    {
        return self::getCatalogBaseUrl() . $arGroup["UF_CODE"] . '/';
    }
}