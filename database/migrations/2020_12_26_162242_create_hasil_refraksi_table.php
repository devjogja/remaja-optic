<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHasilRefraksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_refraksi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id');
            $table->string('sphr')->nullable();
            $table->string('cylr')->nullable();
            $table->string('axisr')->nullable();
            $table->string('addr')->nullable();
            $table->string('sphl')->nullable();
            $table->string('cyll')->nullable();
            $table->string('axisl')->nullable();
            $table->string('addl')->nullable();
            $table->string('pdd')->nullable();
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
        Schema::dropIfExists('hasil_refraksi');
    }
}
