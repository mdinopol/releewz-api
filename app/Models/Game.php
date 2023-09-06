<?php

namespace App\Models;

use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\Sport;
use App\Models\Pivots\Entry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
        'short',
        'slug',
        'description',
        'sport',
        'game_state',
        'duration_type',
        'game_type',
        'min_entry',
        'max_entry',
        'entry_contestants',
        'max_entry_value',
        'entry_price',
        'initial_prize_pool',
        'current_prize_pool',
        'start_date',
        'end_date',
        'points_template',
    ];

    protected $casts = [
        'sport'              => Sport::class,
        'game_state'         => GameState::class,
        'duration_type'      => GameDuration::class,
        'game_type'          => GameType::class,
        'max_entry_value'    => 'float',
        'entry_price'        => 'float',
        'initial_prize_pool' => 'float',
        'current_prize_pool' => 'float',
        'points_template'    => 'array',
        'start_date'         => 'datetime',
        'end_date'           => 'datetime',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function bouts(): HasMany
    {
        return $this->hasMany(Bout::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'entries', 'game_id', 'user_id')
            ->using(Entry::class)
            ->withPivot([
                'name',
                'total_points',
                'points_history',
                'contestants',
                'license_at_creation',
                'currency_at_creation',
            ]);
    }
}
