<?php

namespace ig\Seo;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use ig\Helpers\Url;
use ig\Highload\VirtualPage;

/**
 * Class Meta
 * @package ig\Seo
 */
class Meta
{
    /**
     * @var static
     */
    private static $instance;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $totalPage;

    /**
     * @var string
     */
    private $baseTitle;

    /**
     * @var string
     */
    private $baseDescription;

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $minPrice;


    /**
     * @var string
     */
    private $height;

    /**
     * @var string
     */
    private $month;


    /**
     * Meta constructor.
     */
    private function __construct()
    {
        global $APPLICATION;
        /** Property is actually used in masks */
        /** @noinspection UnusedConstructorDependenciesInspection */
        $this->title = $APPLICATION->GetTitle();
    }

    /**
     * @param string $height
     */
    public function setHeight(string $height): void
    {
        $this->height = $height;
    }

    /**
     * @param string $month
     */
    public function setMonth(string $month): void
    {
        $this->month = $month;
    }

    /**
     *
     */
    private function __clone()
    {

    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @param int $totalPage
     */
    public function setTotalPage(int $totalPage): void
    {
        $this->totalPage = $totalPage;
    }

    /**
     * @param string $baseDescription
     */
    public function setBaseDescription(string $baseDescription): void
    {
        $this->baseDescription = $baseDescription;
    }

    /**
     * @param string $baseTitle
     */
    public function setBaseTitle(string $baseTitle): void
    {
        $this->baseTitle = $baseTitle;
    }

    /**
     * @param float $minPrice
     */
    public function setMinPrice(float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return Meta
     */
    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param mixed $request
     * @param int $currentIteration
     * @return array|null
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getCustomMeta(&$request = null, int $currentIteration = 0): ?array
    {
        if ($request === null) {
            $request = explode('/', trim(Url::getUrlWithoutParams(), '/'));
        }
        $requestCount = count($request);
        if ($currentIteration === $requestCount - 1) {
            return null;
        }

        $currentUrlEqual = '/' . implode('/', $request) . '/';

        $key = $requestCount - 1 - $currentIteration;
        $request[$key] = '#';

        $currentUrlMasked = '/' . implode('/', $request) . '/';

        $meta = VirtualPage::getByUrl([$currentUrlEqual, $currentUrlMasked]);
        if ($meta !== []) {
            $metaArray = array_column($meta, null, 'UF_URL');
            if (isset($metaArray[$currentUrlEqual])) {
                return $metaArray[$currentUrlEqual];
            }
            if (isset($metaArray[$currentUrlMasked])) {
                return $metaArray[$currentUrlMasked];
            }
            throw new ObjectPropertyException('No url found in set');
        }
        return $this->getCustomMeta($request, ++$currentIteration);
    }

    /**
     * @throws SystemException
     */
    private function setPaginationMeta(): void
    {
        global $APPLICATION;
        if ($this->totalPage !== null && $this->currentPage !== null && $this->currentPage > 1) {
            $APPLICATION->SetPageProperty('description', null);
            $browserTitle = $APPLICATION->GetTitle();
            $browserTitle .= " – Страница {$this->currentPage} из {$this->totalPage}";
            $APPLICATION->SetTitle($browserTitle);
            $APPLICATION->SetPageProperty('canonical', Url::getUrlWithoutParams());
        }
    }

    /**
     * Sets meta from VirtualPage HighLoad block
     *
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function setCustomMeta(): void
    {
        global $APPLICATION;
        $meta = $this->getCustomMeta();
        if ($meta === null) {
            return;
        }
        if ($meta['UF_DESCRIPTION'] !== '') {
            $APPLICATION->SetPageProperty('description', $this->processMasks($meta['UF_DESCRIPTION']));
        }
        if ($meta['UF_TITLE'] !== '') {
            $APPLICATION->SetTitle($this->processMasks($meta['UF_TITLE']));
        }
        if ($meta['UF_H1'] !== '') {
            $APPLICATION->SetPageProperty('title', $this->processMasks($meta['UF_H1'], false));
        }
    }

    /**
     * Masks are set as {variableName} placeholders.
     * If this class has variableName property and it is not null, its value is substituted.
     * If no masks found, trimmed value is returned.
     *
     * @param string $maskedString
     * @param bool $dropQuotes
     * @return string
     */

    private function processMasks(string $maskedString, bool $dropQuotes = true): string
    {
        $matches = [];
        preg_match_all('/{(.*)}/mU', $maskedString, $matches);
        if ($matches[1] !== []) {
            $replaceArray = array_unique($matches[1]);
            foreach ($replaceArray as $replaceField) {
                if (property_exists($this, $replaceField) && isset($this->$replaceField)) {
                    $field = $this->$replaceField;
                    if ($dropQuotes === true) {
                        $field = str_replace(["'", '"'], '', $field);
                    }
                    $maskedString = str_replace("{{$replaceField}}", $field, $maskedString);
                }
            }
        }
        return trim($maskedString);
    }


    /**
     * Sets browser title from title property if browser title is empty
     */
    private function setEmptyMeta(): void
    {
        global $APPLICATION;
        if ((string)$APPLICATION->GetTitle() === '') {
            $APPLICATION->SetTitle($APPLICATION->GetPageProperty('title'));
        }

        if ($this->baseTitle === '') {
            $this->baseTitle = $APPLICATION->GetTitle();
        }
    }

    /**
     * Wrapper over internal methods used for event
     */
    public static function setFinalMeta(): void
    {
        /** @var static $instance */
        $instance = static::getInstance();
        $instance->setCustomMeta();
        $instance->setEmptyMeta();
        $instance->setPaginationMeta();
    }
}