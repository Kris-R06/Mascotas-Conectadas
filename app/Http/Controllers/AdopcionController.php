<?php

namespace App\Http\Controllers;

use App\Models\Adopcion;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdopcionController extends Controller
{
    public function index()
    {
        $adopciones = Adopcion::query()
            ->with(['mascota', 'adoptante'])
            ->latest()
            ->get();

        return view('adopciones.index', compact('adopciones'));
    }

    public function create()
    {
        // Obtener las mascotas disponibles para adopción
        $mascotas = Mascota::query()
            ->with('especie')
            ->where('estatus', 'adopcion')
            ->whereDoesntHave('adopciones')
            ->latest()
            ->get();

        return view('adopciones.create', compact('mascotas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'estatus' => ['required', Rule::in(['pendiente', 'en_revision', 'aprobada', 'rechazada', 'cancelada'])],
        ]);

        $data['user_id'] = $request->filled('user_id') ? $request->input('user_id') : null;

        Adopcion::create($data);

        return redirect()->route('adopciones.index');
    }

    public function edit(Adopcion $adopcion)
    {
        return view('adopciones.edit', compact('adopcion'));
    }

    public function update(Request $request, Adopcion $adopcion)
    {
        $data = $request->validate([
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'estatus' => ['required', Rule::in(['pendiente', 'en_revision', 'aprobada', 'rechazada', 'cancelada'])],
        ]);

        $data['user_id'] = $request->filled('user_id') ? $request->input('user_id') : null;

        $adopcion->update($data);

        return redirect()->route('adopciones.index');
    }

    public function destroy(Adopcion $adopcion)
    {
        $adopcion->delete();

        return redirect()->route('adopciones.index');
    }
    public function smartMatch(Request $request)
    {
        $request->validate([
            'q1' => 'required|integer',
            'q2' => 'required|integer',
            'q3' => 'required|integer',
            'has_yard' => 'required|boolean',
            'has_kids' => 'required|boolean',
        ]);

        $lifestyleScore = $request->q1 + $request->q2 + $request->q3;

        // Filtramos el modelo principal que usas en tu vista (asumo que se llama Adopcion o Reporte)
        // Usamos whereHas para filtrar por las características de la mascota vinculada
        $adopciones = \App\Models\Adopcion::where('estatus', 'pendiente')
            ->whereHas('mascota', function ($query) use ($lifestyleScore, $request) {
                
                // Filtro A: Energía
                $query->whereBetween('energy_level', [
                    max(1, $lifestyleScore - 2), 
                    min(10, $lifestyleScore + 2)
                ]);

                // Filtro B: Espacio
                if ($request->has_yard == '0') {
                    $query->where('space_needed', false);
                }

                // Filtro C: Niños
                if ($request->has_kids == '1') {
                    $query->where('kid_friendly', true);
                }

            })->with('mascota.especie')->latest()->get(); // Usa latest('fecha') si tu campo de fecha se llama así

        // Bandera para decirle a la vista que estamos en "Modo Smart Match"
        $isSmartMatch = true;

        // ¡Retornamos a la misma vista de siempre!
        return view('adopciones.index', compact('adopciones', 'lifestyleScore', 'isSmartMatch'));
    }
}