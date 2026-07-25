<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        $reportes = Reporte::query()
            ->with(['tipo_reporte', 'mascota', 'user'])
            ->latest('fecha')
            ->get();

        return view('reportes.index', compact('reportes'));
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