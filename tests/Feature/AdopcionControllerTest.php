<?php

use App\Models\Adopcion;
use App\Models\Especie;
use App\Models\Mascota;
use App\Models\TipoUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates an adoption without requiring a new mascota_id and hides approved ones from the index', function () {
    $tipoUser = TipoUser::create(['nombre' => 'Propietario']);
    $owner = User::create([
        'name' => 'Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
        'tipo_user_id' => $tipoUser->id,
        'telefono' => '111111111',
        'direccion' => 'Test address',
    ]);

    $adoptante = User::create([
        'name' => 'Adoptante',
        'email' => 'adoptante@example.com',
        'password' => bcrypt('password'),
        'tipo_user_id' => $tipoUser->id,
        'telefono' => '222222222',
        'direccion' => 'Another address',
    ]);

    $especie = Especie::create(['nombre' => 'Perro']);
    $mascota = Mascota::create([
        'user_id' => $owner->id,
        'especie_id' => $especie->id,
        'nombre' => 'Firulais',
        'raza' => 'Mestizo',
        'color' => 'Cafe',
        'tamaño' => 'Mediano',
        'edad' => 3,
        'foto' => '',
        'descripcion' => 'Mascota de prueba',
        'estatus' => 'adopcion',
        'energy_level' => 5,
        'space_needed' => false,
        'qr' => 'qr-test',
        'kid_friendly' => true,
    ]);

    $adopcion = Adopcion::create([
        'mascota_id' => $mascota->id,
        'user_id' => null,
        'estatus' => 'pendiente',
    ]);

    $this->actingAs($owner);

    $response = $this->put(route('adopciones.update', $adopcion), [
        'user_id' => $adoptante->id,
        'estatus' => 'aprobada',
    ]);

    $response->assertRedirect(route('adopciones.index'));
    $this->assertDatabaseHas('adopciones', [
        'id' => $adopcion->id,
        'mascota_id' => $mascota->id,
        'user_id' => $adoptante->id,
        'estatus' => 'aprobada',
    ]);

    $ownerIndexResponse = $this->actingAs($owner)->get(route('adopciones.index'));
    $ownerIndexResponse->assertOk();
    $ownerIndexResponse->assertSee('Firulais');

    $viewerIndexResponse = $this->actingAs($adoptante)->get(route('adopciones.index'));
    $viewerIndexResponse->assertOk();
    $viewerIndexResponse->assertDontSee('Firulais');
});

it('blocks non-owners from viewing approved adoption details', function () {
    $tipoUser = TipoUser::create(['nombre' => 'Propietario']);
    $owner = User::create([
        'name' => 'Owner',
        'email' => 'owner2@example.com',
        'password' => bcrypt('password'),
        'tipo_user_id' => $tipoUser->id,
        'telefono' => '111111112',
        'direccion' => 'Test address 2',
    ]);

    $viewer = User::create([
        'name' => 'Viewer',
        'email' => 'viewer@example.com',
        'password' => bcrypt('password'),
        'tipo_user_id' => $tipoUser->id,
        'telefono' => '222222223',
        'direccion' => 'Another address 2',
    ]);

    $especie = Especie::create(['nombre' => 'Gato']);
    $mascota = Mascota::create([
        'user_id' => $owner->id,
        'especie_id' => $especie->id,
        'nombre' => 'Michi',
        'raza' => 'Siamés',
        'color' => 'Blanco',
        'tamaño' => 'Pequeño',
        'edad' => 2,
        'foto' => '',
        'descripcion' => 'Mascota de prueba 2',
        'estatus' => 'safe',
        'energy_level' => 4,
        'space_needed' => false,
        'qr' => 'qr-test-2',
        'kid_friendly' => false,
    ]);

    $adopcion = Adopcion::create([
        'mascota_id' => $mascota->id,
        'user_id' => $viewer->id,
        'estatus' => 'aprobada',
    ]);

    $this->actingAs($viewer)->get(route('adopciones.show', $adopcion))->assertForbidden();
    $this->actingAs($owner)->get(route('adopciones.show', $adopcion))->assertOk();
});
