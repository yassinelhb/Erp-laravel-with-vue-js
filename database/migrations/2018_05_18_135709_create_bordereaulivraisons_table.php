<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBordereaulivraisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bordereaulivraisons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nbordereau')->nullable($value =true);
            $table->string('modaliteP');
            $table->date('datePaiement');
            $table->string('observation')->nullable($value =true);
            $table->integer('besoin_id')->unsigned();
            $table->foreign('besoin_id')->references('id')->on('besoins');
            $table->double('total');
            $table->string('file')->nullable($value =true);
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
        Schema::dropIfExists('bordereaulivraisons');
    }
}
