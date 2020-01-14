<?php


namespace ig\Helpers;


class IBlockElementHelper
{
    /**
     * @param array $element
     * @return int|null
     */
    public static function getFirstImageId(array $element): ?int
    {
        if ((bool)$element['DETAIL_PICTURE'] !== false) {
            return (int)$element['DETAIL_PICTURE'];
        }
        if ((bool)$element['PREVIEW_PICTURE'] !== false) {
            return (int)$element['PREVIEW_PICTURE'];
        }
        if ((int)$element['IBLOCK_ID'] === CATALOG_IBLOCK_ID) {
            foreach (CATALOG_PHOTO_PROPERTIES as $propertyCode) {
                if (
                    isset($element['PROPERTIES'][$propertyCode]['VALUE'])
                    && (bool)$element['PROPERTIES'][$propertyCode]['VALUE'] !== false
                ) {
                    return (int)$element['PROPERTIES'][$propertyCode]['VALUE'];
                }
            }
        }
        return null;
    }

    public static function getPropertyGeneratedDescription(array $element): ?string
    {
        $propertyHelper = new PropertyFormatHelper();
        $propertyHelper->setFormatterParams(
            PropertyFormatHelper::PARAM_POST_PROCESS_RANGE_DASH_REPLACE
        );

        if ((int)$element['IBLOCK_ID'] === CATALOG_IBLOCK_ID) {
            $propertyValueArray = [];
            foreach (CATALOG_DESCRIPTION_PROPERTIES as $propertyCode) {
                if (!isset($element['PROPERTIES'][$propertyCode])) {
                    continue;
                }
                $name = $element['PROPERTIES'][$propertyCode]['NAME'];

                $value = $propertyHelper->formatRandomTypeValue($element['PROPERTIES'][$propertyCode]);
                if ($value === null) {
                    continue;
                }
                $propertyValueArray[] = "{$name} {$value}";
            }
            if ($propertyValueArray !== []) {
                return implode('; ', $propertyValueArray);
            }
        }

        if ((string)$element['PREVIEW_TEXT'] !== '') {
            $text = TextHelper::truncateByStatement(strip_tags(html_entity_decode($element['PREVIEW_TEXT'])));
            if ($text !== '') {
                return $text;
            }
        }

        if ((string)$element['DETAIL_TEXT'] !== '') {
            $text = TextHelper::truncateByStatement(strip_tags(html_entity_decode($element['DETAIL_TEXT'])));
            if ($text !== '') {
                return $text;
            }
        }

        return null;
    }

}