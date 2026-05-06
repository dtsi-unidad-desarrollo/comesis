<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConvertFechaFormatToIso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Obtener todas las entradas con fechas en formato d-m-Y
        $entradas = DB::table('entradas')->select('id', 'fecha')->get();

        foreach ($entradas as $entrada) {
            try {
                $fecha = Carbon::createFromFormat('d-m-Y', $entrada->fecha);
                DB::table('entradas')
                    ->where('id', $entrada->id)
                    ->update(['fecha' => $fecha->format('Y-m-d')]);
            } catch (\Exception $e) {
                // Si no se puede parsear, intentar como Y-m-d (ya convertido o formato inválido)
                continue;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir a formato d-m-Y
        $entradas = DB::table('entradas')->select('id', 'fecha')->get();

        foreach ($entradas as $entrada) {
            try {
                $fecha = Carbon::parse($entrada->fecha);
                DB::table('entradas')
                    ->where('id', $entrada->id)
                    ->update(['fecha' => $fecha->format('d-m-Y')]);
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}