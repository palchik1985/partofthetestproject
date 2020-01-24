<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'birthday' => $faker->date('Y-m-d H:i:s'),
        'phone' => '+'.$faker->phoneNumber(10),
        'avg_check' => 1000,
        'comment' => implode(' ', $faker->words(10)),
        'image' => $faker->image('public/img/clients', 48, 48, null, false),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
