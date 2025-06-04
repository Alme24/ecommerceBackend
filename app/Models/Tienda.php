<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'nombre_tienda',
        'descripcion_tienda',
        'telefono_tienda',
        'direccion_tienda',
        'ubicacion_tienda',
        'ciudad_tienda',
        'provincia_tienda',
        'lugarEntregas_tienda',
        'logo_tienda',
        'banner_tienda',
        'calificacion_tienda'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class,'usuario_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class,'tienda_id');
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_tienda', 'tienda_id', 'categoria_id');
    }

}
