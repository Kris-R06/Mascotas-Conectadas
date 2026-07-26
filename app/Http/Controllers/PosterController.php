<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Barryvdh\DomPDF\Facade\Pdf;

class PosterController extends Controller
{
    public function generar(Mascota $mascota)
    {
        // Solo permitir póster de mascotas con estatus 'extraviado'
        abort_if($mascota->estatus !== 'extraviado', 404, 'Esta mascota no está reportada como extraviada.');

        $mascota->load('user', 'especie');

        $pdf = Pdf::loadView('mascotas.poster', compact('mascota'))
            ->setPaper('letter', 'portrait');

        $nombreArchivo = 'poster-' . str($mascota->nombre)->slug() . '.pdf';

        return $pdf->stream($nombreArchivo); 
        // Usa ->download($nombreArchivo) si prefieres forzar descarga en vez de vista previa
    }
}