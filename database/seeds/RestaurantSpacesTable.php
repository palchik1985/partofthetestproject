<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class RestaurantSpacesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('restaurant_spaces')->insert([
            [
                'id' => 1,
                'restaurant_id' => 2,
                'name' => 'Тестовый зал ресторана',
            ],
        ]);
    }
}
