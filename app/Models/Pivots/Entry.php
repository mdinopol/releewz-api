<?php

namespace App\Models\Pivots;

use App\Enum\Currency;
use App\Enum\License;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Entry extends Pivot
{
    public $incrementing = true;

    protected $casts = [
        'total_points' => 'float',
        'points_history' => 'array',
        'contestants' => 'array',
        'license_at_creation' => License::class,
        'currency_at_creation' => Currency::class,
    ];
}
