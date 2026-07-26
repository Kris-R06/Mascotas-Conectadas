<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = \App\Models\Mascota::with('especie')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('mascotas.index', compact('mascotas'));
    }

    public function create()
    {    $especies = \App\Models\Especie::all();
        
        return view('mascotas.create', compact('especies'));
    }

    public function store(Request $request)
    {
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
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'energy_level' => 'required|integer|min:1|max:10',
        ]);

        $validated['descripcion'] = $validated['descripcion'] ?? $validated['descripción'] ?? '';
        unset($validated['descripción']);

        // 2. Procesamos y guardamos la foto si el usuario subió una
        if ($request->hasFile('foto')) {
            // Guarda la imagen en storage/app/public/mascotas
            $validated['foto'] = $request->file('foto')->store('mascotas', 'public');
        }

        // 3. Asignaciones automáticas (Datos que el usuario no escribe directamente)
        $validated['user_id']      = Auth::id(); // Relacionamos la mascota con el usuario logueado
        $validated['space_needed'] = $request->has('space_needed'); // Checkbox a Booleano
        $validated['kid_friendly'] = $request->has('kid_friendly'); // Checkbox a Booleano
        
        // Generamos un identificador único para su futura placa QR (basado en tu BD)
        $validated['qr'] = (string) Str::uuid(); 

        // 4. ¡Guardamos en la base de datos!
        Mascota::create($validated);

        // 5. Redirigimos al usuario a su lista de mascotas con un mensaje de éxito
        return redirect()->route('mascotas.index')
                        ->with('success', '¡Mascota registrada con éxito a tu manada!');
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