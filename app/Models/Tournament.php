<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function contestants(): BelongsToMany
    {
        return $this->belongsToMany(Contestant::class)
            ->withPivot([
                'abandoned',
            ]);
    }
}
