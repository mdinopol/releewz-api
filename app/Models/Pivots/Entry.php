<?php

namespace App\Models\Pivots;

use App\Enum\Currency;
use App\Enum\License;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Pivots\Entry.
 *
 * @property License  $license_at_creation
 * @property Currency $currency_at_creation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Entry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entry query()
 *
 * @mixin \Eloquent
 */
class Entry extends Pivot
{
    public $incrementing = true;

    protected $casts = [
        'total_points'         => 'float',
        'points_history'       => 'array',
        'contestants'          => 'array',
        'extra_predictions'    => 'array',
        'license_at_creation'  => License::class,
        'currency_at_creation' => Currency::class,
    ];
}
