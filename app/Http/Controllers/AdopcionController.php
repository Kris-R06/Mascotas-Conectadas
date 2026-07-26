<?php

namespace App\Http\Controllers;

use App\Models\Adopcion;
use App\Models\Mascota;
use App\Models\User;
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

    public function show(Adopcion $adopcion)
    {
        $adopcion->load(['mascota.especie', 'mascota.user']);

        return view('adopciones.show', compact('adopcion'));
    }

    public function edit(Adopcion $adopcion)
    {
        $adopcion->load(['mascota.especie']);

        $mascotas = Mascota::query()
            ->with('especie')
            ->latest()
            ->get();

        $usuarios = User::query()
            ->orderBy('name')
            ->get();

        return view('adopciones.edit', compact('adopcion', 'mascotas', 'usuarios'));
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
}