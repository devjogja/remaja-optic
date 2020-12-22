<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductReturnTable extends Migration
{

    public function up()
    {
        Schema::create('purchase_product_return', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('return_id');
            $table->integer('product_id');
            $table->double('qty');
            $table->string('unit')->nullable();
            $table->double('net_unit_cost');
            $table->double('discount');
            $table->double('tax_rate');
            $table->double('tax');
            $table->double('total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_product_return');
    }
}
