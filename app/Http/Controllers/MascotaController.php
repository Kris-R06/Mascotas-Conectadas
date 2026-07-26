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

    public function show($id)
    {
        // Buscamos la mascota y cargamos su relación con especie
        $mascota = \App\Models\Mascota::with('especie')->findOrFail($id);
        if ($mascota->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta mascota.');
        }

        return view('mascotas.show', compact('mascota'));
    }

    public function edit($id)
    {
        $mascota = Mascota::findOrFail($id);
        
        // Proteger: asegurar que el usuario logueado sea el dueño
        if ($mascota->user_id !== auth()->id()) abort(403);
        
        $especies = Especie::all();
        return view('mascotas.edit', compact('mascota', 'especies'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        // 1. Capa de Seguridad: Evitar que alguien edite la mascota de otro usuario
        if ($mascota->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta mascota.');
        }

        // 2. Validación adaptada exactamente a tu formulario HTML
        $validated = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'especie_id'   => ['required', 'exists:especies,id'],
            'raza'         => ['nullable', 'string', 'max:255'],
            'color'        => ['nullable', 'string', 'max:255'],
            'tamaño'       => ['nullable', 'string', 'max:255'], // En la vista envías 'Pequeño', 'Mediano', etc.
            'edad'         => ['nullable', 'string', 'max:255'], // En la vista envías '2 meses', etc.
            'estatus'      => ['required', 'in:safe,adopcion,extraviado'],
            'descripcion'  => ['nullable', 'string'],
            'foto'         => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Ahora valida archivos reales
            'energy_level' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        // 3. Procesar las casillas (Checkboxes a Booleanos)
        $validated['space_needed'] = $request->has('space_needed');
        $validated['kid_friendly'] = $request->has('kid_friendly');

        // 4. El "Hackathon Fix" de SQLite para la descripción
        $validated['descripcion'] = $request->input('descripcion', 'Sin descripción adicional.');

        // 5. Gestión avanzada de la foto
        if ($request->hasFile('foto')) {
            // Si el usuario sube una FOTO NUEVA y ya tenía una antes, borramos la vieja del servidor para ahorrar espacio
            if ($mascota->foto) {
                Storage::disk('public')->delete($mascota->foto);
            }
            // Guardamos la nueva foto
            $validated['foto'] = $request->file('foto')->store('mascotas', 'public');
        }

        // 6. Actualizar la base de datos
        $mascota->update($validated);

        // 7. Redirigir con mensaje de éxito
        return redirect()->route('mascotas.index')
                        ->with('success', '¡Perfil de ' . $mascota->nombre . ' actualizado con éxito!');
    }

    public function destroy(Mascota $mascota)
    {
        $mascota->delete();

        return redirect()->route('mascotas.index');
    }
}