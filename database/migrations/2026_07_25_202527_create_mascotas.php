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
            $table->string('raza')->nullable();            
            $table->string('color')->nullable();
            $table->string('tamaño')->nullable();
            $table->string('edad')->nullable();            
            $table->string('foto')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('estatus')->default('safe')->comment('safe, adopcion, extraviado');
            $table->integer('energy_level')->default(5);
            $table->string('space_needed')->nullable();
            $table->string('qr')->nullable();
            $table->boolean('kid_friendly')->default(false);
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
