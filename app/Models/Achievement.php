<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
