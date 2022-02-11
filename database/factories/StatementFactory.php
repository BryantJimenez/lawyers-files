<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Statement\Statement;

use Faker\Generator as Faker;

$factory->define(Statement::class, function (Faker $faker) {
    $companies=Company::get()->pluck('id');
    return [
        'name' => $faker->text($maxNbChars=30),
        'description' => $faker->text($maxNbChars=1000),
        'type' => $faker->randomElement(['1', '2']),
        'state' => $faker->randomElement(['1', '0']),
        'company_id' => $faker->randomElement($companies)
    ];
});
