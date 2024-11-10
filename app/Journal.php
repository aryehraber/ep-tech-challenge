<?php

namespace App;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'text',
    ];

    protected $appends = [
        'snippet',
        'url',
    ];


    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    protected function snippet(): Attribute
    {
        return Attribute::get(fn () => Str::substr($this->text, 0, 100));
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn () => "/clients/{$this->client_id}/journals/{$this->id}");
    }
}
