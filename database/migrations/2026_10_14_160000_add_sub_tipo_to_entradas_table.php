<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSubTipoToEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('entradas', 'sub_tipo')) {
            Schema::table('entradas', function (Blueprint $table) {
                $table->string('sub_tipo', 255)->nullable()->after('tipo_comensal');
            });
        }

        try {
            DB::statement("UPDATE entradas e INNER JOIN comensales c ON e.cedula = c.cedula SET e.sub_tipo = c.sub_tipo WHERE c.sub_tipo IS NOT NULL AND c.sub_tipo != '' AND (e.sub_tipo IS NULL OR e.sub_tipo = '')");
        } catch (\Throwable $th) {
            // Silently continue if update cannot execute in certain drivers
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('entradas', 'sub_tipo')) {
            Schema::table('entradas', function (Blueprint $table) {
                $table->dropColumn('sub_tipo');
            });
        }
    }
}
