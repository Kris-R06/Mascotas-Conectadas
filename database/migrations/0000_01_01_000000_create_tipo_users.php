<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        // Insertar roles iniciales por defecto para evitar violaciones de llave foránea al registrar usuarios
        DB::table('tipo_users')->insert([
            ['id' => 1, 'nombre' => 'Usuario', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_users');
    }
};
