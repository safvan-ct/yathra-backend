<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'phone', 'pin', 'is_active', 'trust_score', 'trust_level'])]
#[Hidden(['pin'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected array $dontLog = ['pin', 'remember_token'];

    protected function casts(): array
    {
        return [
            'pin'                => 'hashed',
            'is_active'          => 'boolean',
            'last_login_attempt' => 'datetime',
        ];
    }

    public function suggestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    public function rewards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserReward::class);
    }

    public function getTotalPointsAttribute(): int
    {
        return $this->rewards()->sum('points');
    }
}
