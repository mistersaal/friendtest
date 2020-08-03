<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    protected $casts = [
        'answer' => 'boolean',
    ];

    protected $fillable = [
        'value',
        'answer',
        'default_question_id',
        'test_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'default_question_id',
        'test_id',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function defaultQuestion(): BelongsTo
    {
        return $this->belongsTo(DefaultQuestion::class);
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }
}
