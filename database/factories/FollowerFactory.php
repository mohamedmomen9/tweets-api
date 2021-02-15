<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Follower;
use App\User;
use Faker\Generator as Faker;

$factory->define(Follower::class, function (Faker $faker) {

    $user = User::first();
    $user2 = factory(User::class)->create();
    return [
        'follower_id' => $user->id,
        'followed_id' => $user2->id,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
