<?php

namespace Core\Database\migrations;

use Core\Abstractions\Migration;
use Core\Database\Query\TableBuilder;
use Core\Database\Schema;

class PrivateSecretKeysTable extends Migration
{
    public static function up(): void
    {
        Schema::create('private_secret_keys', function (TableBuilder $table) {
            $table->id()->primaryKey()->autoIncrement();
            $table->longText('secret_key')->notNull();
            $table->id('user_id')->notNull()->unique()->foreignKey('users', 'id');
            $table->timeStamp('created_at');
            $table->timeStamp('updated_at');
            return $table;
        });
    }

    public static function down(): void
    {
        Schema::drop('jwt_secret_keys');
    }
}
