<?php


namespace ig\Datasource\Sphinx;

use Bitrix\Main\Config\Configuration;
use Foolz\SphinxQL\Exception\ConnectionException;

class Connection
{

    private static $connection;

    /**
     * @return \Foolz\SphinxQL\Drivers\Mysqli\Connection
     * @throws ConnectionException
     */
    public static function get(): \Foolz\SphinxQL\Drivers\Mysqli\Connection
    {
        if (self::$connection === null) {
            $sphinxSettings = Configuration::getInstance()->get('sphinx');
            self::$connection = new \Foolz\SphinxQL\Drivers\Mysqli\Connection();
            self::$connection->setParams(
                [
                    'host' => $sphinxSettings['host'],
                    'port' => $sphinxSettings['port'],
                ]
            );
            self::$connection->connect();
        }
        return self::$connection;
    }

    private function __construct()
    {
    }
}