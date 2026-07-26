<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Mascota;
use Illuminate\Http\Request;

class ExtraviadoController extends Controller
{
    /**
     * Muestra la lista de mascotas extraviadas.
     */
    public function index(Request $request)
    {
        $query = Reporte::query()
            ->with(['tipo_reporte', 'mascota.especie', 'user'])
            ->where('tipo_reporte_id', 1); // 1 = Extravío

        if ($request->get('filter') === 'mis_publicaciones') {
            $query->where('user_id', auth()->id());
        }

        $reportes = $query->latest('fecha')->get();

        return view('extraviados.index', compact('reportes'));
    }

    /**
     * Muestra el formulario para crear un nuevo reporte de extravío.
     */
    public function create()
    {
        $mascotas = Mascota::with('especie')
            ->where('user_id', auth()->id())
            ->where('estatus', 'extraviado')
            ->get();

        return view('extraviados.create', compact('mascotas'));
    }

    /**
     * Guarda un nuevo reporte de extravío en la tabla 'reportes'.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'user_id' => ['required', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'ubicacion_lat' => ['required', 'string', 'max:255'],
            'ubicacion_lng' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'foto' => ['nullable'],
        ]);

        $tipoExtravio = \App\Models\TipoReporte::firstOrCreate(['nombre' => 'Extravío']);
        $data['tipo_reporte_id'] = $tipoExtravio->id;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('reportes', 'public');
            $data['foto'] = $path;
        } elseif (is_string($request->foto) && !empty($request->foto)) {
            $data['foto'] = $request->foto;
        } else {
            $mascota = Mascota::find($data['mascota_id']);
            $data['foto'] = $mascota->foto ?? 'mascotas/default.jpg';
        }

        Reporte::create($data);

        return redirect()->route('extraviados.index')->with('success', 'Reporte de extravío publicado correctamente.');
    }

    /**
     * Muestra el detalle de una mascota extraviada y sus avistamientos de la comunidad.
     */
    public function show(Reporte $reporte)
    {
        $reporte->load(['mascota.especie', 'user']);

        // Avistamientos vinculados a esta mascota
        $avistamientos = Reporte::query()
            ->with('user')
            ->where('tipo_reporte_id', 2) // 2 = Avistamiento
            ->where('mascota_id', $reporte->mascota_id)
            ->latest('fecha')
            ->get();

        return view('extraviados.show', compact('reporte', 'avistamientos'));
    }

    /**
     * Muestra el formulario para editar un reporte de extravío.
     */
    public function edit(Reporte $reporte)
    {
        $mascotas = Mascota::with('especie')->where('user_id', auth()->id())->get();
        return view('extraviados.edit', compact('reporte', 'mascotas'));
    }

    /**
     * Actualiza el reporte de extravío.
     */
    public function update(Request $request, Reporte $reporte)
    {
        $data = $request->validate([
            'mascota_id' => ['required', 'exists:mascotas,id'],
            'fecha' => ['required', 'date'],
            'ubicacion_lat' => ['required', 'string', 'max:255'],
            'ubicacion_lng' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'foto' => ['nullable'],
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('reportes', 'public');
            $data['foto'] = $path;
        }

        $reporte->update($data);

        return redirect()->route('extraviados.index')->with('success', 'Reporte de extravío actualizado correctamente.');
    }

    /**
     * Elimina el reporte de extravío.
     */
    public function destroy(Reporte $reporte)
    {
        $reporte->delete();

        return redirect()->route('extraviados.index')->with('success', 'Reporte de extravío eliminado correctamente.');
    }
}
