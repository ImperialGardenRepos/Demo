<?php

namespace ig\Datasource\Sphinx;

use Foolz\SphinxQL\Exception\ConnectionException;
use Foolz\SphinxQL\Exception\DatabaseException;
use Foolz\SphinxQL\Exception\SphinxQLException;
use Foolz\SphinxQL\SphinxQL;
use ig\Helpers\SphinxHelper;

class Query extends SphinxQL
{
    protected static $indexName;

    public static $fields;

    protected $orderField;

    protected $orderDirection = 'asc';

    public function __construct()
    {
        $connection = Connection::get();
        $this->from([static::$indexName]);
        parent::__construct($connection);
    }

    /**
     * Sets
     * 1) from depending on current index name
     * 2) max_matches option
     *
     * @return SphinxQL
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    public function compile(): SphinxQL
    {
        $this->from(static::$indexName);
        $this->option('max_matches', 5000);
        if($this->orderField !== null) {
            $this->orderBy($this->orderField, $this->orderDirection);
            unset($this->orderField);
        }
        return parent::compile();
    }

    /**
     * @param array $array
     * @return SphinxQL
     */
    public function setWhereFromArray(array $array): SphinxQL
    {
        foreach ($array as $propertyName => $values) {
            $columnName = SphinxHelper::convertKey($propertyName);
            if (!isset(static::$fields[$columnName])) {
                continue;
            }
            $values = SphinxHelper::convertValue($propertyName, $values, static::class);
            if (is_array($values)) {
                if (isset($values['FROM'], $values['TO'])) {
                    $this->where($columnName, '>=', (int)$values['FROM']);
                    $this->where($columnName, '<=', (int)$values['TO']);
                    continue;
                }
                if (count($values) > 1) {
                    $this->where($columnName, 'IN', $values);
                    continue;
                }
                $value = array_shift($values);
                $this->where($columnName, '=', $value);
                continue;
            }
            if (is_scalar($values)) {
                $this->where($columnName, '=', $values);
            }
        }
        return $this;
    }


    /**
     * @param array $currentValues
     * @param string $countField
     * @return int
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    public function countPropertyValueMatch(array $currentValues = [], string $countField = '*'): int
    {
        $countStr = $countField === '*' ? $countField : "distinct {$countField}";
        $result = $this
            ->select("count({$countStr}) as cnt")
            ->setWhereFromArray($currentValues)
            ->execute();
        if ($result->count() === 1) {
            $result = $result->fetchAllNum();
            $result = array_shift($result);
            return (int)$result[0];
        }
        return 0;
    }

    /**
     * @param array $propertyCodes
     * @param array $currentValues
     * @return array
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    public function countFacet(array $propertyCodes, array $currentValues = []): array
    {
        foreach ($propertyCodes as &$propertyCode) {
            $propertyCode = SphinxHelper::convertKey($propertyCode);
        }
        unset($propertyCode);
        $queryStringCommon = $this->select('count(*)')
            ->setWhereFromArray($currentValues)
            ->orderBy('id', 'ASC')
            ->compile()
            ->getCompiled();

        foreach ($propertyCodes as &$propertyCode) {
            $propertyCode = "FACET {$propertyCode}";
        }
        unset($propertyCode);

        $queryStringFacet = implode(' ', $propertyCodes);
        $multiResult = $this->query("{$queryStringCommon} {$queryStringFacet}")
            ->executeBatch()
            ->store();

        $facet = [];
        foreach ($multiResult->getStored() as $result) {
            while ($row = $result->fetchAssoc()) {
                if (count($row) === 1) {
                    /** This is result count row */
                    continue;
                }
                $property = array_keys($row)[0];
                $value = $row[$property];
                $property = SphinxHelper::revertKey($property);
                $facet[$property]['VALUES'][$value] = (int)$row['count(*)'];
            }
        }
        return $facet;
    }


    /**
     * @param string $field
     * @param string $direction
     */
    public function setOrder(string $field, string $direction = 'asc'): void
    {
        $this->orderField = $field;
        $this->orderDirection = $direction;
    }

    /**
     * Get catalog IDs for current section and filter.
     * @param array $currentValues
     * @param int $currentPage
     * @param int $pageSize
     * @return array
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    public function getItemIDs(array $currentValues = [], int $currentPage = 1, int $pageSize = 12): array
    {
        $offset = ($currentPage - 1) * $pageSize;
        $result = $this
            ->select('cat_id')
            ->setWhereFromArray($currentValues)
            ->limit($offset, $pageSize)
            ->groupBy('cat_id')
            ->execute()
            ->fetchAllAssoc();
        return array_column($result, 'cat_id');
    }
}