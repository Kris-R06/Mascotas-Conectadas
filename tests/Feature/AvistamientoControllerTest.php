<?php

use App\Models\Especie;
use App\Models\Mascota;
use App\Models\Reporte;
use App\Models\TipoReporte;
use App\Models\TipoUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the avistamiento form only lists mascotas with an active extravio report', function () {
    TipoUser::create(['nombre' => 'Usuario']);

    $user = User::factory()->create();
    $especie = Especie::create(['nombre' => 'Perro']);

    $tipoExtravio = TipoReporte::firstOrCreate(['nombre' => 'Extravío']);

    $mascotaExtraviada = Mascota::create([
        'user_id' => $user->id,
        'especie_id' => $especie->id,
        'nombre' => 'Luna',
        'raza' => 'Mestizo',
        'estatus' => 'extraviado',
    ]);

    Reporte::create([
        'tipo_reporte_id' => $tipoExtravio->id,
        'mascota_id' => $mascotaExtraviada->id,
        'user_id' => $user->id,
        'fecha' => now()->toDateString(),
        'ubicacion_lat' => '25.7797',
        'ubicacion_lng' => '-97.4978',
        'descripcion' => 'Mascota reportada como extraviada',
        'foto' => 'mascotas/default.jpg',
    ]);

    $otraMascota = Mascota::create([
        'user_id' => $user->id,
        'especie_id' => $especie->id,
        'nombre' => 'Max',
        'raza' => 'Pastor Alemán',
        'estatus' => 'safe',
    ]);

    $response = $this->actingAs($user)->get(route('avistamientos.create'));

    $response->assertOk();
    $response->assertSee($mascotaExtraviada->nombre);
    $response->assertDontSee($otraMascota->nombre);
});
