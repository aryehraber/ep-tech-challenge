<?php

use App\Booking;
use App\Client;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $quantity = rand(0, 30);

            Booking::factory($quantity)->create([
                'client_id' => $client->id,
            ]);
        }
    }
}
