<?php

namespace App\Seeds;

use Core\Abstractions\Seeder;
use Core\Database\Seed;

class MountSeed extends Seeder
{
    public function handler(): void
    {
        Seed::run('$table', [
            'column_1' => 'value_1',
            'column_2' => 'value_2',
            'column_3' => 'value_3'
        ]);
    }
}
