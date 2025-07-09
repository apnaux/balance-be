<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Number;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'currency',
        'limit',
        'statement_date',
        'days_before_due'
    ];

    protected $appends = [
        'formatted_limit'
    ];

    public function limit() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100
        );
    }

    public function formattedLimit() : Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->limit, $this->currency)
        );
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions() : MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }
}
