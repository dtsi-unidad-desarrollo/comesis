<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Atm;

class Torniquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'endpoint_url',
        'tipo',
        'estatus',
        'descripcion',
    ];

    public function atms()
    {
        return $this->hasMany(Atm::class);
    }
}
