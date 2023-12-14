<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
