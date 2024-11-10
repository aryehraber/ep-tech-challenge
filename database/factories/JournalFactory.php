<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Journal;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Journal::class, function (Faker $faker) {
    $date = Carbon::make($faker->dateTimeBetween('-1 year', '-1 day'));

    return [
        'date' => $date,
        'text' => $faker->paragraphs(rand(2, 10), true),
    ];
});
