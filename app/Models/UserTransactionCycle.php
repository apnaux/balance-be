<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class UserTransactionCycle extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'allocated_budget',
        'active_from',
        'active_until'
    ];

    protected $appends = [
        'formatted_allocated_budget'
    ];

    public function allocatedBudget() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100
        );
    }

    public function formattedAllocatedBudget() : Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->allocated_budget, $this->currency)
        );
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
