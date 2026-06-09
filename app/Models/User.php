<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Entrada;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'rol',
        'foto',
        'email',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'allowed_by_user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'rol');
    }

    public function hasPermiso(string $nombre): bool
    {
        if ($this->rol === 1) {
            return true;
        }

        $role = $this->role;
        if (! $role) {
            return false;
        }

        return $role->permisos()->where('nombre', $nombre)->exists();
    }

    public function hasAnyPermiso(array $nombres): bool
    {
        if ($this->rol === 1) {
            return true;
        }

        $role = $this->role;
        if (! $role) {
            return false;
        }

        return $role->permisos()->whereIn('nombre', $nombres)->exists();
    }
}
