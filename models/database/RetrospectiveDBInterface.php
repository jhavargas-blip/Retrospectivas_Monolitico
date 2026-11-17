<?php

namespace app\models\database;

interface RetrospectiveDBInterface
{
    public function query_select(string $sql): array;
    public function query(string $sql): bool;
    public function get_last_insert_id(): int;
}