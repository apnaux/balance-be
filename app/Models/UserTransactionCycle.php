<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTransactionCycle extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'allocated_budget',
        'active_from',
        'active_until'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
