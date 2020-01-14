<?php


namespace ig\Seo;


use Bitrix\Main\SystemException;
use CFile;
use CMain;
use ig\Helpers\IBlockElementHelper;
use ig\Helpers\UrlHelper;

class PageSchema
{
    public const VIEW_CONTENT_NAME = 'page_schema';

    /**
     * @param array $data
     * @param CMain $application
     */
    public static function add(array $data, CMain $application): void
    {
        $content = $application->GetViewContent(static::VIEW_CONTENT_NAME);
        $encodedString = json_encode($data, JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);
        /** Prevent schema duplicates */
        if (strpos($content, $encodedString) !== false) {
            return;
        }
        $application->AddViewContent(static::VIEW_CONTENT_NAME, "<script type=\"application/ld+json\">{$encodedString}</script>" . PHP_EOL);
    }

    /**
     * @param array $breadcrumbsResult
     * @param CMain $application
     * @throws SystemException
     */
    public static function setBreadcrumbsSchema(array $breadcrumbsResult, CMain $application): void
    {
        if (count($breadcrumbsResult) === 0) {
            return;
        }
        $items = [];
        $position = 1;
        foreach ($breadcrumbsResult as $item) {
            if ((string)$item['LINK'] === '') {
                continue;
            }
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $item['TITLE'],
                'item' => UrlHelper::getHost() . $item['LINK'],
            ];
            $position++;
        }
        $result = [
            '@context' => 'https://schema.org/',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
        static::add($result, $application);
    }

    /**
     * @param array $detailResult
     * @param CMain $application
     * @throws SystemException
     */
    public static function setProductSchema(array $detailResult, CMain $application, $component): void
    {
        $result = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
        ];
        $result['name'] = $detailResult['NAME'];
        if (isset($detailResult['NAME_FULL']) && (string)$detailResult['NAME_FULL'] !== '') {
            $result['name'] = $detailResult['NAME_FULL'];
        }

        $imgPath = CFile::GetPath(IBlockElementHelper::getFirstImageId($detailResult));
        if ($imgPath !== null) {
            $result['image'] = UrlHelper::getHost() . $imgPath;
        }

        $description = IBlockElementHelper::getPropertyGeneratedDescription($detailResult);
        if ($description !== null) {
            $result['description'] = $description;
        }

        $result['brand'] = 'IMPERIAL GARDEN';
        $result['offers'] = [
            '@type' => 'AggregateOffer',
            'url' => UrlHelper::getHost() . $detailResult['DETAIL_PAGE_URL'],
            'priceCurrency' => 'RUB',

        ];

        $pricesAll = array_merge(
            array_filter(array_column($detailResult['OFFERS'], 'CATALOG_PRICE_' . CATALOG_BASE_PRICE_ID)),
            array_filter(array_column($detailResult['OFFERS'], 'CATALOG_PRICE_' . CATALOG_ACTION_PRICE_ID))
        );
        if ($pricesAll !== []) {
            $result['offers']['lowPrice'] = (int)min($pricesAll);
            $result['offers']['highPrice'] = (int)max($pricesAll);
        }

        static::add($result, $application);
    }
}