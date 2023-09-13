<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Matchup.
 *
 * @property int                                                              $id
 * @property int                                                              $bout_id
 * @property int                                                              $home_id
 * @property int                                                              $away_id
 * @property \Illuminate\Support\Carbon                                       $start_date
 * @property \Illuminate\Support\Carbon                                       $end_date
 * @property \Illuminate\Support\Carbon|null                                  $created_at
 * @property \Illuminate\Support\Carbon|null                                  $updated_at
 * @property \App\Models\Contestant|null                                      $away
 * @property \App\Models\Bout                                                 $bout
 * @property \App\Models\Contestant|null                                      $home
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $scores
 * @property int|null                                                         $scores_count
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereAwayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereBoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereHomeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matchup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function bout(): BelongsTo
    {
        return $this->belongsTo(Bout::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
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
