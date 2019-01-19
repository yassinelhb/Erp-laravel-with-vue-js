<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nticket')->virtualAs('id');
            $table->integer('saison_id')->unsigned();
            $table->foreign('saison_id')->references('id')->on('saisons');
            $table->integer('caisse');
            $table->string('dateP1');
            $table->string('dateP2');
            $table->string('peseur');
            $table->float('poidsP1');
            $table->float('poidsP2');
            $table->string('vehicule');
            $table->integer('av_id')->unsigned();
            $table->foreign('av_id')->references('id')->on('avs');
            $table->text('observation')->nullable($value =true);
            $table->string('file')->nullable($value =true);
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
        Schema::dropIfExists('tickets');
    }
}
