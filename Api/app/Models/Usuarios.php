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
        if ($this->foto_path) {
            return Storage::disk('public')->url($this->foto_path);
        }
        return 'https://ionicframework.com/docs/img/demos/avatar.svg';
    }
    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function eventos(){
        return $this->hasMany(Evento::class);
    }
}