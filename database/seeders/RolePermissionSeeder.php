<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin   = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $staff   = Role::where('name', 'staff')->first();

        if ($admin) {
            $adminPermissions = Permission::pluck('id')->toArray();
            $admin->permissions()->sync($adminPermissions);
        }

        if ($manager) {
            $managerPermissions = Permission::whereIn('name', ['manage-users', 'view-reports'])->pluck('id')->toArray();
            $manager->permissions()->sync($managerPermissions);
        }

        if ($staff) {
            $staffPermissions = Permission::whereIn('name', ['view-reports'])->pluck('id')->toArray();
            $staff->permissions()->sync($staffPermissions);
        }
    }
}
