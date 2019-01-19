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
            $table->string('matricule');
            $table->string('nom');
            $table->string('email');
            $table->string('password');
            $table->string('resetcode')->nullable($value =true);
            $table->string('role');
            $table->string('sexe');
            $table->string('rue');
            $table->string('ville');
            $table->string('etat');
            $table->double('telephone');
            $table->string('codetel');
            $table->string('image');
            $table->rememberToken();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable($value =true);
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
