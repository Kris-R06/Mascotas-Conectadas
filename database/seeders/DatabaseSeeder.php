<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoUsersSeeder::class,
            EspeciesSeeder::class,
            TipoReporteSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'tipo_user_id' => 1,
                'telefono' => '1234567890',
                'direccion' => 'Direccion de prueba',
                'has_yard' => false,
                'kids' => false,
            ]
        );
    }
}
