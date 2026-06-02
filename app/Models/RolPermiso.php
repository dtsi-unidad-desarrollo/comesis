<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\Permiso;

class RolPermiso extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_rol",
        "id_permiso"
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso');
    }
}
