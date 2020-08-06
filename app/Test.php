<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $hidden = [
        'updated_at',
        'created_at',
        'user_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function testAnswers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    /**
     * @param User $user
     * @return Model|HasMany|object|TestAnswer
     */
    public function userAnswer(User $user)
    {
        return $this->testAnswers()->where('user_id', $user->id)->first();
    }

    public function hasUserAnswer(User $user): bool
    {
        return $this->testAnswers()->where('user_id', $user->id)->exists();
    }

    public function isAuthor(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
