<?php

namespace App\Models;

use App\Enums\StrategyEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function findByEmail(string $email): Model
    {
        return self::query()->where('email',$email)->firstOrFail();
    }

    public static function mahdi(): self|Model
    {
        return self::query()->where('email','mahdi.msr4@gmail.com')->firstOrFail();
    }

    public function strategies(): HasMany
    {
        return $this->hasMany(UserStrategy::class);
    }

    public function exchangeTokens(): HasMany
    {
        return $this->hasMany(UserToken::class);
    }

    public function strategyBalance(StrategyEnum $strategyEnum): mixed
    {
        return $this->strategies()->where('name', $strategyEnum->value)->first()->balance;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
