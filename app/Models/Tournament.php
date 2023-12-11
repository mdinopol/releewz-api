<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Tournament.
 *
 * @property int                                                             $id
 * @property string                                                          $name
 * @property string|null                                                     $description
 * @property \Illuminate\Support\Carbon                                      $start_date
 * @property \Illuminate\Support\Carbon                                      $end_date
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Game> $games
 * @property int|null                                                        $games_count
 *
 * @method static \Database\Factories\TournamentFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereUpdatedAt($value)
 *
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mattch> $mattches
 * @property int|null                                                          $mattches_count
 *
 * @mixin \Eloquent
 */
class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function mattches(): HasMany
    {
        return $this->hasMany(Mattch::class);
    }
}
