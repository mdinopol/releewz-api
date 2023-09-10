<?php

namespace App\Models;

use App\Enum\GameType;
use App\Enum\Sport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PointTemplate
 *
 * @property int $id
 * @property Sport $sport
 * @property GameType $game_type
 * @property array $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate whereGameType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate whereSport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PointTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport',
        'game_type',
        'points',
    ];

    protected $casts = [
        'sport'     => Sport::class,
        'game_type' => GameType::class,
        'points'    => 'array',
    ];
}
