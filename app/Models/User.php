<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Filterable, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'external_access_token',
        'external_refresh_token',
        'external_expires_at',
        'external_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'external_access_token',
        'external_refresh_token',
        'external_expires_at',
        'external_id',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'is_mercadolibre_connected',
    ];

    protected function isMercadolibreConnected(): Attribute
    {
        return new Attribute(
            get: fn () => (bool) $this->external_access_token,
        );
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
