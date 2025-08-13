<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamoLibro extends Model
{
    use HasFactory;
    protected $table = 'prestamos_libros';
    protected $fillable = [
        'id',
        'fecha_solicitud',
        'id_solicitud_prestamo'
    ];
}
