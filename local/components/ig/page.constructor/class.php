<?php
/**
 * @package LandingConstructor
 * @author Oleg Makeev me@olegmakeev.com
 *
 */

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException as ArgumentExceptionAlias;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use ig\Exceptions\NotFoundHttpException;

class CPageConstructor extends CBitrixComponent
{

    /**
     * @param $arParams
     * @return array
     * @throws ArgumentExceptionAlias
     * @throws SystemException
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams = parent::onPrepareComponentParams($arParams);
        if (!array_key_exists('IBLOCK_ID', $arParams) || !is_int((int)$arParams['IBLOCK_ID'])) {
            throw new ArgumentExceptionAlias('Undefined IBLOCK_ID');
        }

        $requestArray = Application::getInstance()->getContext()->getRequest()->toArray();
        if (!isset($arParams['CODE'])) {
            if (!array_key_exists('CODE', $requestArray) || (bool)$requestArray['CODE'] === false) {
                Tools::process404();
            }
            $code = explode('?', $requestArray['CODE']);

            $arParams['CODE'] = reset($code);
        }
        return $arParams;
    }

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
        } catch (NotFoundHttpException $e) {
            $this->abortResultCache();
            defined('STATUS_404') or define('STATUS_404', true);
            return;
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
     * @throws NotFoundHttpException
     */
    private function setResult(): void
    {
        $section = $this->getSection();
        if ($section === null) {
            throw new NotFoundHttpException();
        }
        $this->arResult['SECTION'] = $section;
        $this->arResult['ITEMS'] = $this->getBlocks($section['ID']);
        global $APPLICATION;
        $APPLICATION->SetTitle($this->arResult['SECTION']['NAME']);
    }

    /**
     * @return array|null
     */
    private function getSection(): ?array
    {
        $section = CIBlockSection::GetList(
            ['SORT' => 'ASC'],
            [
                'CODE' => $this->arParams['CODE'],
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            ]
        );
        if ($section->SelectedRowsCount() !== 1) {
            Tools::process404();
            return null;
        }

        return $section->Fetch();
    }

    /**
     * @param int $sectionId
     * @return array|null
     */
    private function getBlocks(int $sectionId): ?array
    {
        $elements = CIBlockElement::GetList(
            [
                'SORT' => 'ASC',
            ],
            [
                'IBLOCK_SECTION_ID' => $sectionId,
                'ACTIVE' => 'Y',
            ],
            false,
            false,
            [
                '*',
                'PROPERTY_*',
            ]
        );
        $items = [];
        while ($element = $elements->GetNextElement(true, false)) {
            $item = $element->GetFields();
            $item['PROPERTIES'] = $element->GetProperties();
            $items[$item['ID']] = $item;
        }
        return $items;
    }
}