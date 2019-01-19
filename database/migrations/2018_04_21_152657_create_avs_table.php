<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('libelle');
            $table->string('representant');
            $table->string('adresse');
            $table->string('email');
            $table->double('numfix');
            $table->double('mobile');
            $table->string('fax')->nullable($value =true);
            $table->string('image');
            $table->integer('cgi_id')->unsigned();
            $table->foreign('cgi_id')->references('id')->on('cgis');
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
        Schema::dropIfExists('avs');
    }
}
