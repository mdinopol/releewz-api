<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Mattch.
 *
 * @property int                             $id
 * @property int                             $tournament_id
 * @property int                             $home_id
 * @property int                             $away_id
 * @property \Illuminate\Support\Carbon      $start_date
 * @property \Illuminate\Support\Carbon      $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Contestant|null     $away
 * @property \App\Models\Contestant|null     $home
 * @property \App\Models\Tournament          $tournament
 *
 * @method static \Database\Factories\MattchFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereAwayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereHomeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereTournamentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mattch whereUpdatedAt($value)
 *
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $scores
 * @property int|null                                                         $scores_count
 *
 * @mixin \Eloquent
 */
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

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
