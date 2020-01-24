<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWidthToRestaurantSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_spaces', function (Blueprint $table) {
            $table->integer('size_x')->after('image')->nullable();
            $table->integer('size_y')->after('size_x')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_spaces', function (Blueprint $table) {
            $table->dropColumn('size_x');
            $table->dropColumn('size_y');
        });
    }
}
