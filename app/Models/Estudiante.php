<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'nombres',
       'apellidos',
       'nacionalidad', 
       'cedula', 
       'sexo', 
       'tipo',
       'foto',
       'observacion', 
       'datos_extras', 
       'estatus'
    ];
    
}
