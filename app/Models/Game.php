<?php

namespace App\Models;

use App\Enum\ContestantType;
use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\Sport;
use App\Models\Pivots\Entry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Game.
 *
 * @property int                                                                   $id
 * @property int|null                                                              $tournament_id
 * @property string                                                                $name
 * @property string|null                                                           $short
 * @property string                                                                $slug
 * @property string|null                                                           $description
 * @property Sport                                                                 $sport
 * @property GameState                                                             $game_state
 * @property GameDuration                                                          $duration_type
 * @property GameType                                                              $game_type
 * @property ContestantType                                                        $contestant_type
 * @property int                                                                   $min_entry
 * @property int                                                                   $max_entry
 * @property int                                                                   $entry_contestants
 * @property float                                                                 $max_entry_value
 * @property float                                                                 $entry_price
 * @property float|null                                                            $initial_prize_pool
 * @property float|null                                                            $current_prize_pool
 * @property \Illuminate\Support\Carbon                                            $start_date
 * @property \Illuminate\Support\Carbon                                            $end_date
 * @property array|null                                                            $point_template
 * @property \Illuminate\Support\Carbon|null                                       $created_at
 * @property \Illuminate\Support\Carbon|null                                       $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contestant> $contestants
 * @property int|null                                                              $contestants_count
 * @property array                                                                 $achievement_template
 * @property \App\Models\Tournament|null                                           $tournament
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\User>       $users
 * @property int|null                                                              $users_count
 * @method static \Database\Factories\GameFactory factory($count = null, $state = [])
 * @method static Builder|Game                    filters(\App\Enum\GameState $gameState, ?\App\Enum\Sport $sport = null)
 * @method static Builder|Game                    newModelQuery()
 * @method static Builder|Game                    newQuery()
 * @method static Builder|Game                    query()
 * @method static Builder|Game                    whereContestantType($value)
 * @method static Builder|Game                    whereCreatedAt($value)
 * @method static Builder|Game                    whereCurrentPrizePool($value)
 * @method static Builder|Game                    whereDescription($value)
 * @method static Builder|Game                    whereDurationType($value)
 * @method static Builder|Game                    whereEndDate($value)
 * @method static Builder|Game                    whereEntryContestants($value)
 * @method static Builder|Game                    whereEntryPrice($value)
 * @method static Builder|Game                    whereGameState($value)
 * @method static Builder|Game                    whereGameType($value)
 * @method static Builder|Game                    whereId($value)
 * @method static Builder|Game                    whereInitialPrizePool($value)
 * @method static Builder|Game                    whereMaxEntry($value)
 * @method static Builder|Game                    whereMaxEntryValue($value)
 * @method static Builder|Game                    whereMinEntry($value)
 * @method static Builder|Game                    whereName($value)
 * @method static Builder|Game                    wherePointTemplate($value)
 * @method static Builder|Game                    whereShort($value)
 * @method static Builder|Game                    whereSlug($value)
 * @method static Builder|Game                    whereSport($value)
 * @method static Builder|Game                    whereStartDate($value)
 * @method static Builder|Game                    whereTournamentId($value)
 * @method static Builder|Game                    whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        'contestant_type',
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
        'point_template',
    ];

    protected $casts = [
        'sport'              => Sport::class,
        'game_state'         => GameState::class,
        'duration_type'      => GameDuration::class,
        'game_type'          => GameType::class,
        'contestant_type'    => ContestantType::class,
        'max_entry_value'    => 'float',
        'entry_price'        => 'float',
        'initial_prize_pool' => 'float',
        'current_prize_pool' => 'float',
        'start_date'         => 'datetime',
        'end_date'           => 'datetime',
        'point_template'     => 'array',
    ];

    protected $appends = [
        'achievement_template',
    ];

    public function scopeFilters(Builder $query, GameState $gameState, Sport $sport = null): Builder
    {
        $filter = $query->where('game_state', $gameState);

        if (!$sport) {
            return $filter;
        }

        return $filter->where('sport', $sport);
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
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

    public function contestants(): BelongsToMany
    {
        return $this->belongsToMany(Contestant::class)
            ->withTimestamps()
            ->withPivot(['abandoned', 'value']);
    }

    public function getAchievementTemplateAttribute(): array
    {
        return $this->sport->template();
    }

    public function isImmutable(): bool
    {
        return $this->game_state->isImmutable();
    }
}
