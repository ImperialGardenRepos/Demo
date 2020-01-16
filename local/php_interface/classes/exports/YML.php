<?php


namespace ig\Exports;


use Bitrix\Catalog\PriceTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\IO\FileOpenException;
use Bitrix\Main\IO\IoException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CIBlockElement;
use CIBlockSection;
use ig\Helpers\UrlHelper;

class YML
{
    private const YML_FILE = SITE_BASE_DIR . 'offers.yml';

    private $file;

    /**
     * YML constructor.
     * @throws LoaderException
     */
    private function __construct()
    {
        Loader::includeModule('iblock');
    }

    /**
     * @throws LoaderException
     */
    public static function generate(): string
    {
        $yml = new static();
        $yml->setFile();
        $yml->addHeader();
        $yml->addCommonInfo();
        $yml->addOffers();
        $yml->addFooter();
        return static::class . '::generate();';
    }

    /**
     * @throws FileOpenException
     */
    private function setFile(): void
    {
        $file = @fopen(self::YML_FILE, 'wb');
        if ($file === false) {
            throw new FileOpenException(self::YML_FILE);
        }
        $this->file = $file;
    }

    /**
     * @param $row
     * @throws IoException
     */
    private function write($row): void
    {
        if (@fwrite($this->file, $row) === false) {
            throw new IoException('Couldn\'t write to yml file');
        }
    }

    /**
     * @throws IoException
     */
    private function addHeader(): void
    {
        $this->write('<?xml version="1.0" encoding="UTF-8"?><yml_catalog date="2019-11-01 17:22"><shop>');
    }

    /**
     * @throws IoException
     */
    private function addFooter(): void
    {
        $this->write('</shop></yml_catalog>');
    }

    /**
     * <name>BestSeller</name>
     * <company>Tne Best inc.</company>
     * <url>http://best.seller.ru</url>
     * <currencies>
     * <currency id="RUR" rate="1"/>
     * <currency id="USD" rate="60"/>
     * </currencies>
     * <categories>
     * <category id="1">Бытовая техника</category>
     * <category id="10" parentId="1">Мелкая техника для кухни</category>
     * </categories>
     * <delivery-options>
     * <option cost="200" days="1"/>
     * </delivery-options>
     */
    private function addCommonInfo(): void
    {
        $info = '';
        $info .= '<name>ImperialGarden</name>';
        $info .= '<company>АО «Ривьера»</company>';
        $info .= '<currencies><currency id="RUR" rate="1"/></currencies>';
        $info .= $this->getSections();
        $info .= $this->getDeliveryOptions();
        $this->write($info);
    }

    /**
     * @return string
     */
    private function getSections(): string
    {
        $sectionModel = CIBlockSection::GetTreeList(
            [
                'IBLOCK_ID' => [CATALOG_IBLOCK_ID, CATALOG_GARDEN_IBLOCK_ID],
                'ACTIVE' => 'Y',
            ],
            ['ID', 'IBLOCK_SECTION_ID', 'NAME', 'DEPTH_LEVEL']
        );
        $sections = [];
        while ($section = $sectionModel->GetNext(false, false)) {
            $sections[$section['ID']] = $section;
        }
        $categories = '';
        foreach ($sections as $id => $section) {
            $name = $section['NAME'];
            $parentAttribute = '';
            if ($section['IBLOCK_SECTION_ID'] !== null) {
                $parentAttribute = " parentId=\"{$section['IBLOCK_SECTION_ID']}\"";
                $name = "{$sections[$section['IBLOCK_SECTION_ID']]['NAME']} {$name}";
            }
            $categories .= "<category id=\"{$id}\"{$parentAttribute}>{$name}</category>";
        }
        return "<categories>{$categories}</categories>";
    }

    /**
     * @return string
     */
    private function getDeliveryOptions(): string
    {
        return '<delivery-options><option cost="1000" days="7"/></delivery-options>';
    }

    /**
     * @throws ArgumentException
     * @throws IoException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function addOffers(): void
    {
        $this->write('<offers>');
        $this->write($this->getOffers());
        $this->write('</offers>');
    }

    /**
     * @return string
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getOffers(): string
    {
        $elementModel = CIBlockElement::GetList(
            [],
            [
                '=IBLOCK_ID' => [
                    CATALOG_GARDEN_IBLOCK_ID,
                    CATALOG_IBLOCK_ID,
                ],
                '=AVAILABLE' => 'Y',
                '=ACTIVE' => 'Y',
            ],
            false,
            false,
            [
                'ID',
                'NAME',
                'IBLOCK_ID',
                'DETAIL_PAGE_URL',
                'IBLOCK_SECTION_ID',
                'PROPERTY_FULL_NAME',
            ]
        );
        $elements = [];
        while ($element = $elementModel->GetNext()) {
            $elements[$element['ID']] = [
                'NAME' => htmlspecialchars($element['PROPERTY_FULL_NAME_VALUE']),
                'URL' => $element['DETAIL_PAGE_URL'],
                'SECTION_ID' => $element['IBLOCK_SECTION_ID'],
            ];
        }
        $priceModel = PriceTable::getList([
            'select' => [
                'PRICE',
                'ELEMENT.ID',
            ],
            'filter' => [
                '>PRICE' => 0,
                '=ELEMENT.ID' => array_keys($elements),
            ],
            'order' => [
                'PRICE' => 'ASC',
            ],
        ]);
        $offers = '';
        while ($price = $priceModel->fetch()) {
            if (isset($elements[$price['CATALOG_PRICE_ELEMENT_ID']])) {
                $element = $elements[$price['CATALOG_PRICE_ELEMENT_ID']];
                $element['URL'] = UrlHelper::getHost() . $element['URL'];
                $price['PRICE'] = (int)$price['PRICE'];
                $offers .= "<offer id=\"{$price['CATALOG_PRICE_ELEMENT_ID']}\">";
                $offers .= "<name>{$element['NAME']}</name>";
                $offers .= "<url>{$element['URL']}</url>";
                $offers .= "<price>{$price['PRICE']}</price>";
                $offers .= '<currencyId>RUR</currencyId>';
                $offers .= "<categoryId>{$element['SECTION_ID']}</categoryId>";
                $offers .= '</offer>';
            }
        }
        return $offers;
    }
}