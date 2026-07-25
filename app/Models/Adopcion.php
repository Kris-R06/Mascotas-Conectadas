<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adopcion extends Model
{
    use HasFactory;

    protected $table = 'adopciones';

    protected $fillable = [
        'mascota_id',
        'user_id',
        'estatus',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function adoptante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}