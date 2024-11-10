<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Client;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function destroy(Client $client, Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return response([
            'status' => 'success',
            'message' => 'Booking deleted!'
        ]);
    }
}
