<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Mascota;
use Illuminate\Http\Request;
use App\Models\Especie;

class AvistamientoController extends Controller
{
    /**
     * Muestra la lista de avistamientos reportados.
     */
    public function index(Request $request)
    {
        $query = Reporte::query()
            ->with(['tipo_reporte', 'mascota.especie', 'user'])
            ->where('tipo_reporte_id', 2) // 2 = Avistamiento
            ->whereNull('mascota_id');     // Solo avistamientos independientes / no vinculados como pista a una mascota extraviada

        if ($request->get('filter') === 'mis_publicaciones') {
            $query->where('user_id', auth()->id());
        }

        $reportes = $query->latest('fecha')->get();

        return view('avistamientos.index', compact('reportes'));
    }

    /**
     * Muestra el formulario para crear un nuevo avistamiento independiente.
     */
    public function create()
    {
        $mascotas = Mascota::with('especie')->get();
        $especies = Especie::all();
        return view('avistamientos.create', compact('mascotas', 'especies'));
    }

    /**
     * Guarda un nuevo avistamiento en la tabla 'reportes'.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mascota_id' => ['nullable', 'exists:mascotas,id'],
            'user_id' => ['required', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'ubicacion_lat' => ['required', 'string', 'max:255'],
            'ubicacion_lng' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'foto' => ['nullable'],
        ]);

        // Se asigna automáticamente el tipo_reporte_id = 2 (Avistamiento)
        $data['tipo_reporte_id'] = 2;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('avistamientos', 'public');
            $data['foto'] = $path;
        } elseif (is_string($request->foto) && !empty($request->foto)) {
            $data['foto'] = $request->foto;
        } else {
            $data['foto'] = 'mascotas/default.jpg';
        }

        Reporte::create($data);

        if ($request->filled('redirect_to_extraviados')) {
            return redirect()->route('extraviados.index')->with('success', 'Avistamiento registrado y vinculado a la mascota extraviada.');
        }

        return redirect()->route('avistamientos.index')->with('success', 'Avistamiento registrado correctamente.');
    }

    /**
     * Muestra el formulario para editar un avistamiento.
     */
    public function edit(Reporte $reporte)
    {
        $mascotas = Mascota::with('especie')->get();
        $especies = Especie::all();
        return view('avistamientos.edit', compact('reporte', 'mascotas', 'especies'));
    }

    /**
     * Actualiza el avistamiento.
     */
    public function update(Request $request, Reporte $reporte)
    {
        $data = $request->validate([
            'mascota_id' => ['nullable', 'exists:mascotas,id'],
            'fecha' => ['required', 'date'],
            'ubicacion_lat' => ['required', 'string', 'max:255'],
            'ubicacion_lng' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'foto' => ['nullable'],
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('avistamientos', 'public');
            $data['foto'] = $path;
        }

        $reporte->update($data);

        return redirect()->route('avistamientos.index')->with('success', 'Avistamiento actualizado correctamente.');
    }

    /**
     * Elimina el avistamiento.
     */
    public function destroy(Reporte $reporte)
    {
        $reporte->delete();

        return redirect()->route('avistamientos.index')->with('success', 'Avistamiento eliminado correctamente.');
    }
}
