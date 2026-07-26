<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especies = [
            'Perro',
            'Gato',
            'Ave',
            'Conejo',
            'Roedor',
            'Reptil',
            'Otro'
        ];

        foreach ($especies as $nombre) {
            Especie::firstOrCreate(['nombre' => $nombre]);
        }

        // Limpieza automática de duplicados (ej. 'perro' vs 'Perro')
        $all = Especie::all();
        $seen = [];
        foreach ($all as $item) {
            $key = strtolower(trim($item->nombre));
            if (isset($seen[$key])) {
                // Reasignar mascotas al ID principal antes de eliminar el duplicado
                DB::table('mascotas')->where('especie_id', $item->id)->update(['especie_id' => $seen[$key]]);
                $item->delete();
            } else {
                $seen[$key] = $item->id;
            }
        }
    }
}
