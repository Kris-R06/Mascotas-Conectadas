<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function indexExtraviados()
    {
        $reportes = Reporte::query()
            ->with(['tipo_reporte', 'mascota', 'user'])
            ->where('tipo_reporte_id', 1) // 1 = Extravío
            ->latest('fecha')
            ->get();

        // Te enviará a resources/views/extraviados/index.blade.php
        return view('extraviados.index', compact('reportes'));
    }

    // Método para cargar SOLO los avistamientos (Tipo 2)
    public function indexAvistamientos()
    {
        $reportes = Reporte::query()
            ->with(['tipo_reporte', 'mascota', 'user'])
            ->where('tipo_reporte_id', 2) // 2 = Avistamiento
            ->latest('fecha')
            ->get();

        // Te enviará a resources/views/avistamientos/index.blade.php
        return view('avistamientos.index', compact('reportes'));
    }

    public function create()
    {
        return view('reportes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_reporte_id' => ['required', 'exists:tipo_reportes,id'],
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'user_id' => ['required', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'ubicacion_lat' => ['required', 'string', 'max:255'],
            'ubicacion_lng' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'foto' => ['required', 'string', 'max:255'],
        ]);

        Reporte::create($data);

        return redirect()->route('reportes.index');
    }

    public function edit(Reporte $reporte)
    {
        return view('reportes.edit', compact('reporte'));
    }

    public function update(Request $request, Reporte $reporte)
    {
        $data = $request->validate([
            'tipo_reporte_id' => ['required', 'exists:tipo_reportes,id'],
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'user_id' => ['required', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'ubicacion_lat' => ['required', 'string', 'max:255'],
            'ubicacion_lng' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'foto' => ['required', 'string', 'max:255'],
        ]);

        $reporte->update($data);

        return redirect()->route('reportes.index');
    }

    public function destroy(Reporte $reporte)
    {
        $reporte->delete();

        return redirect()->route('reportes.index');
    }
}