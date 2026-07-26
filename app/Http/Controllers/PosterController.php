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

        // ===== Convertir la foto (local o Cloudinary) a base64 =====
        $fotoBase64 = null;
        $fotoUrl = $mascota->foto_url;

        if ($fotoUrl) {
            if (\Illuminate\Support\Str::startsWith($fotoUrl, ['http://', 'https://'])) {
                try {
                    $context = stream_context_create([
                        'http' => ['timeout' => 10],
                        'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
                    ]);
                    $datos = @file_get_contents($fotoUrl, false, $context);
                    if ($datos !== false) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_buffer($finfo, $datos) ?: 'image/jpeg';
                        finfo_close($finfo);
                        $fotoBase64 = 'data:' . $mime . ';base64,' . base64_encode($datos);
                    }
                } catch (\Throwable $e) {
                    // Fallback silencioso si falla la red
                }
            } else {
                $rutaFoto = public_path('storage/' . $mascota->foto);
                if (file_exists($rutaFoto)) {
                    $extension = strtolower(pathinfo($rutaFoto, PATHINFO_EXTENSION));
                    $mime = match ($extension) {
                        'png' => 'image/png',
                        'webp' => 'image/webp',
                        default => 'image/jpeg',
                    };
                    $datos = file_get_contents($rutaFoto);
                    $fotoBase64 = 'data:' . $mime . ';base64,' . base64_encode($datos);
                }
            }
        }

        // ===== URL del QR apuntando a la publicación pública del reporte de extravío =====
        $tipoExtravio = \App\Models\TipoReporte::where('nombre', 'Extravío')->first();
        $reporte = \App\Models\Reporte::where('mascota_id', $mascota->id)
            ->when($tipoExtravio, function ($q) use ($tipoExtravio) {
                $q->where('tipo_reporte_id', $tipoExtravio->id);
            })
            ->latest('fecha')
            ->first();

        $destUrl = $reporte ? route('extraviados.show', $reporte->id) : route('extraviados.index');

        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' 
                . urlencode($destUrl);

        $pdf = Pdf::loadView('mascotas.poster', compact('mascota', 'fotoBase64', 'qrUrl'))
            ->setPaper('letter', 'portrait')
            ->setOptions(['isRemoteEnabled' => true]);

        $nombreArchivo = 'poster-' . str($mascota->nombre)->slug() . '.pdf';

        return $pdf->stream($nombreArchivo); 
        // Usa ->download($nombreArchivo) para forzar descarga en vez de vista previa
    }
}