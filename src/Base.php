<?php

namespace Soupmix;
/*
Base Interface
*/

interface Base
{
    public function __construct($config);

    public function connect($config);

    public function create($collection, $fields);

    public function drop($collection);

    public function truncate($collection);

    public function createIndexes($collection, $indexes);

    public function insert($collection, $values);

    public function get($collection, $docID);

    public function update($collection, $filter, $values);

    public function delete($collection, $filter);

    public function find($collection, $filter, $fields = null, $sort = null, $start = 0, $limit = 25, $debug = false);

    public function query($query);

    public static function buildFilter($filter);

}
