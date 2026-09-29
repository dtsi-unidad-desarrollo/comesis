<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApoyosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apoyos', function (Blueprint $table) {
            $table->id();
            $table->string('institucion', 255)->comment('Nombre de la institucion externa');
            $table->string('codigo', 50)->unique()->comment('Codigo unico para registrar las entradas');
            $table->integer('cantidad_habilitada')->default(0)->comment('Total de comidas permitidas');
            $table->integer('cantidad_consumida')->default(0)->comment('Total de comidas ya registradas/consumidas');
            $table->boolean('estatus')->default(true)->comment('Indica si el apoyo sigue activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apoyos');
    }
}
