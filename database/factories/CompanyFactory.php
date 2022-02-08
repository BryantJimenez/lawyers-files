<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    $customers=User::role('Cliente')->get()->pluck('id');
    return [
        'name' => $faker->company,
        'social_reason' => $faker->company,
        'address' => $faker->address,
        'state' => $faker->randomElement(['1', '0']),
        'user_id' => $faker->randomElement($customers)
    ];
});
