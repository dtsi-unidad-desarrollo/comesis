<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 155)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('email_verified_at', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->string('rol', 55)->default(3); // 2:admin 3:cajero
            $table->string('password', 255)->nullable();
            $table->string('foto', 255)->default('/assets/img/avatar.png');
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
        Schema::dropIfExists('users');
    }
}
