<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DefaultQuestion extends Model
{
    protected $fillable = [
        'value'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    protected $casts = [
        'visible' => 'boolean',
    ];
}
