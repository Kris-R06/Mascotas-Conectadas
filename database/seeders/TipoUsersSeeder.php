<?php

namespace Database\Seeders;

use App\Models\TipoUser;
use Illuminate\Database\Seeder;

class TipoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1, 'nombre' => 'Usuario'],
            ['id' => 2, 'nombre' => 'Refugio'],
            ['id' => 3, 'nombre' => 'Administrador'],
        ];

        foreach ($tipos as $tipo) {
            TipoUser::firstOrCreate(
                ['id' => $tipo['id']],
                ['nombre' => $tipo['nombre']]
            );
        }
    }
}
