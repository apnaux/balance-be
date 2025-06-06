<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color'
    ];

    public function transactions() : MorphMany
    {
        return $this->morphMany(Transaction::class, 'taggable');
    }
}
