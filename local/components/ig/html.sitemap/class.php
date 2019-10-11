<?php
/**
 * @package HTML Sitemap
 * @author Oleg Makeev me@olegmakeev.com
 * As probability that this will slightly ever change, sections are hardcoded.
 */

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

/** @noinspection AutoloadingIssuesInspection */

class CHtmlSitemap extends CBitrixComponent
{
    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        try {
            $this->startResultCache();
            $this->loadModules();
            $this->setResult();
            $this->includeComponentTemplate();
            $this->endResultCache();
        } catch (Exception $e) {
            $this->abortResultCache();
        }
    }


    /**
     * @throws LoaderException
     */
    private function loadModules(): void
    {
        Loader::includeModule('iblock');
    }


    /**
     * Method combines all other methods used to fill $arResult
     */
    private function setResult(): void
    {
        $menu = $this->getMenu();
        $catalogMenu = $this->getCatalogSections($menu);
        $menu['/katalog/']['CHILDREN']  = array_merge_recursive($menu['/katalog/']['CHILDREN'], $catalogMenu);
        $this->arResult['ITEMS'] = $menu;
    }

    private function getCatalogSections(): array
    {
        $sections = [];
        $sectionModel = CIBlockSection::GetList(
            [],
            [
                'IBLOCK_ID' => [CATALOG_GARDEN_IBLOCK_ID, CATALOG_IBLOCK_ID],
                'ACTIVE' => 'Y',
            ],
            false,
            [
                'ID',
                'IBLOCK_ID',
                'LIST_PAGE_URL',
                'SECTION_PAGE_URL',
                'DEPTH_LEVEL',
                'NAME'
            ]
        );
        while ($section = $sectionModel->GetNext()) {
            if((int)$section['DEPTH_LEVEL'] === 1) {
                $sections[$section['LIST_PAGE_URL']]['CHILDREN'][$section['SECTION_PAGE_URL']]['NAME'] = $section['NAME'];
            }
            if((int)$section['DEPTH_LEVEL'] === 2) {
                $sectionUrlArray = explode('/',trim($section['SECTION_PAGE_URL'],'/'));
                array_pop($sectionUrlArray);
                $parentSectionUrl = '/' . implode('/',$sectionUrlArray) . '/';
                $sections[$section['LIST_PAGE_URL']]['CHILDREN'][$parentSectionUrl]['CHILDREN'][$section['SECTION_PAGE_URL']]['NAME'] = $section['NAME'];
            }
        }
        return $sections;
    }


    private function getMenu(): array
    {
        $result = [];
        global $APPLICATION;
        $menu = $APPLICATION->GetMenu('top', true, false, '/');
        $menu->Init('/');
        foreach ($menu->arMenu as $item) {
            $submenu = $APPLICATION->GetMenu('subtop', true, false, $item[1]);
            $result[$item[1]]['NAME'] = $item[0];
            foreach ($submenu->arMenu as $subItem) {
                $result[$item[1]]['CHILDREN'][$subItem[1]]['NAME'] = $subItem[0];
            }
        }
        return $result;
    }
}