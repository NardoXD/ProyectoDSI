<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('nombreUsuario')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('habilitado')->default(0);
            $table->string('token')->nullable();
            $table->rememberToken();

            $table->integer('personaId')->unsigned();
            $table->foreign('personaId')->references('id')->on('Personas');
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
