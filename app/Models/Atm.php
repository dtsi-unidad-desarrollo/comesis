<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Torniquete;

class Atm extends Model
{

    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'torniquete_id',
        'descripcion',
    ];

    public function torniquete()
    {
        return $this->belongsTo(Torniquete::class);
    }

}
