<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoReporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'extravio'],
            ['nombre' => 'avistamiento']
        ];

        foreach ($tipos as $tipo) {
            TipoReporte::create($tipo);
        }
    }
}
