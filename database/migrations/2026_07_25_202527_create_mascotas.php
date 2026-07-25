<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('especie_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');
            $table->string('raza');            
            $table->string('color');
            $table->integer('tamaño');
            $table->integer('edad');            
            $table->string('foto');
            $table->string('descripcion');
            $table->string('estatus')->comment('safe, adopcion, extraviado');
            $table->integer('energy_level');
            $table->string('space_needed');
            $table->string('qr');
            $table->boolean('kid_friendly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
