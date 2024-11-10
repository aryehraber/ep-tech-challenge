<?php

namespace App\Policies;

use App\Booking;
use App\Client;
use App\Journal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Journal $journal)
    {
        return $journal->client->user_id === $user->id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user, Journal $journal)
    {
        return $journal->client->user_id === $user->id;
    }
}
