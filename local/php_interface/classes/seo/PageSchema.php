<?php


namespace ig\Seo;


use CMain;
use ig\Helpers\UrlHelper;

class PageSchema
{
    public const VIEW_CONTENT_NAME = 'page_schema';

    public static function add(array $data, CMain $application): void
    {
        $currentContent = $application->GetViewContent(static::VIEW_CONTENT_NAME);
        $contentArray = [];
        if ($currentContent !== '') {
            $contentArray = explode(PHP_EOL, $currentContent);
            array_shift($contentArray);
            array_pop($contentArray);
        }
        $encodedString = json_encode($data, JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);

        $contentArray[] = $encodedString;

        array_unshift($contentArray, '<script type="application/ld+json">');
        $contentArray[] = '</script>';
        $application->AddViewContent(static::VIEW_CONTENT_NAME, implode(PHP_EOL, $contentArray));
    }

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

}