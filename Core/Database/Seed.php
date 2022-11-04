<?php

namespace Core\Database;

class Seed
{
    public static function run(string $table, array $column_value): void
    {
        Schema::insert($table, $column_value);
    }
}
