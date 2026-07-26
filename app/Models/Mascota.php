<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';

    protected $fillable = [
        'user_id',
        'especie_id',
        'nombre',
        'raza',
        'color',
        'tamaño',
        'edad',
        'foto',
        'descripcion',
        'estatus',
        'energy_level',
        'space_needed',
        'qr',
        'kid_friendly',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    public function adopciones()
    {
        return $this->hasMany(Adopcion::class);
    }

    public function getFotoUrlAttribute(): string
    {
        if (!$this->foto) {
            return asset('storage/mascotas/default.jpg');
        }
        if (\Illuminate\Support\Str::startsWith($this->foto, ['http://', 'https://'])) {
            return $this->foto;
        }
        return asset('storage/' . $this->foto);
    }
}
