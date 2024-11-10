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
            $amount = rand(0, 5);

            factory(Journal::class, $amount)->create([
                'client_id' => $client->id,
            ]);
        }
    }
}
