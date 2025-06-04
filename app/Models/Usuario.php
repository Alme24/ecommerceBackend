<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'typeuser_id',
        'nombre_user',
        'apellido_user',
        'email_user',
        'contrasena_user',
    ];

    public function cuenta()
    {
        return $this->hasOne(Cuenta::class,'user_id');
    }

    public function typeuser()
    {
        return $this->belongsTo(Typeuser::class,'typeuser_id');
    }

    public function tienda()
    {
        return $this->hasOne(Tienda::class, 'usuario_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'user_id');
    }
}
