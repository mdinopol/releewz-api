<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Achievement.
 *
 * @property int                                                              $id
 * @property string                                                           $name
 * @property string|null                                                      $alias
 * @property string|null                                                      $short
 * @property int                                                              $order
 * @property bool                                                             $is_range
 * @property string|null                                                      $description
 * @property \Illuminate\Support\Carbon|null                                  $created_at
 * @property \Illuminate\Support\Carbon|null                                  $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $scores
 * @property int|null                                                         $scores_count
 *
 * @method static \Database\Factories\AchievementFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereIsRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achievement whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'short',
        'order',
        'is_range',
        'description',
    ];

    protected $casts = [
        'is_range' => 'boolean',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
