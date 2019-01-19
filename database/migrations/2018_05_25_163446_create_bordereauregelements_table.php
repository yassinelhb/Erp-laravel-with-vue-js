<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBordereauregelementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bordereaureglements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reglement')->nullable($value =true);
            $table->double('montantreglement');
            $table->double('montantP');
            $table->date('datereglement');
            $table->string('modaliteP');
            $table->string('file')->nullable($value =true);
            $table->integer('facturelivraison_id')->unsigned();
            $table->foreign('facturelivraison_id')->references('id')->on('facturelivraisons');
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
        Schema::dropIfExists('bordereaureglements');
    }
}
