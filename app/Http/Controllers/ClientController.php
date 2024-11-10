<?php

namespace App\Http\Controllers;

use App\Client;
use App\Enums\BookingType;
use App\Http\Requests\ClientRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $clients = $request->user()->clients()
            ->withCount(['bookings', 'journals'])
            ->get();

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

        $bookingType = $request->enum('booking_type', BookingType::class);

        $client->load([
            'bookings' => fn ($q) => $q->ofType($bookingType)->latest('start'),
            'journals' => fn ($q) => $q->latest('date'),
        ]);

        return view('clients.show', [
            'client' => $client,
            'bookingType' => $bookingType?->value,
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

        return $request->user()->clients()->create($data);
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
