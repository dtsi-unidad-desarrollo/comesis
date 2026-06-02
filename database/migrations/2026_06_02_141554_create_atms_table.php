<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtmsTable extends Migration
{
    public function up()
    {
        Schema::create('atms', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('mac_address', 50)->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->unsignedBigInteger('torniquete_id')->nullable();
            $table->text('descripcion')->nullable();

            $table->foreign('torniquete_id')->references('id')->on('torniquetes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atms');
    }
}
