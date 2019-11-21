<?php

namespace ig\Catalog;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use ig\Datasource\Highload\FilterAliasTable;
use ig\Datasource\Highload\VirtualPageTable;
use ig\Exceptions\NotFoundHttpException;
use ig\Helpers\UrlHelper;

class Router
{
//ToDo:Check if double page appear w/o check

    private $baseUrl;
    private $iBlockId = 0;
    private $sectionId;
    private $sectionDepthLevel;
    private $sectionCode;
    private $sectionCodePath;
    private $elementId;
    private $elementCode;
    private $isFilter;
    private $isVirtualFilter;
    private $virtualUrl;

    /**
     * @param array $arParams
     * @return array
     * @throws LoaderException
     * @throws NotFoundHttpException
     * @throws SystemException
     */
    public static function guessCatalogPath(array $arParams): array
    {
        Loader::includeModule('iblock');
        $router = new self();
        $base = $arParams['SEF_FOLDER'] ?? '/';
        $router->baseUrl = $base;
        $urlArray = UrlHelper::getUrlArrayRaw($base);
        $router->iBlockId = $arParams['IBLOCK_ID'];
        $router->setRouteParams($urlArray);
        return $router->getResult();
    }

    /**
     * If 1st level exists, it can be section only (virtual is returned already).
     * If no unique 1st level section exist, NotFoundHttpException is thrown.
     * @param string $code
     * @throws ArgumentException
     * @throws NotFoundHttpException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function setFirstLevelRouteParam(string $code): void
    {
        /** Check section */
        $sectionModel = SectionTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => $this->iBlockId,
                    'DEPTH_LEVEL' => 1,
                    '=CODE' => $code,
                ],
                'select' => ['ID', 'CODE'],
            ]
        );
        if ($sectionModel->getSelectedRowsCount() !== 1) {
            throw new NotFoundHttpException();
        }
        $section = $sectionModel->fetch();
        $this->sectionId = $section['ID'];
        $this->sectionDepthLevel = 1;
        $this->sectionCode = $section['CODE'];
        $this->sectionCodePath = UrlHelper::buildFromParts($section['CODE']);
    }

    /**
     * If 2nd level exists, it can be section only.
     * If no unique 2nd level section exist, NotFoundHttpException is thrown.
     * @param string $code
     * @throws ArgumentException
     * @throws NotFoundHttpException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function setSecondLevelRouteParam(string $code): void
    {
        $sectionModel = SectionTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => $this->iBlockId,
                    'DEPTH_LEVEL' => 2,
                    'ACTIVE' => 'Y',
                    'IBLOCK_SECTION_ID' => $this->sectionId,
                    '=CODE' => $code,
                ],
                'select' => ['ID', 'CODE'],
            ]
        );

        if ($sectionModel->getSelectedRowsCount() !== 1) {
            throw new NotFoundHttpException();
        }

        $section = $sectionModel->fetch();
        $this->sectionId = $section['ID'];
        $this->sectionDepthLevel = 2;
        $this->sectionCode = $section['CODE'];
        $this->sectionCodePath = UrlHelper::buildFromParts($this->sectionCodePath, $section['CODE']);
    }

    /**
     * @param string $code
     * @throws ArgumentException
     * @throws NotFoundHttpException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function setThirdLevelRouteParam(string $code): void
    {
        $elementModel = ElementTable::getList(
            [
                'filter' => [
                    'IBLOCK_ID' => $this->iBlockId,
                    'ACTIVE' => 'Y',
                    'IBLOCK_SECTION_ID' => $this->sectionId,
                    '=CODE' => $code,
                ],
                'select' => ['ID', 'CODE', 'IBLOCK_SECTION_ID'],
            ]
        );
        if ($elementModel->getSelectedRowsCount() !== 1) {
            throw new NotFoundHttpException();
        }
        $element = $elementModel->fetch();
        $this->elementId = $element['ID'];
        $this->elementCode = $element['CODE'];
    }

    /**
     * @param array $urlArray
     * @throws ArgumentException
     * @throws NotFoundHttpException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function setRouteParams(array $urlArray): void
    {
        $levelCount = count($urlArray);
        if ($levelCount > 3) {
            throw new NotFoundHttpException();
        }
        if($this->getIsOffersRequest() === true) {
            $this->setOffersRequestParams();
            return;
        }
            if ($this->getIsVirtualFilter($urlArray)) {
                $this->isVirtualFilter = true;
            }

        $this->isFilter = $this->isGetFilterAliasSet();

        try {
            if ($levelCount > 0) {
                $this->setFirstLevelRouteParam($urlArray[0]);
            }
            if ($levelCount > 1) {
                $this->setSecondLevelRouteParam($urlArray[1]);
            }
        } catch (NotFoundHttpException $e) {
            /** Virtual page parent section may exist */
            if ($this->isVirtualFilter === true) {
                return;
            }
            throw $e;
        }

        if ($levelCount > 2) {
            $this->setThirdLevelRouteParam($urlArray[2]);
        }
    }

    /**
     * @param array $urlArray
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getIsVirtualFilter(array $urlArray): bool
    {
        $virtualUrl = UrlHelper::buildFromParts($this->baseUrl, ...$urlArray);

        /** Check filter */

        $isVirtualFilter = VirtualPageTable::getIsVirtualFilter($virtualUrl);
        if ($isVirtualFilter === true) {
            $this->virtualUrl = $virtualUrl;
        }
        return $isVirtualFilter;
    }

    /**
     * @return bool
     * @throws ArgumentException
     * @throws NotFoundHttpException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function isGetFilterAliasSet(): bool
    {
        $requestArray = Application::getInstance()->getContext()->getRequest()->toArray();
        if (array_key_exists('filterAlias', $requestArray)) {
            $isFilter = FilterAliasTable::isUniqueAliasExist($requestArray['filterAlias']);
            if ($isFilter === true) {
                return true;
            }
            throw new NotFoundHttpException();
        }
        return false;
    }

    /**
     * @return array
     */
    private function getResult(): array
    {
        $result = [];
        if ($this->elementId !== null) {
            $result['ELEMENT_ID'] = $this->elementId;
            $result['ELEMENT_CODE'] = $this->elementCode;
            $result['TEMPLATE'] = 'element';
            return $result;
        }
        $result['IS_FILTER'] = $this->isFilter;
        $result['IS_VIRTUAL_FILTER'] = $this->isVirtualFilter;
        $result['VIRTUAL_URL'] = $this->virtualUrl;
        $result['TEMPLATE'] = 'section';
        if ($this->sectionId !== null) {
            $result['SECTION_ID'] = $this->sectionId;
            $result['SECTION_DEPTH'] = $this->sectionDepthLevel;
            $result['SECTION_CODE'] = $this->sectionCode;
            $result['SECTION_CODE_PATH'] = $this->sectionCodePath;
        }
        return $result;
    }

    /**
     * @return bool
     * @throws SystemException
     */
    private function getIsOffersRequest(): bool
    {
        $productId = Application::getInstance()->getContext()->getRequest()->get('product_id');
        return $productId !== null;
    }

    private function setOffersRequestParams(): void
    {
    }
}
