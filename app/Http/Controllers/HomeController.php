<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Mascota;
use App\Models\Adopcion;
use App\Models\Especie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Conteos dinámicos para los widgets
        $extravios_count = Reporte::whereHas('tipo_reporte', function ($q) {
            $q->where('nombre', 'like', '%extravio%');
        })->orWhere('tipo_reporte_id', 1)->count();

        $avistamientos_count = Reporte::whereHas('tipo_reporte', function ($q) {
            $q->where('nombre', 'like', '%avistamiento%');
        })->orWhere('tipo_reporte_id', 2)->count();

        $adopciones_count = Mascota::where('estatus', 'adopcion')->count();

        $reencuentros_count = Adopcion::where('estatus', 'aprobada')->count();

        // 2. Extravíos urgentes
        $extraviosQuery = Reporte::with(['mascota.especie', 'mascota.user', 'user', 'tipo_reporte'])
            ->where(function($query) {
                $query->whereHas('tipo_reporte', function ($q) {
                    $q->where('nombre', 'like', '%extravio%');
                })->orWhere('tipo_reporte_id', 1);
            });

        if ($request->filled('especie_id')) {
            $extraviosQuery->whereHas('mascota', function ($q) use ($request) {
                $q->where('especie_id', $request->especie_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $extraviosQuery->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                  ->orWhereHas('mascota', function ($mq) use ($search) {
                      $mq->where('nombre', 'like', "%{$search}%")
                         ->orWhere('raza', 'like', "%{$search}%");
                  });
            });
        }

        $extravios = $extraviosQuery->latest('fecha')->take(6)->get();

        // 3. Avistamientos recientes
        $avistamientos = Reporte::with(['mascota.especie', 'user', 'tipo_reporte'])
            ->where(function($query) {
                $query->whereHas('tipo_reporte', function ($q) {
                    $q->where('nombre', 'like', '%avistamiento%');
                })->orWhere('tipo_reporte_id', 2);
            })
            ->latest('fecha')
            ->take(5)
            ->get();

        // 4. Mascotas en Adopción
        $mascotasAdopcionQuery = Mascota::with(['especie', 'user'])
            ->where('estatus', 'adopcion');

        if ($request->filled('especie_id')) {
            $mascotasAdopcionQuery->where('especie_id', $request->especie_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $mascotasAdopcionQuery->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('raza', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $mascotasAdopcion = $mascotasAdopcionQuery->latest()->take(8)->get();

        // 5. Especies para el filtro
        $especies = Especie::all();

        return view('home.index', compact(
            'extravios_count',
            'avistamientos_count',
            'adopciones_count',
            'reencuentros_count',
            'extravios',
            'avistamientos',
            'mascotasAdopcion',
            'especies'
        ));
    }
}
