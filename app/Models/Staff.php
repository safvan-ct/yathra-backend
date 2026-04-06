<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'is_active'])]
#[Hidden(['password'])]
class Staff extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    public $dontLog = ['is_active', 'password', 'remember_token'];

    protected $table = 'staffs';

    protected function casts(): array
    {
        return [
            'password'           => 'hashed',
            'is_active'          => 'boolean',
            'last_login_attempt' => 'datetime',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'staff_role');
    }

    public function hasRole($role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function getPermissions()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->permissions()->pluck('name')->toArray());
        }
        return array_unique($permissions);
    }
}
