<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    protected $casts = [
        'answer' => 'boolean',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function defaultQuestion(): HasOne
    {
        return $this->hasOne(DefaultQuestion::class);
    }
}
