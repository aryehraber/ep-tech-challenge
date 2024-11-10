<?php

namespace Tests\Unit;

use App\Booking;
use App\Client;
use App\Journal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class JournalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function journal_has_various_properties()
    {
        $client = Client::factory()->create();
        $journal = Journal::factory()->for($client)->create([
            'date' => '2024-11-10',
            'text' => ($journalText = $this->faker->paragraph(5)),
        ]);

        $this->assertTrue($journal->client->is($client));
        $this->assertEquals('2024-11-10', $journal->date);
        $this->assertEquals($journalText, $journal->text);
        $this->assertEquals(Str::substr($journalText, 0, 100), $journal->snippet);
        $this->assertEquals("/clients/{$client->id}/journals/{$journal->id}", $journal->url);
    }
}
