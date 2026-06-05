<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Atm;
use App\Models\User;

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
        'atm_id',
        'allowed_by_user_id',
    ];

    public function atm()
    {
        return $this->belongsTo(Atm::class);
    }

    public function allowedBy()
    {
        return $this->belongsTo(User::class, 'allowed_by_user_id');
    }

    // Compatibility alias used in controllers/views
    public function user()
    {
        return $this->belongsTo(User::class, 'allowed_by_user_id');
    }
}
