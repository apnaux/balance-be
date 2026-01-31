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
        'currency',
        'total_income',
        'to_save',
        'active_from',
        'active_until'
    ];

    protected $appends = [
        'allocated_budget'
    ];

    public function totalIncome() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100
        );
    }

    public function toSave() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100
        );
    }

    public function allocatedBudget() : Attribute
    {
        return Attribute::make(
            get: fn () => round(($this->total_income - $this->to_save) / 100)
        );
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
