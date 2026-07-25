<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'user'],
            ['nombre' => 'refugio'],
            ['nombre' => 'admin'],
        ];

        foreach ($tipos as $tipo) {
            TipoUser::create($tipo);
        }
    }
}
