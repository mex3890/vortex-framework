<?php

namespace Core\Database\migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class MigrationsTable extends Migration
{
    public static function up(): void
    {
        Schema::create('migrations', function (TableBuilder $table) {
            $table->id()->unique()->notNull()->autoIncrement();
            $table->varchar('migration', 255)->unique()->notNull();
            $table->int('step');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('migrations');
    }
}
