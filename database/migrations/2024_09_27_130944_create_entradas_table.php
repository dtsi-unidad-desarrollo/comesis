<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 255); 
            $table->string('apellidos', 255); 
            $table->string('nacionalidad', 55); 
            $table->string('cedula', 55); 
            $table->string('sexo', 55)->comment('F: femenino, M: Masculino'); 
            $table->string('comida', 55)->comment('indica almuezo o cena');
            $table->string('fecha', 55)->comment('indica fecha');
            $table->string('hora', 55)->comment('indica hora');
            $table->string('tipo_comensal', 255)->comment('se registra si es estudiante | admin | obrero ...'); 

            $table->string('codigo_carrera', 255)->nullable();
            $table->string('carrera', 255)->nullable()->comment('nombre de carrera que cursa el estudiante');

            $table->string('codigo_sede', 255)->nullable();
            $table->string('sede', 255)->nullable();
            $table->string('tipo_sede', 255)->nullable();

            $table->string('estado', 255)->nullable();
            $table->string('municipio', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->timestamps();
            // $table->string('marcado', 55)->comment('esto indica si se acepto la entra o se rechaso: RECHAZADO | ACEPTADO'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entradas');
    }
}
