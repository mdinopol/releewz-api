<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mattch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'home_id',
        'away_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function home(): BelongsTo
    {
        return $this->belongsTo(Contestant::class, 'home_id');
    }

    public function away(): BelongsTo
    {
        return $this->belongsTo(Contestant::class, 'away_id');
    }
}
