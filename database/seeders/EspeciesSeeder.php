<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EspeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especies = [
            ['nombre' => 'perro'],
            ['nombre' => 'gato'],
            ['nombre' => 'ave'],
            ['nombre' => 'conejo'],
            ['nombre' => 'roedor'],
            ['nombre' => 'reptil'],
            ['nombre' => 'otro']
        ];

        foreach ($especies as $especie) {
            Especie::create($especie);
        }
    }
}
