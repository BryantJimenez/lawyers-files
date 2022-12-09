<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Type;
use App\Models\Company;
use App\Models\Statement;

use Faker\Generator as Faker;

$factory->define(Statement::class, function (Faker $faker) {
	$types=Type::get()->pluck('id');
    $companies=Company::get()->pluck('id');
    return [
        'name' => $faker->text($maxNbChars=30),
        'description' => $faker->text($maxNbChars=1000),
        'state' => $faker->randomElement(['1', '0']),
        'type_id' => $faker->randomElement($types),
        'company_id' => $faker->randomElement($companies)
    ];
});
