<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class genero extends Model
{
    use HasFactory;
    protected $table = 'generos_libros';
    protected $fillable = [
        'nombre_genero',
        'descripcion_genero',
    ];
}
