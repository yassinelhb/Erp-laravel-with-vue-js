<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactureglobalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factureglobals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nglobal')->nullable($value =true);
            $table->string('file')->nullable($value =true);
            $table->integer('facturelivraison_id')->unsigned();
            $table->foreign('facturelivraison_id')->references('id')->on('facturelivraisons');
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
        Schema::dropIfExists('bordereaureglements');
            //
    }

}
