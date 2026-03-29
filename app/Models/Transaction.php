<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Number;

class Transaction extends Model
{
    protected $guarded = [];

    protected $appends = [
        'formatted_amount',
        'transacted_at_converted'
    ];

    public function amount() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100
        );
    }

    public function formattedAmount() : Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->amount, $this->currency)
        );
    }

    public function transactedAtConverted() : Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->transacted_at, 'UTC')->timezone($this->timezone)->toDateTimeString()
        );
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class);
    }


    public function tag() : BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function recurringTransaction() : BelongsTo
    {
        return $this->belongsTo(RecurringTransaction::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
