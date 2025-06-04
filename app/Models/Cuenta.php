<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombreTitular_cuenta',
        'numero_cuenta',
        'nombreBanco_cuenta',
        'nit_cuenta',
        'ci_cuenta',
        'tipo_cuenta',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class,'user_id');
    }
}
