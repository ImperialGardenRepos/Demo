<?php


namespace ig\Base;


use InvalidArgumentException;

class Registry
{
    /**
     * @var array
     * CATALOG_FILTER_VALUES
     * CATALOG_FILTER_PRODUCT_COUNT
     * CATALOG_FILTER_RESULT_LINK
     * CATALOG_FILTER_ALIAS
     */
    private $fields = [];
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if (!array_key_exists($key, $this->fields)) {
            throw new InvalidArgumentException("No field {$key} found in registry");
        }
        return $this->fields[$key];
    }

    public function set(string $key, $value): void
    {
        $this->fields[$key] = $value;
    }
}