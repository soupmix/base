<?php

namespace Soupmix;

use InvalidArgumentException;

abstract class AbstractQueryBuilder
{
    protected static $orderTypes  = ['asc', 'desc'];
    protected $soupmix            = null;
    protected $conn               = null;
    protected $collection         = null;
    protected $filters            = null;
    protected $andFilters         = null;
    protected $orFilters          = null;
    protected $fieldNames         = null;
    protected $distinctFieldName  = null;
    protected $sortFields         = null;
    protected $groupByFields      = null;
    protected $offset             = 0;
    protected $limit              = 25;
    protected $leftJoin           = null;
    protected $innerJoin           = null;
    protected $outerJoin           = null;
    protected $rightJoin           = null;

    public function __construct($collection, Base $soupmix)
    {
        $this->collection = $collection;
        $this->soupmix = $soupmix;
        $this->conn = $soupmix->getConnection();
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
        foreach ($fieldNames as $fieldName => $value) {
            $this->andFilters[$fieldName] = $value;
        }
        return $this;
    }

    public function orFilter($fieldName, $value=null)
    {
        if (!is_string($fieldName)) {
            throw new InvalidArgumentException('orFilter() Error: $fieldNames must be a string '
                . gettype($fieldName) . " given");
        }
        $this->orFilters[] = [$fieldName => $value];
        return $this;
    }

    public function orFilters(array $fieldNames)
    {
        foreach ($fieldNames as $value) {
            $fieldName = array_keys($value)[0];
            $value = $value[$fieldName];
            $this->orFilters[] = [$fieldName => $value];
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
    public function returnFields(array $fieldNames=null)
    {
        if($fieldNames !== null ) {
            foreach ($fieldNames as $fieldName) {
                $this->fieldNames[] = $fieldName;
            }
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

    public function sortFields(array $fieldNames=null)
    {
        if ($fieldNames !== null) {
            foreach ($fieldNames as $fieldName => $order) {
                $order = (in_array($order, self::$orderTypes)) ? $order : 'asc';
                $this->sortFields[$fieldName] = strtolower($order);
            }
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
        $this->distinctFieldName=$fieldName;
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

    public function leftJoin($joinCollection, array $filters, array $returnFieldNames=null)
    {
        return $this->addJoin($joinCollection, 'leftJoin', $filters, $returnFieldNames);
    }

    public function innerJoin($joinCollection, array $filters, array $returnFieldNames=null)
    {
        return $this->addJoin($joinCollection, 'innerJoin', $filters, $returnFieldNames);
    }

    public function join($joinCollection, array $filters, array $returnFieldNames=null)
    {
        return $this->innerJoin($joinCollection,  $filters, $returnFieldNames);
    }

    public function rightJoin($joinCollection, array $filters, array $returnFieldNames=null)
    {
        return $this->addJoin($joinCollection, 'rightJoin', $filters, $returnFieldNames);
    }

    public function outerJoin($joinCollection, array $filters, array $returnFieldNames=null)
    {
        return $this->addJoin($joinCollection, 'outerJoin', $filters, $returnFieldNames);
    }

    public function addJoin($joinCollection, $joinType, array $filters, array $returnFieldNames=null)
    {
        if (!is_string($joinCollection)) {
            throw new InvalidArgumentException('addJoin() for '.$joinType.' Error: $joinCollection must be a string, '
                . gettype($joinCollection) . " given");
        }
        $this->{$joinType}[$joinCollection] = [];
        $this->{$joinType}[$joinCollection]['relations'] = $filters;
        if (is_array($returnFieldNames)) {
            foreach ($returnFieldNames as $returnFieldName) {
                $this->{$joinType}[$joinCollection]['returnFields'][] = $returnFieldName;
            }
        }
        return $this;
    }

    abstract public function run();
}