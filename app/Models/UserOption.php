<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOption extends Model
{
    protected $fillable = [
        'currency',
        'cycle_cutoff',
        'allocated_budget'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
