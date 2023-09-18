<?php

namespace App\Models;

use App\Enum\Achievement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Score.
 *
 * @property int                             $id
 * @property int                             $matchup_id
 * @property Achievement                     $achievement
 * @property float                           $home_score
 * @property float                           $home_points
 * @property float                           $away_score
 * @property float                           $away_points
 * @property array|null                      $history
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Matchup|null        $matchup
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Score newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score query()
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereAchievement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereAwayPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereAwayScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereHomePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereHomeScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereMatchupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'matchup_id',
        'achievement',
        'home_score',
        'home_points',
        'away_score',
        'away_points',
        'history',
    ];

    protected $casts = [
        'achievement' => Achievement::class,
        'home_score'  => 'float',
        'home_points' => 'float',
        'away_score'  => 'float',
        'away_points' => 'float',
        'history'     => 'array',
    ];

    public function matchup(): BelongsTo
    {
        return $this->belongsTo(Matchup::class);
    }
}
