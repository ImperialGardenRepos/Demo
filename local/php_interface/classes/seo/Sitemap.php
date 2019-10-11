<?php
/**
 * @package Sitemap
 * It doesn't use any xml builder as string writing is faster.
 */

namespace ig\Seo;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\IO\FileNotOpenedException;
use Bitrix\Main\IO\FileOpenException;
use Bitrix\Main\IO\IoException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\SystemException;
use CIBlock;
use CIBlockElement;
use CIBlockSection;
use DateTime;
use ig\Helpers\DBG;
use ig\Highload\VirtualPage;

/**
 * Class Sitemap
 * @package ig\Seo
 */
class Sitemap
{
    private const SITEMAP_FILE = SITE_BASE_DIR . 'sitemap.xml';

    private const HOST = '';

    private const IBLOCK_ELEMENTS = [
        CATALOG_IBLOCK_ID,
        CATALOG_GARDEN_IBLOCK_ID,
        NEWS_IBLOCK_ID,
        ENTERTAINMENT_IBLOCK_ID,
        SERVICES_IBLOCK_ID,
        PORTFOLIO_IBLOCK_ID,
    ];

    private const IBLOCK_SECTIONS = [
        CATALOG_IBLOCK_ID,
        CATALOG_GARDEN_IBLOCK_ID,
        NEWS_IBLOCK_ID,
        CONSTRUCTOR_IBLOCK_ID,
        SERVICES_IBLOCK_ID,
        REVIEWS_IBLOCK_ID,
        PORTFOLIO_IBLOCK_ID,
    ];

    private const IBLOCKS = [
        CATALOG_IBLOCK_ID,
        CATALOG_GARDEN_IBLOCK_ID,
        NEWS_IBLOCK_ID,
        CONSTRUCTOR_IBLOCK_ID,
        FAQ_IBLOCK_ID,
        VEHICLE_RENT_IBLOCK_ID,
        SERVICES_IBLOCK_ID,
        GRATITUDE_IBLOCK_ID,
        ENTERTAINMENT_IBLOCK_ID,
        TEAM_IBLOCK_ID,
        PARTNERS_IBLOCK_ID,
        REVIEWS_IBLOCK_ID,
        PORTFOLIO_IBLOCK_ID,

    ];

    private const CUSTOM_PAGES = [
        '/',
        '/o-nas/kontakty/',
        '/o-nas/virtualnyy-tur/',
        '/partneram/event-ploshchadka/',
        '/partneram/optovyy-otdel/',
        '/partneram/stat-partnyerom/'
    ];

    /**
     * Sitemap constructor.
     * @throws LoaderException
     */
    private function __construct()
    {
        Loader::includeModule('iblock');
    }

    /**
     * @var resource|null
     */
    private $file;
    /**
     * @var array
     */
    private $addedLinks = [];

    /**
     * @throws LoaderException
     */
    public static function generate(): string
    {
        $sitemap = new static();
        $sitemap->setFile();
        $sitemap->addHeader();
        $sitemap->addLinks(self::CUSTOM_PAGES);
        $sitemap->addLinks($sitemap->getVirtualPageLinks());
        $sitemap->addLinks($sitemap->getIBlockLinks());
        $sitemap->addLinks($sitemap->getIBlockSectionLinks());
        $sitemap->addLinks($sitemap->getIBlockElementLinks());
        $sitemap->addFooter();
        $sitemap->unsetFile();
        return static::class . '::generate();';
    }

    /**
     * @throws FileOpenException
     */
    private function setFile(): void
    {
        $file = @fopen(self::SITEMAP_FILE, 'wb');
        if ($file === false) {
            throw new FileOpenException(self::SITEMAP_FILE);
        }
        $this->file = $file;
    }

    /**
     * @throws FileNotOpenedException
     * @throws IoException
     */
    private function unsetFile(): void
    {
        if ($this->file === null) {
            throw new FileNotOpenedException(self::SITEMAP_FILE);
        }
        $isClosed = @fclose($this->file);
        if ($isClosed === false) {
            throw new IoException('Couldn\'t close sitemap file', self::SITEMAP_FILE);
        }
        $this->file = null;
    }

