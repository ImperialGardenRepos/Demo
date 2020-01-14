<?php


namespace ig\Helpers;

use IntlDateFormatter;

class DateTimeHelper
{
    /**
     * @param string $locale
     * @return array
     */
    public static function getMonthNames(string $locale = 'ru_RU'): array
    {
        $dateFormatter = new IntlDateFormatter($locale, null, null);
        $dateFormatter->setPattern('LLLL');
        $monthArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $date = date_create_from_format('m', $i);
            $monthArray[$i] = TextHelper::ucFirst($dateFormatter->format($date));
        }
        return $monthArray;
    }

    /**
     * @param int $monthNumber
     * @param string $locale
     * @return string|null
     */
    public static function getMonthName(int $monthNumber, string $locale = 'ru_RU'): ?string
    {
        if ($monthNumber < 1 || $monthNumber > 12) {
            return null;
        }
        $dateFormatter = new IntlDateFormatter($locale, null, null);
        $dateFormatter->setPattern('LLLL');
        $date = date_create_from_format('m', $monthNumber);
        return TextHelper::ucFirst($dateFormatter->format($date));

    }
}