<?php

namespace App\Enums;

enum BookingType: string
{
    case FUTURE = 'future';
    case PAST = 'past';
}
