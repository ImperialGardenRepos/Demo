<?php


namespace ig\Helpers;


class Text
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
}