<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estadoSolicitudesPrestamos extends Model
{
    use HasFactory;
    protected $table = 'estados_solicitudes_prestamos';
    protected $fillable = [
        'nombre',
        'id_tipo_estado',
    ];
}
