<?php

namespace Core\Abstractions;

use Core\Database\Schema;

abstract class Seeder
{
    abstract public static function handler();

    public static function create(string $table, array $column_value): void
    {
        Schema::insert($table, $column_value);
    }

    public static function factory(string $table, string $factory, int $count = 1): void
    {
        $factory = new $factory();

        if ($factory instanceof Factory) {
            for ($i = 0; $i < $count; $i++) {
                Schema::insert($table, $factory::frame())->get();
            }
        }
    }
}