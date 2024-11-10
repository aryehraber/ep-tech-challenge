<?php

use App\Client;
use App\Journal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
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
            $quantity = rand(0, 5);

            Journal::factory($quantity)->create([
                'client_id' => $client->id,
            ]);
        }
    }
}
