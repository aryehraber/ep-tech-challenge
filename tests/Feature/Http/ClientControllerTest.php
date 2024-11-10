<?php

namespace Tests\Feature\Http;

use App\Booking;
use App\Client;
use App\Journal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_all_clients_belonging_to_auth_user()
    {
        [$user, $otherUser] = User::factory(2)
            ->has(Client::factory(5))
            ->create();

        $this->actingAs($user)
            ->get(route('clients.index'))
            ->assertSuccessful()
            ->assertViewIs('clients.index')
            ->assertViewHas('clients', $user->clients()->get());
    }

    /** @test */
    public function can_view_client_profile()
    {
        $user = User::factory()->create();
        $client = Client::factory()
            ->state(['user_id' => $user->id])
            ->create();

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertSuccessful()
            ->assertViewIs('clients.show')
            ->assertViewHas('client', $client);
    }

    /** @test */
    public function cannot_view_another_users_client_profile()
    {
        [$user, $otherUser] = User::factory(2)->create();
        $client = Client::factory()
            ->state(['user_id' => $otherUser->id])
            ->has(Booking::factory(2))
            ->create();

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertForbidden();
    }

    /** @test */
    public function client_profile_contains_bookings()
    {
        $user = User::factory()->create();
        $client = Client::factory()
            ->state(['user_id' => $user->id])
            ->has(Booking::factory(3))
            ->create();

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertSuccessful()
            ->assertViewIs('clients.show')
            ->assertViewHas('client', function (Client $client) {
                $this->assertTrue($client->relationLoaded('bookings'));
                $this->assertEquals($client->bookings, $client->bookings()->latest('start')->get());

                return true;
            });
    }

    /** @test */
    public function client_profile_can_filter_by_booking_type()
    {
        $user = User::factory()->create();
        $client = Client::factory()
            ->state(['user_id' => $user->id])
            ->has(Booking::factory(5)->state(['start' => now()->addDays(7)]))
            ->has(Booking::factory(8)->state(['start' => now()->subDays(3)]))
            ->create();

        $this->actingAs($user)
            ->get(route('clients.show', [$client, 'booking_type' => 'future']))
            ->assertViewHas('booking_type', 'future')
            ->assertViewHas('client', function (Client $client) {
                $this->assertTrue($client->relationLoaded('bookings'));

                $bookings = $client->bookings()
                    ->where('start', '>=', now())
                    ->latest('start')
                    ->get();

                $this->assertCount(5, $bookings);
                $this->assertEquals($client->bookings, $bookings);

                return true;
            });

        $this->actingAs($user)
            ->get(route('clients.show', [$client, 'booking_type' => 'past']))
            ->assertViewHas('booking_type', 'past')
            ->assertViewHas('client', function (Client $client) {
                $this->assertTrue($client->relationLoaded('bookings'));

                $bookings = $client->bookings()
                    ->where('start', '<', now())
                    ->latest('start')
                    ->get();

                $this->assertCount(8, $bookings);
                $this->assertEquals($client->bookings, $bookings);

                return true;
            });
    }

    /** @test */
    public function client_profile_contains_journals()
    {
        $user = User::factory()->create();
        $client = Client::factory()
            ->state(['user_id' => $user->id])
            ->has(Journal::factory(3))
            ->create();

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertSuccessful()
            ->assertViewIs('clients.show')
            ->assertViewHas('client', function (Client $client) {
                $this->assertTrue($client->relationLoaded('journals'));
                $this->assertEquals($client->journals, $client->journals()->latest('date')->get());

                return true;
            });
    }

    /** @test */
    public function can_create_new_client()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('clients.store', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '0123456789',
                'address' => 'Lorem Street 123',
                'city' => 'Ipsum',
                'postcode' => '11111',
            ]));

        $client = Client::first();
        $response->assertSuccessful();
        $response->assertJsonPath('url', $client->url);
        $this->assertTrue($client->user->is($user));
    }

    /** @test */
    public function cannot_create_client_without_email_or_phone()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('clients.store', [
                'name' => 'John Doe',
            ]))
            ->assertSessionHasErrors(['email', 'phone']);

        $this->actingAs($user)
            ->post(route('clients.store', [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ]))
            ->assertSuccessful();

        $this->actingAs($user)
            ->post(route('clients.store', [
                'name' => 'John Doe',
                'phone' => '0123456789'
            ]))
            ->assertSuccessful();
    }

    /** @test */
    public function cannot_create_client_with_invalid_email()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('clients.store', [
                'name' => 'John Doe',
                'email' => 'invalid@email'
            ]))
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function cannot_create_client_with_invalid_phone()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('clients.store', [
                'name' => 'John Doe',
                'phone' => 'abc123'
            ]))
            ->assertSessionHasErrors(['phone']);
    }

    /** @test */
    public function can_delete_client()
    {
        $user = User::factory()->create();
        $client = Client::factory()
            ->state(['user_id' => $user->id])
            ->create();

        $this->actingAs($user)
            ->delete(route('clients.destroy', $client))
            ->assertSuccessful()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Client deleted!');
    }

    /** @test */
    public function cannot_delete_another_users_client()
    {
        [$user, $otherUser] = User::factory(2)->create();
        $client = Client::factory()
            ->state(['user_id' => $otherUser->id])
            ->create();

        $this->actingAs($user)
            ->delete(route('clients.destroy', $client))
            ->assertForbidden();
    }
}
