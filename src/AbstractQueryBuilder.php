<?php

namespace Soupmix;

use InvalidArgumentException;

abstract class AbstractQueryBuilder
{
    private static $orderTypes  = ['asc', 'desc'];
    private $soupmix            = null;
    private $collection         = null;
    private $filters            = null;
    private $andFilters         = null;
    private $orFilters          = null;
    private $fieldNames         = null;
    private $distinctFieldName  = null;
    private $sortFields         = null;
    private $groupByFields      = null;
    private $offset             = 0;
    private $limit              = 25;
    private $join               = null;

    public function __construct($collection, Base $soupmix)
    {
        $this->collection = $collection;
        $this->soupmix = $soupmix;

        return $this;
    }

    public function andFilter($fieldName, $value=null)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('andFilters() Error: $fieldNames must be a string '
                . gettype($fieldName) . " given");
        }
        $this->andFilters[$fieldName] = $value;
        return $this;
    }

    public function andFilters(array $fieldNames)
    {
        foreach ($fieldNames as $field=>$value) {
            $this->andFilters[$fieldNames] = $value;
        }
        return $this;
    }

    public function orFilter($fieldName, $value=null)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('orFilter() Error: $fieldNames must be a string '
                . gettype($fieldName) . " given");
        }
        $this->orFilters[] = [$fieldName=>$value];
        return $this;
    }

    public function orFilters(array $fieldNames)
    {
        foreach ($fieldNames as $fieldName=>$value) {
            $this->orFilters[] = [$fieldName=>$value];
        }
        return $this;
    }

    public function returnField($fieldName)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('returnField() Error: $fieldName must be a string, '
                . gettype($fieldName) . " given");
        }
        $this->fieldNames[]=$fieldName;
        return $this;
    }
    public function returnFields(array $fieldNames)
    {
         foreach ($fieldNames as $fieldName) {
            $this->fieldNames[] = $fieldName;
        }
        return $this;
    }

    public function sortField($fieldName, $order)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('sortField() Error: $fieldName must be a string, '
                . gettype($fieldName) . " given");
        }
        $order = (in_array($order, self::$orderTypes)) ? $order : 'asc';
        $this->sortFields[$fieldName] = strtolower($order);
        return $this;
    }

    public function sortFields(array $fieldNames)
    {
        foreach ($fieldNames as $fieldName => $order) {
            $order = (in_array($order, self::$orderTypes)) ? $order : 'asc';
            $this->sortFields[$fieldName] = strtolower($order);
        }
        return $this;
    }

    public function groupByField($fieldName)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('groupByField() Error: $fieldName must be a string, '
                . gettype($fieldName) . " given");
        }
        $this->fieldNames[]=$fieldName;
        return $this;
    }

    public function groupByFields(array $fieldNames)
    {
        foreach ($fieldNames as $fieldName) {
            $this->groupByFields[] = $fieldName;
        }
        return $this;
    }

    public function distinctField($fieldName)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('distinctField() Error: $fieldName must be a string, '
                . gettype($fieldName) . " given");
        }
        $this->distinctFieldName[]=$fieldName;
        return $this;
    }

    public function offset($offset)
    {
        if (!is_integer($offset)) {
            throw new InvalidArgumentException('offset() Error: $fieldNames must be an integer, '
                . gettype($offset) . " given");
        }
        $this->offset = $offset;
        return $this;
    }

    public function limit($limit)
    {
        if (!is_integer($limit)) {
            throw new InvalidArgumentException('limit() Error: $fieldNames must be an integer, '
                . gettype($limit) . " given");
        }
        $this->limit = $limit;
        return $this;
    }

    public function join($joinCollection, array $filters, array $returnFieldNames=null)
    {
        if (!is_string($joinCollection)) {
            throw new InvalidArgumentException('join() Error: $joinCollection must be a string, '
                . gettype($joinCollection) . " given");
        }
        $this->join[$joinCollection] = [];
        foreach ($filters as $sourceField=>$joinField) {
            $this->join[$joinCollection]['relations'][$sourceField] = $joinField;
        }
        if (is_array($returnFieldNames)) {
            foreach ($returnFieldNames as $returnFieldName) {
                $this->join[$joinCollection]['returnFields'] = $returnFieldName;
            }
        }
        return $this;
    }

    abstract public function run();
}