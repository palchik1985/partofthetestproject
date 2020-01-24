<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_subcategory_id');
            $table->unsignedBigInteger('r_keeper_id')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('price');
            $table->integer('weight_gram');
            $table->string('image')->nullable();
            $table->integer('position_order')->default(0);
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
        Schema::dropIfExists('menu_products');
    }
}
