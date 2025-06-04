<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'modoPago_carrito',
        'precioTotal_carrito'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'pedido_id');
    }
}
