<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class UserOption extends Model
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'currency',
        'cycle_cutoff',
        'total_income',
        'to_save',
        'timezone'
    ];

    protected $appends = [
        'formatted_total_income'
    ];

    public function allocatedBudget() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100
        );
    }

    public function formattedTotalIncome() : Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->total_income / 100, $this->currency)
        );
    }

    public function formattedToSave() : Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->to_save / 100, $this->currency)
        );
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
