<?php


namespace ig\Helpers;

use IntlDateFormatter;

class DateTimeHelper
{
    /**
     * @param string $locale
     * @return array
     */
    public static function getMonthNames(string $locale = 'ru_RU'):array {
        $dateFormatter = new IntlDateFormatter($locale,null,null);
        $dateFormatter->setPattern('LLLL');
        $monthArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $date = date_create_from_format('m',$i);
            $monthArray[$i] = TextHelper::ucFirst($dateFormatter->format($date));
        }
        return $monthArray;
    }
}