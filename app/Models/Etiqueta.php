<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_etiqueta'
    ];

    public function productos(){

        return $this->belongsToMany(Producto::class, 'etiqueta_producto', 'etiqueta_id', 'producto_id');

    }
}
