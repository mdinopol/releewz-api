<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\Country;
use App\Enum\Role;
use App\Models\Pivots\Entry;
use App\Models\Traits\BelongsToRoleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use BelongsToRoleTrait;

    protected $fillable = [
        'user_name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'country_code',
        'adress_city',
        'adress_postal',
        'adress_line_one',
        'adress_line_two',
        'image_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role'              => Role::class,
        'country_code'      => Country::class,
    ];

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'entries', 'user_id', 'game_id')
            ->using(Entry::class)
            ->withPivot([
                'name',
                'total_points',
                'points_history',
                'contestants',
                'license_at_creation',
                'currency_at_creation',
            ]);
    }
}
