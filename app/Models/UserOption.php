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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
