<?php


namespace ig\Helpers;


class TextHelper
{
    /**
     * @param string $text
     * @param int $statementCount
     * @return string
     */
    public static function truncateByStatement(string $text, int $statementCount = 1): string
    {
        $text = strip_tags($text);
        $statements = explode('.', $text);
        $i = 0;
        $result = '';
        while ($i < $statementCount && isset($statements[$i])) {
            if (trim($statements[$i]) !== '') {
                $result .= $statements[$i] . '.';
            }
            $i++;
        }
        return $result;
    }

    /**
     * @param string $string
     * @param string $enc
     * @return string
     */
    public static function ucFirst(string $string, string $enc = 'UTF-8'): string
    {
        return
            mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
            mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }
}