    /**
     * @param array $links
     * @throws IoException
     */
    private function addLinks(array $links): void
    {
        foreach ($links as [$loc, $lastmod, $priority]) {
            if (!$loc || in_array($loc, $this->addedLinks, true)) {
                continue;
            }
            $loc = trim($loc);
            $this->addedLinks[] = $loc;
            if (!$lastmod) {
                $lastmod = date('Y-m-d\TH:i:sP');
            }
            if (!$priority) {
                $priority = $this->getPriorityByLink($loc);
            }
            $loc = self::HOST . $loc;

            $this->write(
                "<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><priority>{$priority}</priority></url>"
            );
        }
    }

    /**
     * @throws IoException
     */
    private function addHeader(): void
    {
        $this->write('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
    }

    /**
     * @throws IoException
     */
    private function addFooter(): void
    {
        $this->write('</urlset>');
    }

    /**
     * @param $row
     * @throws IoException
     */
    private function write($row): void
    {
        if (@fwrite($this->file, $row) === false) {
            throw new IoException('Couldn\'t write to sitemap file');
        }
    }

    /**
     * @return array
     */
    private function getIBlockLinks(): array
    {
        $iBlockModel = CIBlock::GetList(
            [],
            [
                'ID' => self::IBLOCKS,
                'ACTIVE' => 'Y',
            ]
        );
        $iBlockLinks = [];
        while ($iBlock = $iBlockModel->GetNext()) {
            $iBlockLinks[] = [
                $this->removeHash($iBlock['LIST_PAGE_URL']),
                $this->convertTimestampX($iBlock['TIMESTAMP_X']),
                false
            ];
        }
        return $iBlockLinks;
    }

    /**
     * @return array
     */
    private function getIBlockSectionLinks(): array
    {
        $sectionModel = CIBlockSection::GetList(
            [],
            [
                'IBLOCK_ID' => self::IBLOCK_SECTIONS,
                'ACTIVE' => 'Y',
            ],
            false,
            [
                'ID',
                'SECTION_PAGE_URL',
                'DEPTH_LEVEL',
                'TIMESTAMP_X'
            ]
        );
        $sectionLinks = [];
        while ($section = $sectionModel->GetNext()) {
            $sectionLinks[] = [
                $this->removeHash($section['SECTION_PAGE_URL']),
                $this->convertTimestampX($section['TIMESTAMP_X']),
                $this->getPriorityByDepthLevel((int)$section['DEPTH_LEVEL']),
            ];
        }
        return $sectionLinks;
    }

    /**
     * @return array
     */
    private function getIBlockElementLinks(): array
    {
        $elementModel = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => self::IBLOCK_ELEMENTS,
                'ACTIVE' => 'Y',
            ],
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
                'DETAIL_PAGE_URL',
                'TIMESTAMP_X',
            ]
        );
        $elementLinks = [];
        while ($element = $elementModel->GetNext()) {
            $elementLinks[] = [
                $this->removeHash($element['DETAIL_PAGE_URL']),
                $this->convertTimestampX($element['TIMESTAMP_X']),
                false
            ];
        }
        return $elementLinks;
    }


    /**
     * @return array
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getVirtualPageLinks(): array
    {
        /**
         * @var Entity $virtualPageModel
         */
        $virtualPageEntity = VirtualPage::getEntity();
        $virtualPageModel = $virtualPageEntity::query()
            ->where('UF_ACTIVE', '=', '1')
            ->setSelect(['UF_URL'])
            ->exec();
        $virtualPageLinks = [];
        while ($virtualPage = $virtualPageModel->fetch()) {
            $virtualPageLinks[] = [
                $this->removeHash($virtualPage['UF_URL']),
                false,
                false,
            ];
        }
        return $virtualPageLinks;
    }

    /**
     * @param int $depthLevel
     * @return string
     */
    private function getPriorityByDepthLevel(int $depthLevel): string
    {
        $base = $depthLevel - 1;
        $priority = 1 - $base * .2;
        if ($priority < .2) {
            $priority = .2;
        }
        return (string)$priority;
    }

    /**
     * @param string $link
     * @return string
     */
    private function getPriorityByLink(string $link): string
    {
        $link = trim($link, '/');
        $linkArray = explode('/', $link);
        return $this->getPriorityByDepthLevel(count($linkArray));
    }


    /**
     * @param string $timestampX
     * @return string
     */
    private function convertTimestampX(string $timestampX): string
    {
        $dateTime = DateTime::createFromFormat('d.m.Y H:i:s', $timestampX);
        return $dateTime->format('Y-m-d\TH:i:sP');
    }

    /**
     * @param string $url
     * @return string
     */
    private function removeHash(string $url): string
    {
        $urlArray = explode('#', $url);
        return $urlArray[0];
    }
}