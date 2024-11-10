<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'text',
    ];

    protected $dates = [
        'date',
    ];

    protected $appends = [
        'snippet',
        'url',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getSnippetAttribute()
    {
        return Str::substr($this->text, 0, 100);
    }

    public function getUrlAttribute()
    {
        return "/clients/{$this->client_id}/journals/{$this->id}";
    }
}
