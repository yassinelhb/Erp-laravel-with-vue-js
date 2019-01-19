<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturelivraisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturelivraisons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nfacture')->nullable($value =true);
            $table->string('file')->nullable($value =true);
            $table->integer('bordereaulivraison_id')->unsigned();
            $table->foreign('bordereaulivraison_id')->references('id')->on('bordereaulivraisons');
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
        Schema::dropIfExists('facturelivraisons');
    }
}
