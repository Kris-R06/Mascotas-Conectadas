<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReporte extends Model
{
    use HasFactory;

    protected $table = 'tipo_reportes';

    protected $fillable = [
        'nombre',
    ];

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'tipo_reporte_id');
    }
}