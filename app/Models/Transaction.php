<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Number;

class Transaction extends Model
{
    protected $fillable = [
        'name',
        'currency',
        'amount',
        'recurring_transaction_id',
        'tag_id',
        'posted_at'
    ];

    public function amount() : Attribute
    {
        return Attribute::make(
            get: fn($value) => Number::currency($value, $this->currency),
        );
    }

    public function transactable() : MorphTo
    {
        return $this->morphTo();
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
