<?php

namespace Database\Seeders;

use App\Models\TipoUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Usuario',
            'Refugio',
            'Administrador'
        ];

        foreach ($tipos as $nombre) {
            TipoUser::firstOrCreate(['nombre' => $nombre]);
        }

        // Limpieza automática de duplicados (ej. 'user' vs 'Usuario')
        $all = TipoUser::all();
        $seen = [];
        foreach ($all as $item) {
            $key = strtolower(trim($item->nombre));
            if ($key === 'user') $key = 'usuario';
            if ($key === 'admin') $key = 'administrador';

            if (isset($seen[$key])) {
                DB::table('users')->where('tipo_user_id', $item->id)->update(['tipo_user_id' => $seen[$key]]);
                $item->delete();
            } else {
                $seen[$key] = $item->id;
            }
        }
    }
}
