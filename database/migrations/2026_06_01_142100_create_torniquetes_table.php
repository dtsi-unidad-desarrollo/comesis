<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorniquetesTable extends Migration
{
    public function up()
    {
        Schema::create('torniquetes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('endpoint_url')->nullable()->comment('URL o dirección para enviar la orden de apertura');
            $table->string('tipo')->nullable();
            $table->string('estatus')->default('activo');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('torniquetes');
    }
}
