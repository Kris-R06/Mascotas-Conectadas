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
        return $this->belongsTo(Mascota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}