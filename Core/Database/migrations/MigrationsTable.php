<?php

namespace Core\Database\migrations;

use Core\Abstractions\Migration;
use Core\Database\DbTable;
use Core\Database\Schema;

class MigrationsTable extends Migration
{
    public static function up(): void
    {
        Schema::create('migrations', $table = new DbTable(), function () use (&$table) {
            $table->id()->unique()->notNull()->autoIncrement();
            $table->varchar('migration', 255)->unique()->notNull();
            $table->int('step');

            return $table->columns;
        });
    }

    public static function down(): void
    {
        Schema::dropIfExists('migrations');
    }
}
