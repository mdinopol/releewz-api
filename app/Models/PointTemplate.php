<?php

namespace App\Models;

use App\Enum\GameType;
use App\Enum\Sport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
