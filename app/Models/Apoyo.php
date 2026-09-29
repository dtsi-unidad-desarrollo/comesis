<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apoyo extends Model
{
    use HasFactory;

    protected $fillable = [
        'institucion',
        'codigo',
        'cantidad_habilitada',
        'cantidad_consumida',
        'estatus'
    ];
}
