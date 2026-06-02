<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Permiso extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "estatus"
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'rol_permisos', 'id_permiso', 'id_rol');
    }
}
