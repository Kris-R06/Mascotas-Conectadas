<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class EspeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especies = [
            ['id' => 1, 'nombre' => 'Perro'],
            ['id' => 2, 'nombre' => 'Gato'],
            ['id' => 3, 'nombre' => 'Ave'],
            ['id' => 4, 'nombre' => 'Conejo'],
            ['id' => 5, 'nombre' => 'Roedor'],
            ['id' => 6, 'nombre' => 'Reptil'],
            ['id' => 7, 'nombre' => 'Otro']
        ];

        foreach ($especies as $especie) {
            Especie::firstOrCreate(
                ['id' => $especie['id']],
                ['nombre' => $especie['nombre']]
            );
        }
    }
}
