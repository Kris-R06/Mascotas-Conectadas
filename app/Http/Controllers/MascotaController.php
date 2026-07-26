<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Especie;
use Illuminate\Support\Facades\Storage;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::with('especie')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('mascotas.index', compact('mascotas'));
    }

    public function create()
    {
        if (Especie::count() === 0) {
            Especie::firstOrCreate(['id' => 1], ['nombre' => 'Perro']);
            Especie::firstOrCreate(['id' => 2], ['nombre' => 'Gato']);
            Especie::firstOrCreate(['id' => 3], ['nombre' => 'Ave']);
            Especie::firstOrCreate(['id' => 4], ['nombre' => 'Otro']);
        }

        $especies = Especie::all();
        return view('mascotas.create', compact('especies'));
    }

    public function store(Request $request)
    {
        if (Especie::count() === 0) {
            Especie::firstOrCreate(['id' => 1], ['nombre' => 'Perro']);
            Especie::firstOrCreate(['id' => 2], ['nombre' => 'Gato']);
            Especie::firstOrCreate(['id' => 3], ['nombre' => 'Ave']);
            Especie::firstOrCreate(['id' => 4], ['nombre' => 'Otro']);
        }

        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'especie_id'   => 'required|exists:especies,id',
            'raza'         => 'nullable|string|max:255',
            'color'        => 'nullable|string|max:255',
            'tamaño'       => 'nullable|string|max:255',
            'edad'         => 'nullable|string|max:255',
            'estatus'      => 'required|in:safe,adopcion,extraviado',
            'descripcion'  => 'nullable|string',
            'descripción'  => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'energy_level' => 'nullable|integer|min:1|max:10',
        ]);

        // Asignación de valores por defecto para evitar NOT NULL Constraint Violation si los campos vienen vacíos
        $validated['raza']         = !empty($validated['raza']) ? $validated['raza'] : 'Mestizo';
        $validated['color']        = !empty($validated['color']) ? $validated['color'] : 'No especificado';
        $validated['tamaño']       = !empty($validated['tamaño']) ? $validated['tamaño'] : 'Mediano';
        $validated['edad']         = !empty($validated['edad']) ? $validated['edad'] : 'No especificada';
        $validated['descripcion']  = !empty($validated['descripcion']) ? $validated['descripcion'] : (!empty($validated['descripción']) ? $validated['descripción'] : 'Sin descripción adicional.');
        unset($validated['descripción']);

        $validated['energy_level'] = $request->input('energy_level', 5);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('mascotas', 'public');
        } else {
            $validated['foto'] = 'mascotas/default.jpg';
        }

        $validated['user_id']      = Auth::id();
        $validated['space_needed'] = $request->has('space_needed') ? 'Espacio amplio' : 'Espacio estándar';
        $validated['kid_friendly'] = $request->has('kid_friendly');
        $validated['qr']           = (string) Str::uuid(); 

        Mascota::create($validated);

        return redirect()->route('mascotas.index')
                        ->with('success', '¡Mascota registrada con éxito!');
    }

    public function show($id)
    {
        $mascota = Mascota::with('especie')->findOrFail($id);
        if ($mascota->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta mascota.');
        }

        return view('mascotas.show', compact('mascota'));
    }

    public function edit($id)
    {
        $mascota = Mascota::findOrFail($id);
        
        if ($mascota->user_id !== auth()->id()) abort(403);
        
        if (Especie::count() === 0) {
            Especie::firstOrCreate(['id' => 1], ['nombre' => 'Perro']);
            Especie::firstOrCreate(['id' => 2], ['nombre' => 'Gato']);
        }

        $especies = Especie::all();
        return view('mascotas.edit', compact('mascota', 'especies'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        if ($mascota->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta mascota.');
        }

        $validated = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'especie_id'   => ['required', 'exists:especies,id'],
            'raza'         => ['nullable', 'string', 'max:255'],
            'color'        => ['nullable', 'string', 'max:255'],
            'tamaño'       => ['nullable', 'string', 'max:255'],
            'edad'         => ['nullable', 'string', 'max:255'],
            'estatus'      => ['required', 'in:safe,adopcion,extraviado'],
            'descripcion'  => ['nullable', 'string'],
            'foto'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'energy_level' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $validated['raza']         = !empty($validated['raza']) ? $validated['raza'] : 'Mestizo';
        $validated['color']        = !empty($validated['color']) ? $validated['color'] : 'No especificado';
        $validated['tamaño']       = !empty($validated['tamaño']) ? $validated['tamaño'] : 'Mediano';
        $validated['edad']         = !empty($validated['edad']) ? $validated['edad'] : 'No especificada';
        $validated['descripcion']  = !empty($validated['descripcion']) ? $validated['descripcion'] : 'Sin descripción adicional.';
        $validated['space_needed'] = $request->has('space_needed') ? 'Espacio amplio' : 'Espacio estándar';
        $validated['kid_friendly'] = $request->has('kid_friendly');
        $validated['energy_level'] = $request->input('energy_level', $mascota->energy_level ?? 5);

        if ($request->hasFile('foto')) {
            if ($mascota->foto && $mascota->foto !== 'mascotas/default.jpg') {
                Storage::disk('public')->delete($mascota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('mascotas', 'public');
        }

        $mascota->update($validated);

        return redirect()->route('mascotas.index')
                        ->with('success', '¡Perfil de ' . $mascota->nombre . ' actualizado con éxito!');
    }

    public function destroy($id)
    {
        $mascota = Mascota::findOrFail($id);

        if ($mascota->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar esta mascota.');
        }

        if ($mascota->foto && $mascota->foto !== 'mascotas/default.jpg') {
            Storage::disk('public')->delete($mascota->foto);
        }

        $mascota->delete();

        return redirect()->route('mascotas.index')
                        ->with('success', 'La mascota ha sido eliminada correctamente.');
    }
}