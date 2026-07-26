<?php

namespace Database\Seeders;

use App\Models\TipoReporte;
use Illuminate\Database\Seeder;

class TipoReporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1, 'nombre' => 'Extravío'],
            ['id' => 2, 'nombre' => 'Avistamiento']
        ];

        foreach ($tipos as $tipo) {
            TipoReporte::firstOrCreate(
                ['id' => $tipo['id']],
                ['nombre' => $tipo['nombre']]
            );
        }
    }
}
