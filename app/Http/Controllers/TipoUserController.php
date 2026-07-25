<?php

namespace App\Http\Controllers;

use App\Models\TipoUser;
use Illuminate\Http\Request;

class TipoUserController extends Controller
{
    public function index()
    {
        $tipoUsers = TipoUser::query()->orderBy('nombre')->get();

        return view('tipo-users.index', compact('tipoUsers'));
    }

    public function create()
    {
        return view('tipo-users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipo_users,nombre'],
        ]);

        TipoUser::create($data);

        return redirect()->route('tipo-users.index');
    }

    public function edit(TipoUser $tipoUser)
    {
        return view('tipo-users.edit', compact('tipoUser'));
    }

    public function update(Request $request, TipoUser $tipoUser)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipo_users,nombre,' . $tipoUser->id],
        ]);

        $tipoUser->update($data);

        return redirect()->route('tipo-users.index');
    }

    public function destroy(TipoUser $tipoUser)
    {
        $tipoUser->delete();

        return redirect()->route('tipo-users.index');
    }
}