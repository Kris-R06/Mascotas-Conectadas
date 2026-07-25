<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::query()
            ->with(['user', 'especie'])
            ->latest()
            ->get();

        return view('mascotas.index', compact('mascotas'));
    }

    public function create()
    {
        return view('mascotas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'especie_id' => ['required', 'exists:especies,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'raza' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'tamaño' => ['required', 'integer', 'min:0'],
            'edad' => ['required', 'integer', 'min:0'],
            'foto' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'estatus' => ['required', Rule::in(['safe', 'adopcion', 'extraviado'])],
            'energy_level' => ['required', 'integer', 'min:0'],
            'space_needed' => ['required', 'string', 'max:255'],
            'qr' => ['required', 'string', 'max:255'],
            'kid_friendly' => ['required', 'boolean'],
        ]);

        Mascota::create($data);

        return redirect()->route('mascotas.index');
    }

    public function edit(Mascota $mascota)
    {
        return view('mascotas.edit', compact('mascota'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'especie_id' => ['required', 'exists:especies,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'raza' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'tamaño' => ['required', 'integer', 'min:0'],
            'edad' => ['required', 'integer', 'min:0'],
            'foto' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'estatus' => ['required', Rule::in(['safe', 'adopcion', 'extraviado'])],
            'energy_level' => ['required', 'integer', 'min:0'],
            'space_needed' => ['required', 'string', 'max:255'],
            'qr' => ['required', 'string', 'max:255'],
            'kid_friendly' => ['required', 'boolean'],
        ]);

        $mascota->update($data);

        return redirect()->route('mascotas.index');
    }

    public function destroy(Mascota $mascota)
    {
        $mascota->delete();

        return redirect()->route('mascotas.index');
    }
}