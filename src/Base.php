<?php

namespace Soupmix;

/*
Base Interface
*/

interface Base
{
    public function getConnection();

    public function create(string $collection, array $fields);

    public function drop(string $collection);

    public function truncate(string $collection);

    public function createIndexes(string $collection, array $indexes);

    public function insert(string $collection, array $values);

    public function get(string $collection, $docId);

    public function update(string $collection, array $filter, array $values);

    public function delete(string $collection, array $filter);

    public function find(
        string $collection,
        array $filter,
        array $fields = null,
        array $sort = null,
        int $start = 0,
        int $limit = 25
    );

    public function query(string $collection);
}
