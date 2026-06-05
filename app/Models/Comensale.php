<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comensale extends Model
{
    use HasFactory;

    protected $attributes = [
        'sub_tipo' => '',
    ];

    protected $fillable = [
        'nombres',
        'apellidos',
        'nacionalidad',
        'cedula',
        'sexo',
        'tipo_comensal',
        'sub_tipo',
        'observacion',
        'foto',
        'datos_extras',
        'estatus',
    ];
}
