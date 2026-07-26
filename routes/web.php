<?php

use App\Http\Controllers\AdopcionController;
use App\Http\Controllers\AvistamientoController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\ExtraviadoController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\TipoReporteController;
use App\Http\Controllers\TipoUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PosterController;

Route::get('/', function () {
    return view('welcome');
});

    Route::get('/adopciones', [AdopcionController::class, 'index'])->name('adopciones.index');
    Route::get('/extraviados', [ExtraviadoController::class, 'index'])->name('extraviados.index');
    Route::get('/avistamientos', [AvistamientoController::class, 'index'])->name('avistamientos.index');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/adopciones/create', [AdopcionController::class, 'create'])->name('adopciones.create');
    Route::get('/adopciones/{adopcion}', [AdopcionController::class, 'show'])->name('adopciones.show');
    Route::post('/adopciones', [AdopcionController::class, 'store'])->name('adopciones.store');
    Route::get('/adopciones/edit/{adopcion}', [AdopcionController::class, 'edit'])->name('adopciones.edit');
    Route::put('/adopciones/{adopcion}', [AdopcionController::class, 'update'])->name('adopciones.update');
    Route::delete('/adopciones/destroy/{adopcion}', [AdopcionController::class, 'destroy'])->name('adopciones.destroy');
    Route::get('/adopciones/smart-match', [AdopcionController::class, 'smartMatch'])->name('adopciones.smart-match');

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
    Route::get('/mascotas/{mascota}', [MascotaController::class, 'show'])->name('mascotas.show');
    Route::get('/mascotas/{mascota}/edit', [MascotaController::class, 'edit'])->name('mascotas.edit');
    Route::put('/mascotas/{mascota}', [MascotaController::class, 'update'])->name('mascotas.update');
    Route::delete('/mascotas/{mascota}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');

    // Rutas de Extraviados (ExtraviadoController)
    Route::get('/extraviados', [ExtraviadoController::class, 'index'])->name('extraviados.index');
    Route::get('/extraviados/create', [ExtraviadoController::class, 'create'])->name('extraviados.create');
    Route::post('/extraviados', [ExtraviadoController::class, 'store'])->name('extraviados.store');
    Route::get('/extraviados/{reporte}/edit', [ExtraviadoController::class, 'edit'])->name('extraviados.edit');
    Route::put('/extraviados/{reporte}', [ExtraviadoController::class, 'update'])->name('extraviados.update');
    Route::delete('/extraviados/{reporte}', [ExtraviadoController::class, 'destroy'])->name('extraviados.destroy');
    Route::get('/extraviados/{reporte}', [ExtraviadoController::class, 'show'])->name('extraviados.show');

    // Rutas de Avistamientos (AvistamientoController)
    Route::get('/avistamientos', [AvistamientoController::class, 'index'])->name('avistamientos.index');
    Route::get('/avistamientos/create', [AvistamientoController::class, 'create'])->name('avistamientos.create');
    Route::post('/avistamientos', [AvistamientoController::class, 'store'])->name('avistamientos.store');
    Route::get('/avistamientos/{reporte}/edit', [AvistamientoController::class, 'edit'])->name('avistamientos.edit');
    Route::put('/avistamientos/{reporte}', [AvistamientoController::class, 'update'])->name('avistamientos.update');
    Route::delete('/avistamientos/{reporte}', [AvistamientoController::class, 'destroy'])->name('avistamientos.destroy');

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

    // Ruta Poster
    Route::get('/mascotas/{mascota}/poster', [PosterController::class, 'generar'])->name('mascotas.poster');
});
