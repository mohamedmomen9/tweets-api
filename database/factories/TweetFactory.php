<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tweet;
use Faker\Generator as Faker;

$factory->define(Tweet::class, function (Faker $faker) {

    return [
        'text_en' => $faker->word,
        'text_ar' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
