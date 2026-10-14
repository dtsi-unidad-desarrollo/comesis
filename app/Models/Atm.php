<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Torniquete;
use App\Models\User;

class Atm extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'torniquete_id',
        'descripcion',
        'en_uso',
        'user_id',
        'last_active_at',
    ];

    protected $casts = [
        'en_uso' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public function torniquete()
    {
        return $this->belongsTo(Torniquete::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si la asignación del ATM está obsoleta/inactiva.
     */
    public function isStale(int $minutes = 15): bool
    {
        if (!$this->en_uso) {
            return false;
        }

        if (!$this->last_active_at) {
            return true;
        }

        return $this->last_active_at->diffInMinutes(Carbon::now()) >= $minutes;
    }

    /**
     * Libera el ATM de cualquier usuario o sesión activa.
     */
    public function release(): bool
    {
        return $this->update([
            'en_uso' => false,
            'user_id' => null,
            'last_active_at' => null,
        ]);
    }

    /**
     * Asigna el ATM al usuario especificado y refresca la actividad.
     */
    public function assignTo(?int $userId): bool
    {
        return $this->update([
            'en_uso' => true,
            'user_id' => $userId,
            'last_active_at' => Carbon::now(),
        ]);
    }

    /**
     * Actualiza el timestamp de última actividad.
     */
    public function touchActivity(): bool
    {
        return $this->update([
            'last_active_at' => Carbon::now(),
        ]);
    }
}
