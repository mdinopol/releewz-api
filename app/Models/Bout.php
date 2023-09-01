<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bout extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'start_date',
        'end_date',
    ];

    public function matchups(): HasMany
    {
        return $this->hasMany(Matchup::class);
    }
}
