<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skhedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function mattches(): HasMany
    {
        return $this->hasMany(Mattch::class);
    }
}
