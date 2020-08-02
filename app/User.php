<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mistersaal\VkMiniAppsAuth\VkMiniAppsAuthenticatable;

class User extends Authenticatable
    implements VkMiniAppsAuthenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'img', 'vkid'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];

    public function getVkIdFieldName(): string
    {
        return 'vkid';
    }

    public function test(): HasOne
    {
        return $this->hasOne(Test::class);
    }

    public function testAnswers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }
}
