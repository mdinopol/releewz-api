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
