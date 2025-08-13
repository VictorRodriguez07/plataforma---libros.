<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaLibro extends Model
{
    use HasFactory;
    protected $table = 'categorias_libros';
    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria',
    ];
}
