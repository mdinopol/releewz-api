<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Bout.
 *
 * This model can be thought of as the game's "Event(s)".
 * For SPAN Type games, Bout name could be dates(e.g., 11 October, 15 October, and so on...).
 * For DAILY games, Bout name could be the game's name itself.
 *
 * @property int                                                                $id
 * @property int                                                                $game_id
 * @property string                                                             $name
 * @property \Illuminate\Support\Carbon                                         $start_date
 * @property \Illuminate\Support\Carbon                                         $end_date
 * @property \Illuminate\Support\Carbon|null                                    $created_at
 * @property \Illuminate\Support\Carbon|null                                    $updated_at
 * @property \App\Models\Game                                                   $game
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matchup> $matchups
 * @property int|null                                                           $matchups_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bout query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bout whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Bout extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function matchups(): HasMany
    {
        return $this->hasMany(Matchup::class);
    }
}
