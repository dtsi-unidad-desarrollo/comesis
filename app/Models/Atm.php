<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Torniquete;

class Atm extends Model
{

    use HasFactory;
    protected $fillable = [
        'nombre',
        'mac_address',
        'ip_address',
        'torniquete_id',
        'descripcion',
    ];

    public function torniquete()
    {
        return $this->belongsTo(Torniquete::class);
    }

}
