<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';

    protected $fillable = [
        'tipo_reporte_id',
        'mascota_id',
        'user_id',
        'fecha',
        'ubicacion_lat',
        'ubicacion_lng',
        'descripcion',
        'foto',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function tipo_reporte()
    {
        return $this->belongsTo(TipoReporte::class, 'tipo_reporte_id');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class)->withDefault([
            'nombre' => 'Sin identificar / Avistamiento libre',
            'raza' => 'No especificada',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            if (\Illuminate\Support\Str::startsWith($this->foto, ['http://', 'https://'])) {
                return $this->foto;
            }
            return asset('storage/' . $this->foto);
        }
        if ($this->mascota && $this->mascota->foto) {
            return $this->mascota->foto_url;
        }
        return asset('storage/mascotas/default.jpg');
    }
}