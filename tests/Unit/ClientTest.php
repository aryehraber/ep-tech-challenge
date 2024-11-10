<?php

namespace Tests\Unit;

use App\Booking;
use App\Client;
use App\Journal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_has_various_properties()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '012345678',
            'address' => 'Lorem Street 123',
            'city' => 'Someplace',
            'postcode' => '11111',
        ]);

        $this->assertTrue($client->user->is($user));
        $this->assertEquals('John Doe', $client->name);
        $this->assertEquals('john@example.com', $client->email);
        $this->assertEquals('012345678', $client->phone);
        $this->assertEquals('Lorem Street 123', $client->address);
        $this->assertEquals('Someplace', $client->city);
        $this->assertEquals('11111', $client->postcode);
    }

    /** @test */
    public function deleting_a_client_deletes_its_relations()
    {
        $client = Client::factory()
            ->has(Booking::factory(5))
            ->has(Journal::factory(3))
            ->create();

        $this->assertEquals(5, Booking::count());
        $this->assertEquals(3, Journal::count());

        $client->delete();

        $this->assertEquals(0, Booking::count());
        $this->assertEquals(0, Journal::count());
    }
}
