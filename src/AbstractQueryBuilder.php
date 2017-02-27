<?php

namespace Soupmix;

abstract class AbstractQueryBuilder
{
    protected static $orderTypes  = ['asc', 'desc'];
    protected $soupmix;
    protected $conn;
    protected $collection;
    protected $filters;
    protected $andFilters;
    protected $orFilters;
    protected $fieldNames;
    protected $distinctFieldName;
    protected $sortFields;
    protected $groupByFields;
    protected $offset = 0;
    protected $limit = 25;
    protected $leftJoin;
    protected $innerJoin;
    protected $outerJoin;
    protected $rightJoin;

    public function __construct(string $collection, Base $soupmix)
    {
        $this->collection = $collection;
        $this->soupmix = $soupmix;
        $this->conn = $soupmix->getConnection();
    }

    public function andFilter(string $fieldName, $value = null)
    {
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

    public function orFilter(string $fieldName, $value = null)
    {
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

    public function returnField(string $fieldName)
    {

        $this->fieldNames[]=$fieldName;
        return $this;
    }
    public function returnFields(array $fieldNames = null)
    {
        if ($fieldNames !== null) {
            foreach ($fieldNames as $fieldName) {
                $this->fieldNames[] = $fieldName;
            }
        }
        return $this;
    }

    public function sortField(string $fieldName, string $order)
    {
        $order = in_array($order, self::$orderTypes, true) ? $order : 'asc';
        $this->sortFields[$fieldName] = strtolower($order);
        return $this;
    }

    public function sortFields(array $fieldNames = null)
    {
        if ($fieldNames !== null) {
            foreach ($fieldNames as $fieldName => $order) {
                $order = in_array($order, self::$orderTypes, true) ? $order : 'asc';
                $this->sortFields[$fieldName] = strtolower($order);
            }
        }
        return $this;
    }

    public function groupByField(string $fieldName)
    {
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

    public function distinctField(string $fieldName)
    {
        $this->distinctFieldName=$fieldName;
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function leftJoin(string $joinCollection, array $filters, array $returnFieldNames = null)
    {
        return $this->addJoin($joinCollection, 'leftJoin', $filters, $returnFieldNames);
    }

    public function innerJoin(string $joinCollection, array $filters, array $returnFieldNames = null)
    {
        return $this->addJoin($joinCollection, 'innerJoin', $filters, $returnFieldNames);
    }

    public function join(string $joinCollection, array $filters, array $returnFieldNames = null)
    {
        return $this->innerJoin($joinCollection, $filters, $returnFieldNames);
    }

    public function rightJoin(string $joinCollection, array $filters, array $returnFieldNames = null)
    {
        return $this->addJoin($joinCollection, 'rightJoin', $filters, $returnFieldNames);
    }

    public function outerJoin(string $joinCollection, array $filters, array $returnFieldNames = null)
    {
        return $this->addJoin($joinCollection, 'outerJoin', $filters, $returnFieldNames);
    }

    private function addJoin(string $joinCollection, $joinType, array $filters, array $returnFieldNames = null)
    {
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
