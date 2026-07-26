<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    // Vista de solo lectura
    public function index()
    {
        $user = Auth::user();
        return view('perfil.index', compact('user'));
    }

    // Formulario de edición
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

    // Guardar cambios
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telefono'  => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'password'  => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $validated['has_yard'] = $request->has('has_yard');
        $validated['kids'] = $request->has('kids');

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('perfil.index')->with('status', 'Perfil actualizado correctamente.');
    }
}