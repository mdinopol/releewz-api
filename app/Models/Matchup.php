<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matchup extends Model
{
    use HasFactory;

    protected $fillable = [
        'bout_id',
        'home_id',
        'away_id',
        'start_date',
        'end_date',
    ];

    public function bout(): BelongsTo
    {
        return $this->belongsTo(Bout::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
