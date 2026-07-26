<?php

namespace App\Http\Controllers;

use App\Models\Adopcion;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdopcionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $adopciones = Adopcion::query()
            ->with(['mascota', 'adoptante'])
            ->when($request->query('filter') === 'mis_publicaciones' && $userId, function ($query) use ($userId) {
                
                $query->whereHas('mascota', function ($mascotaQuery) use ($userId) {
                    $mascotaQuery->where('user_id', $userId);
                });
                
            }, function ($query) use ($userId) {
                
                $query->when($userId, function ($q) use ($userId) {
                    $q->where(function ($subQuery) use ($userId) {
                        $subQuery->whereIn('estatus', ['pendiente', 'en_revision'])
                            ->orWhere(function ($approvedQuery) use ($userId) {
                                $approvedQuery->whereIn('estatus', ['aprobada', 'adoptado'])
                                    ->whereHas('mascota', function ($mascotaQuery) use ($userId) {
                                        $mascotaQuery->where('user_id', $userId);
                                    });
                            });
                    });
                }, function ($q) {
                    $q->whereIn('estatus', ['pendiente', 'en_revision']);
                });

            })
            ->latest()
            ->get();

        return view('adopciones.index', compact('adopciones'));
    }

    public function create()
    {
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
            'estatus' => ['required', Rule::in(['pendiente', 'en_revision', 'aprobada', 'adoptado', 'rechazada', 'cancelada'])],
        ]);

        $data['user_id'] = $request->filled('user_id') ? $request->input('user_id') : null;

        $adopcion = Adopcion::create($data);

        if (in_array($adopcion->estatus, ['aprobada', 'adoptado'], true)) {
            $adopcion->mascota()->update(['estatus' => 'safe']);
        }

        return redirect()->route('adopciones.index');
    }

    public function show(Adopcion $adopcion)
    {
        $adopcion->load(['mascota.especie', 'mascota.user']);

        if (in_array($adopcion->estatus, ['aprobada', 'adoptado'], true) && $adopcion->mascota->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta adopción aprobada.');
        }

        return view('adopciones.show', compact('adopcion'));
    }

    public function edit(Adopcion $adopcion)
    {
        $adopcion->load(['mascota.especie']);
        if ($adopcion->mascota->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta adopción.');
        }
        $usuarios = User::query()
            ->latest()
            ->get();

        $users = $usuarios;

        $mascotas = Mascota::where('user_id', Auth::id())
            ->with('especie')
            ->latest()
            ->get();

        return view('adopciones.edit', compact('adopcion', 'mascotas', 'users', 'usuarios'));
    }

    public function update(Request $request, Adopcion $adopcion)
    {
        if ($adopcion->mascota->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar esta adopción.');
        }
        $data = $request->validate([
            'mascota_id' => ['nullable', 'exists:mascotas,id'],
            'user_id'    => ['nullable', 'exists:users,id'],
            'estatus'    => ['required', Rule::in(['pendiente', 'en_revision', 'aprobada', 'adoptado', 'rechazada', 'cancelada'])],
        ]);

        $data['mascota_id'] = $request->filled('mascota_id') ? $request->input('mascota_id') : $adopcion->mascota_id;
        $data['user_id'] = $request->filled('user_id') ? $request->input('user_id') : null;
        $adopcion->update($data);

        if (in_array($data['estatus'], ['aprobada', 'adoptado'], true)) {
            $adopcion->mascota()->update(['estatus' => 'safe']);
        }
        return redirect()->route('adopciones.index')
                         ->with('success', '¡El estado de la adopción ha sido actualizado!');
    }

    public function destroy(Adopcion $adopcion)
    {
        if ($adopcion->mascota->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta adopción.');
        }
        $adopcion->delete();
        return redirect()->route('adopciones.index')
                         ->with('success', 'La publicación de adopción fue cancelada y eliminada.');
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

        $adopciones = \App\Models\Adopcion::where('estatus', 'pendiente')
            ->whereHas('mascota', function ($query) use ($lifestyleScore, $request) {
                
                $query->whereBetween('energy_level', [
                    max(1, $lifestyleScore - 2), 
                    min(10, $lifestyleScore + 2)
                ]);

                if ($request->has_yard == '0') {
                    $query->where(function ($q) {
                        $q->where('space_needed', '0')
                          ->orWhere('space_needed', 'false')
                          ->orWhere('space_needed', 'Espacio estándar')
                          ->orWhereNull('space_needed');
                    });
                }

                if ($request->has_kids == '1') {
                    $query->where(function ($q) {
                        $q->where('kid_friendly', true)
                          ->orWhere('kid_friendly', 1)
                          ->orWhere('kid_friendly', '1');
                    });
                }

            })->with('mascota.especie')->latest()->get();

        $isSmartMatch = true;

        return view('adopciones.index', compact('adopciones', 'lifestyleScore', 'isSmartMatch'));
    }

    public function adoptar(Adopcion $adopcion)
    {
        if ($adopcion->mascota->user_id === auth()->id()) {
            abort(403, 'No puedes adoptar a tu propia mascota.');
        }
        if ($adopcion->estatus !== 'pendiente') {
            return back()->withErrors('Esta mascota ya no está disponible o ya está en revisión.');
        }

        $adopcion->update([
            'user_id' => auth()->id(),
            'estatus' => 'en_revision'
        ]);

        return back()->with('success', '¡Solicitud enviada exitosamente! El dueño se pondrá en contacto contigo muy pronto.');
    }
}