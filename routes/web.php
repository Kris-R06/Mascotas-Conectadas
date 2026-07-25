<?php

use App\Http\Controllers\AdopcionController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TipoReporteController;
use App\Http\Controllers\TipoUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tipo-users', TipoUserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('especies', EspecieController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('tipo-reportes', TipoReporteController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('mascotas', MascotaController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('reportes', ReporteController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('adopciones', AdopcionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
