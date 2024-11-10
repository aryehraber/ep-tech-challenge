<?php

namespace Tests\Feature\Http;

use App\Client;
use App\Journal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_client_journal()
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();
        $journal = Journal::factory()->for($client)->create();

        $this->actingAs($user)
            ->get(route('journals.show', [$client, $journal]))
            ->assertSuccessful()
            ->assertViewIs('journals.show')
            ->assertViewHas('journal', $journal);
    }

    /** @test */
    public function cannot_view_another_users_client_journal()
    {
        [$user, $otherUser] = User::factory(2)->create();
        $client = Client::factory()->for($otherUser)->create();
        $journal = Journal::factory()->for($client)->create();

        $this->actingAs($user)
            ->get(route('journals.show', [$client, $journal]))
            ->assertForbidden();
    }

    /** @test */
    public function can_create_new_client_journal()
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->post(route('journals.store', $client), [
                'date' => '2024-11-10',
                'text' => 'This is a test journal.'
            ]);

        $journal = Journal::first();
        $response->assertSuccessful();
        $response->assertJsonPath('url', $journal->url);
        $this->assertTrue($journal->client->is($client));
    }

    /** @test */
    public function cannot_create_client_journal_without_date_and_text()
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->post(route('journals.store', $client), [
                'date' => '',
                'text' => ''
            ])
            ->assertSessionHasErrors(['date', 'text']);
    }
}
