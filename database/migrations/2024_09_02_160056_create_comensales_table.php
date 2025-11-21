<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComensalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comensales', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->string('nacionalidad', 25);
            $table->string('cedula', 35)->unique();
            $table->string('sexo', 35);
            $table->string('tipo_comensal', 100)->comment('ESTUDIANTE, PROFESOR, ADMINISTRATIVO, OBRERO Y EVENTUAL');
            $table->string('observacion', 500)->nullable();
            $table->string('foto')->default('/assets/img/avatar.png');
            $table->text('datos_extras')->nullable();
            $table->boolean('estatus')->default(true);
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
        Schema::dropIfExists('comensales');
    }
}
