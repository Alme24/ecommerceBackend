<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeuser extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_type'
    ];

    public function usuario()
    {
        return $this->hasMany(Usuario::class,'typeuser_id');
    }
}
