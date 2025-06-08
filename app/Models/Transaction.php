<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'recurring_transaction_id',
        'tag_id',
        'posted_at'
    ];

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
