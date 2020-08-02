<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAnswer extends Model
{
    public function testAnswer(): BelongsTo
    {
        return $this->belongsTo(TestAnswer::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    protected $casts = [
        'answer' => 'boolean',
    ];
}
