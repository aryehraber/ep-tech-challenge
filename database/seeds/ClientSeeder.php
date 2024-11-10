<?php

use App\Client;
use App\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        $otherUsers = User::factory(25)->create();

        Client::factory(25)->create([
            'user_id' => $user->id,
        ]);

        foreach ($otherUsers as $otherUser) {
            $quantity = rand(0, 10);

            Client::factory($quantity)->create([
                'user_id' => $otherUser->id,
            ]);
        }
    }
}
