<?php

namespace App;

use App\Enums\BookingType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
        ];
    }

    public function client(): BelongsTo
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
