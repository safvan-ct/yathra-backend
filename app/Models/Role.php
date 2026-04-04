<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'display_name', 'description'])]
class Role extends Model
{
    use HasFactory;

    protected array $dontLog = [];

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
