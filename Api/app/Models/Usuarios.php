<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class Usuarios extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'dui',
        'password',
        'foto_path',
        'balance_actual',
        'ingreso_mensual',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'foto_url',
    ];

    public function getForeignKey()
    {
        return 'usuario_id';
    }
    public function getFotoUrlAttribute()
    {
        $value = $this->attributes['foto_path'] ?? null;

        if (!$value) {
            return 'https://ionicframework.com/docs/img/demos/avatar.svg';
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // ✅ CORRECCIÓN DEFINITIVA PARA TU ESTRUCTURA:
        // Tu foto YA está en public/storage/uploads/fotos/. 
        // Por lo tanto, la URL pública debe ser: /storage/uploads/fotos/...
        return 'https://gennda-api.onrender.com/storage/' . $value;
    }
    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
