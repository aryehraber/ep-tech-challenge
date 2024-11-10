<?php

namespace App\Http\Controllers;

use App\Client;
use App\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function show(Client $client, Journal $journal)
    {
        $this->authorize('view', $journal);

        return view('journals.show', [
            'client' => $client,
            'journal' => $journal,
        ]);
    }

    public function create(Client $client)
    {
        $this->authorize('create', Journal::class);

        return view('journals.create', [
            'client' => $client,
        ]);
    }

    public function store(Request $request, Client $client)
    {
        $this->authorize('create', Journal::class);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'text' => ['required'],
        ]);

        return $client->journals()->create($data);
    }

    public function destroy(Client $client, Journal $journal)
    {
        $this->authorize('delete', $journal);

        $journal->delete();

        return response([
            'status' => 'success',
            'message' => 'Journal deleted!'
        ]);
    }
}
