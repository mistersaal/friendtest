<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestAnswer extends Model
{
    protected $fillable = [
        'test_id'
    ];

    protected $hidden = [
        'user_id',
        'test_id',
        'updated_at',
        'created_at',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }
}
