<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'carrito_id',
        'user_id',
        'unidad_pedido',
        'preciosub_pedido',
        'estado_pedido',
        'fecha_pedido',
        'fechaFin_pedido'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'carrito_id');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'pedido_id');
    }
}
