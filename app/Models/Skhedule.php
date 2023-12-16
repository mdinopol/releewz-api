<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Skhedule.
 *
 * @property int                                                               $id
 * @property int                                                               $tournament_id
 * @property string                                                            $name
 * @property \Illuminate\Support\Carbon                                        $date
 * @property \Illuminate\Support\Carbon|null                                   $created_at
 * @property \Illuminate\Support\Carbon|null                                   $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mattch> $mattches
 * @property int|null                                                          $mattches_count
 * @property \App\Models\Tournament                                            $tournament
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule whereTournamentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Skhedule whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
