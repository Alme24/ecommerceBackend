<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioTienda extends Model
{
    use HasFactory;

    protected $table = 'horarios_tienda';

    protected $fillable = [
        'tienda_id',
        'dia_semana',
        'hora_apertura',
        'hora_cierre',
        'cerrado',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }
}
