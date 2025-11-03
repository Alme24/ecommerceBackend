<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'tienda_id',
        'categoria_id',
        'nombre_producto',
        'descripcion_producto',
        'tamano_producto',
        'peso_producto',
        'precio_producto',
        'color_producto',
        'cantDisp_producto',
        'descuento_producto',
        'imagen_public_id',
        'imagen_producto'
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class,'tienda_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'producto_id');
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'etiqueta_producto', 'producto_id', 'etiqueta_id');
    }

}
