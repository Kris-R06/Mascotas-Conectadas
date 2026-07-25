<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_user_id',
        'telefono',
        'direccion',
        'has_yard',
        'kids',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_yard' => 'boolean',
            'kids' => 'boolean',
        ];
    }

    public function tipo_user()
    {
        return $this->belongsTo(TipoUser::class, 'tipo_user_id');
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    public function adopciones()
    {
        return $this->hasMany(Adopcion::class, 'user_id');
    }
}
