<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestAnswer extends Model
{
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'user_id');
    }

    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }
}
