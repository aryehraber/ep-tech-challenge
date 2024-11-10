<?php

namespace Database\Factories;

use App\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::make($this->faker->dateTimeBetween('-1 year', '+1 year'));
        $end = $start->copy()->addMinutes($this->faker->randomElement([15, 30, 45, 60, 75, 90]));

        return [
            'client_id' => Client::factory(),
            'start' => $start,
            'end' => $end,
            'notes' => $this->faker->boolean(30) ? $this->faker->paragraphs(1, true) : '',
        ];
    }
}
