<?php

namespace App\Http\Controllers;

use App\Models\TipoReporte;
use Illuminate\Http\Request;

class TipoReporteController extends Controller
{
    public function index()
    {
        $tipoReportes = TipoReporte::query()->orderBy('nombre')->get();

        return view('tipo-reportes.index', compact('tipoReportes'));
    }

    public function create()
    {
        return view('tipo-reportes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipo_reportes,nombre'],
        ]);

        TipoReporte::create($data);

        return redirect()->route('tipo-reportes.index');
    }

    public function edit(TipoReporte $tipoReporte)
    {
        return view('tipo-reportes.edit', compact('tipoReporte'));
    }

    public function update(Request $request, TipoReporte $tipoReporte)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipo_reportes,nombre,' . $tipoReporte->id],
        ]);

        $tipoReporte->update($data);

        return redirect()->route('tipo-reportes.index');
    }

    public function destroy(TipoReporte $tipoReporte)
    {
        $tipoReporte->delete();

        return redirect()->route('tipo-reportes.index');
    }
}