<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::query()
            ->where('user_id', $request->user()?->id)
            ->get();

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        $this->authorize('create', Client::class);

        return view('clients.create');
    }

    public function show(Request $request, Client $client)
    {
        $this->authorize('view', $client);

        $bookingType = $request->get('booking_type');

        $client->load([
            'bookings' => fn ($q) => $q
                ->when(
                    $bookingType === 'future',
                    fn (Builder $q) => $q->where('start', '>=', now())
                )
                ->when(
                    $bookingType === 'past',
                    fn (Builder $q) => $q->where('start', '<', now())
                )
                ->latest('start'),
        ]);

        return view('clients.show', [
            'client' => $client,
            'booking_type' => $bookingType,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Client::class);

        $data = $request->validate([
            'name' => ['required', 'max:190'],
            'email' => ['nullable', 'required_without:phone', 'email:filter'],
            'phone' => ['nullable', 'required_without:email', 'regex:/^[+]?\d+$/'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'postcode' => ['nullable'],
        ]);

        return $request->user()->client()->create($data);
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();

        return response([
            'status' => 'success',
            'message' => 'Client deleted!',
        ]);
    }
}
