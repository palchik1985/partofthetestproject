<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {

    return [
        'restaurant_id' => 2,
        'title' => $faker->name,
        'description' => $faker->paragraph,
        'date_time' => $faker->date('Y-m-d H:i:s'),
        'ticket_price' => $faker->randomNumber(4),
        'min_deposit' => $faker->randomNumber(4),
        'image' => $faker->image('public/img/events', 640, 480, null, false),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
