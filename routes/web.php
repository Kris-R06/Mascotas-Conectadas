<?php

use App\Http\Controllers\AdopcionController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TipoReporteController;
use App\Http\Controllers\TipoUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerfilController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/adopciones', [AdopcionController::class, 'index'])->name('index');
    Route::get('/adopciones/create', [AdopcionController::class, 'create'])->name('create');
    Route::post('/adopciones', [AdopcionController::class, 'store'])->name('store');
    Route::get('/adopciones/edit/{adopcion}', [AdopcionController::class, 'edit'])->name('edit');
    Route::put('/adopciones/{adopcion}', [AdopcionController::class, 'update'])->name('update');
    Route::delete('/adopciones/destroy/{adopcion}', [AdopcionController::class, 'destroy'])->name('destroy');

    // Rutas de Especies
    Route::get('/especies', [EspecieController::class, 'index'])->name('especies.index');
    Route::get('/especies/create', [EspecieController::class, 'create'])->name('especies.create');
    Route::post('/especies', [EspecieController::class, 'store'])->name('especies.store');
    Route::get('/especies/{especie}/edit', [EspecieController::class, 'edit'])->name('especies.edit');
    Route::put('/especies/{especie}', [EspecieController::class, 'update'])->name('especies.update');
    Route::delete('/especies/{especie}', [EspecieController::class, 'destroy'])->name('especies.destroy');

    // Rutas de Mascotas
    Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas.index');
    Route::get('/mascotas/create', [MascotaController::class, 'create'])->name('mascotas.create');
    Route::post('/mascotas', [MascotaController::class, 'store'])->name('mascotas.store');
    Route::get('/mascotas/{mascota}/edit', [MascotaController::class, 'edit'])->name('mascotas.edit');
    Route::put('/mascotas/{mascota}', [MascotaController::class, 'update'])->name('mascotas.update');
    Route::delete('/mascotas/{mascota}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');

    // Rutas de Reportes
    Route::get('/extraviados', [ReporteController::class, 'indexExtraviados'])->name('extraviados.index');
    Route::get('/avistamientos', [ReporteController::class, 'indexAvistamientos'])->name('avistamientos.index');
    Route::get('/reportes/create', [ReporteController::class, 'create'])->name('reportes.create');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
    Route::get('/reportes/{reporte}/edit', [ReporteController::class, 'edit'])->name('reportes.edit');
    Route::put('/reportes/{reporte}', [ReporteController::class, 'update'])->name('reportes.update');
    Route::delete('/reportes/{reporte}', [ReporteController::class, 'destroy'])->name('reportes.destroy');

    // Rutas de Tipo de Reportes
    Route::get('/tipo-reportes', [TipoReporteController::class, 'index'])->name('tipo-reportes.index');
    Route::get('/tipo-reportes/create', [TipoReporteController::class, 'create'])->name('tipo-reportes.create');
    Route::post('/tipo-reportes', [TipoReporteController::class, 'store'])->name('tipo-reportes.store');
    Route::get('/tipo-reportes/{tipoReporte}/edit', [TipoReporteController::class, 'edit'])->name('tipo-reportes.edit');
    Route::put('/tipo-reportes/{tipoReporte}', [TipoReporteController::class, 'update'])->name('tipo-reportes.update');
    Route::delete('/tipo-reportes/{tipoReporte}', [TipoReporteController::class, 'destroy'])->name('tipo-reportes.destroy');

    // Rutas de Tipo de Usuarios
    Route::get('/tipo-users', [TipoUserController::class, 'index'])->name('tipo-users.index');
    Route::get('/tipo-users/create', [TipoUserController::class, 'create'])->name('tipo-users.create');
    Route::post('/tipo-users', [TipoUserController::class, 'store'])->name('tipo-users.store');
    Route::get('/tipo-users/{tipoUser}/edit', [TipoUserController::class, 'edit'])->name('tipo-users.edit');
    Route::put('/tipo-users/{tipoUser}', [TipoUserController::class, 'update'])->name('tipo-users.update');
    Route::delete('/tipo-users/{tipoUser}', [TipoUserController::class, 'destroy'])->name('tipo-users.destroy');

    // Rutas De Perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::patch('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

