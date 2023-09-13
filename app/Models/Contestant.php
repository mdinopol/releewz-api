<?php

namespace App\Models;

use App\Enum\ContestantType;
use App\Enum\Country;
use App\Enum\Sport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Contestant.
 *
 * @property int                                                                   $id
 * @property int|null                                                              $parent_id
 * @property string                                                                $name
 * @property string|null                                                           $alias
 * @property Country                                                               $country_code
 * @property ContestantType                                                        $contestant_type
 * @property Sport                                                                 $sport
 * @property bool                                                                  $active
 * @property string|null                                                           $image_path
 * @property \Illuminate\Support\Carbon|null                                       $created_at
 * @property \Illuminate\Support\Carbon|null                                       $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, Contestant>             $members
 * @property int|null                                                              $members_count
 * @property Contestant|null                                                       $team
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tournament> $tournaments
 * @property int|null                                                              $tournaments_count
 * @method static \Database\Factories\ContestantFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereContestantType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereSport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contestant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Contestant extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'alias',
        'country_code',
        'contestant_type',
        'sport',
        'active',
        'image_path',
    ];

    protected $casts = [
        'country_code'    => Country::class,
        'contestant_type' => ContestantType::class,
        'sport'           => Sport::class,
        'active'          => 'boolean',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Contestant::class, 'parent_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Contestant::class, 'parent_id');
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class);
    }
}
