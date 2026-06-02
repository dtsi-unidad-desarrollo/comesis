<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtmAndAllowedByToEntradas extends Migration
{
    public function up()
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->unsignedBigInteger('atm_id')->nullable()->after('direccion');
            $table->unsignedBigInteger('allowed_by_user_id')->nullable()->after('atm_id');

            $table->foreign('atm_id')->references('id')->on('atms')->onDelete('set null');
            $table->foreign('allowed_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->dropForeign(['atm_id']);
            $table->dropForeign(['allowed_by_user_id']);
            $table->dropColumn(['atm_id','allowed_by_user_id']);
        });
    }
}
