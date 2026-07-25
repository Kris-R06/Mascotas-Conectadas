<?php

namespace App\Http\Controllers;

use App\Models\Adopcion;
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
        return view('adopciones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'user_id' => ['required', 'exists:users,id'],
            'estatus' => ['required', Rule::in(['pendiente', 'en_revision', 'aprobada', 'rechazada', 'cancelada'])],
        ]);

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
            'user_id' => ['required', 'exists:users,id'],
            'estatus' => ['required', Rule::in(['pendiente', 'en_revision', 'aprobada', 'rechazada', 'cancelada'])],
        ]);

        $adopcion->update($data);

        return redirect()->route('adopciones.index');
    }

    public function destroy(Adopcion $adopcion)
    {
        $adopcion->delete();

        return redirect()->route('adopciones.index');
    }
}