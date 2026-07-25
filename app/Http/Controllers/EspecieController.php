<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use Illuminate\Http\Request;

class EspecieController extends Controller
{
    public function index()
    {
        $especies = Especie::query()->orderBy('nombre')->get();

        return view('especies.index', compact('especies'));
    }

    public function create()
    {
        return view('especies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:especies,nombre'],
        ]);

        Especie::create($data);

        return redirect()->route('especies.index');
    }

    public function edit(Especie $especie)
    {
        return view('especies.edit', compact('especie'));
    }

    public function update(Request $request, Especie $especie)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:especies,nombre,' . $especie->id],
        ]);

        $especie->update($data);

        return redirect()->route('especies.index');
    }

    public function destroy(Especie $especie)
    {
        $especie->delete();

        return redirect()->route('especies.index');
    }
}