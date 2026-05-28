<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuarios;
use App\Models\Evento;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'color'];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
