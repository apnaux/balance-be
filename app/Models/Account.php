<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'limit',
        'statement_date',
        'days_before_due'
    ];

    public function transactions() : MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }
}
