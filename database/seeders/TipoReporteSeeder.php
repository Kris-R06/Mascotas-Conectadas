<?php

namespace Database\Seeders;

use App\Models\TipoReporte;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoReporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Extravío',
            'Avistamiento'
        ];

        foreach ($tipos as $nombre) {
            TipoReporte::firstOrCreate(['nombre' => $nombre]);
        }

        // Limpieza automática de duplicados (ej. 'extravio' vs 'Extravío')
        $all = TipoReporte::all();
        $seen = [];
        foreach ($all as $item) {
            $key = strtolower(trim(str_replace(['í', 'i'], 'i', $item->nombre)));
            if (isset($seen[$key])) {
                DB::table('reportes')->where('tipo_reporte_id', $item->id)->update(['tipo_reporte_id' => $seen[$key]]);
                $item->delete();
            } else {
                $seen[$key] = $item->id;
            }
        }
    }
}
