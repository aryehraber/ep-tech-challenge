<?php

namespace App;

use App\Enums\BookingType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'start',
        'end',
        'notes',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeOfType(Builder $query, ?BookingType $type = null)
    {
        $operator = match ($type) {
            BookingType::FUTURE => '>=',
            BookingType::PAST => '<',
            default => null,
        };

        $query->when(
            $operator,
            fn (Builder $q) => $q->where('start', $operator, now())
        );
    }
}
