<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'achievement_id',
        'matchup_id',
        'home_score',
        'home_points',
        'away_score',
        'away_points',
        'history',
    ];

    protected $casts = [
        'home_score'  => 'float',
        'home_points' => 'float',
        'away_score'  => 'float',
        'away_points' => 'float',
        'history'     => 'array',
    ];
}
