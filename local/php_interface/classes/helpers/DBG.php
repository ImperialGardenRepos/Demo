<?php
/**
 * @package DBG
 * @author Oleg Makeev
 * @license MIT
 */

namespace ig\Helpers;


class DBG
{

    private static $timeMarks = [];

    /**
     * @param $variable
     * @param bool $die
     * @param string $dieText
     */
    public static function pre($variable, $die = false, $dieText = 'Die in DBG::pre()'): void
    {
        echo '<pre>';
        /** @noinspection ForgottenDebugOutputInspection */
        print_r($variable);
        echo '</pre>';
        if ($die) {
            die($dieText);
        }
    }

    /**
     * @param array $variables
     */
    public static function vd(...$variables): void
    {
        foreach ($variables as $variable) {
            echo '<pre>';
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump($variable);
            echo '</pre>';
        }
    }

    /**
     * @param array $variables
     */
    public static function vde(...$variables): void
    {
        foreach ($variables as $variable) {
            echo '<pre>';
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump($variable);
            echo '</pre>';
        }
        die('Die in DBG::vde()');
    }

    public static function markTime($label): void
    {
        static::$timeMarks[$label] = time();
    }

    public static function getTimeMarks(): array
    {
        return static::$timeMarks;
    }
}