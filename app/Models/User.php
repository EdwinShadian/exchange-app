<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const TABLE = 'users';
    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'city_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
