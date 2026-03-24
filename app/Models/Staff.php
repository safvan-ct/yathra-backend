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

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password'           => 'hashed',
            'is_active'          => 'boolean',
            'last_login_attempt' => 'datetime',
        ];
    }

    /**
     * Get the table associated with the model.
     */
    protected $table = 'staff';

    /**
     * Get the roles associated with the staff.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'staff_role');
    }

    /**
     * Check if staff has a specific role.
     */
    public function hasRole($role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if staff has a permission.
     */
    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get all permissions for the staff.
     */
    public function getPermissions()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->permissions()->pluck('name')->toArray());
        }
        return array_unique($permissions);
    }
}
