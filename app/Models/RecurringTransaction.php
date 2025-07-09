<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;

class RecurringTransaction extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'amount',
        'payment_date',
        'grace_period',
        'ends_at'
    ];

    protected $appends = [
        'formatted_amount'
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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions() : HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
