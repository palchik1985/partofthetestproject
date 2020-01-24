<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConstraintKeysToTables extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('additional_tables_for_date', function (Blueprint $table) {
            
            $table->foreign('tables_for_date_id')->references('id')->on('tables_for_date')
                  ->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')
                  ->onDelete('cascade');
        });
        
        Schema::table('table_presets', function (Blueprint $table) {
            
            $table->foreign('restaurant_space_id')->references('id')->on('restaurant_spaces')
                  ->onDelete('cascade');
        });
        
        Schema::table('table_table_group', function (Blueprint $table) {
            
            $table->foreign('table_id')->references('id')->on('tables')
                  ->onDelete('cascade');
            $table->foreign('table_group_id')->references('id')->on('table_groups')
                  ->onDelete('cascade');
        });
        
        Schema::table('table_table_preset', function (Blueprint $table) {
            
            $table->foreign('table_id')->references('id')->on('tables')
                  ->onDelete('cascade');
            $table->foreign('table_preset_id')->references('id')->on('table_presets')
                  ->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('additional_tables_for_date', function (Blueprint $table) {
            
            $table->dropForeign('additional_tables_for_date_table_id_foreign');
            $table->dropForeign('additional_tables_for_date_tables_for_date_id_foreign');
        });
        
        Schema::table('table_presets', function (Blueprint $table) {
            
            $table->dropForeign('table_presets_restaurant_space_id_foreign');
        });
        
        Schema::table('table_table_group', function (Blueprint $table) {
            
            $table->dropForeign('table_table_group_table_id_foreign');
            $table->dropForeign('table_table_group_table_group_id_foreign');
        });
        
        Schema::table('table_table_preset', function (Blueprint $table) {
            
            $table->dropForeign('table_table_preset_table_id_foreign');
            $table->dropForeign('table_table_preset_table_preset_id_foreign');
        });
    }
}
