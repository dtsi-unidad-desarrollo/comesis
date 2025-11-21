<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombres',
        'apellidos',
        'nacionalidad',  
        'cedula', 
        'sexo', 
        'comida', 
        'fecha', 
        'hora', 
        
        'codigo_carrera', 
        'carrera', 

        'codigo_sede', 
        'sede', 
        'tipo_sede', 
        
        'estado', 
        'municipio', 
        'direccion', 
        'tipo_comensal',  
    ];
}
