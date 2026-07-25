<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUser extends Model
{
    use HasFactory;

    protected $table = 'tipo_users';

    protected $fillable = [
        'nombre',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'tipo_user_id');
    }
}