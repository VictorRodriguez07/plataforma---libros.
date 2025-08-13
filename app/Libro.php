<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $table = 'libros';
    protected $fillable = [
        'nombre',
        'fecha_publicacion',
        'autor',
        'sinopsis',
        'img',
        'cantidad',
        'id_genero',
        'id_estado'
    ];
}
