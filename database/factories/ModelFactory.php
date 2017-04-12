<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->unique()->word,
        'phone_number' => $faker->e164PhoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Model\Notes::class, function (Faker\Generator $faker) {

    return [
        'note' => $faker->sentences(3, true),
        'type' => 0,
        'sale_id' => 1,
        'company_id' => 1,
        'user_id' => 1,
    ];
});