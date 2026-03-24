<?php
namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name'         => 'manage-users',
                'display_name' => 'Manage Users',
                'description'  => 'Can manage users',
            ],
            [
                'name'         => 'manage-staff',
                'display_name' => 'Manage Staff',
                'description'  => 'Can manage staff members',
            ],
            [
                'name'         => 'manage-roles',
                'display_name' => 'Manage Roles',
                'description'  => 'Can manage roles',
            ],
            [
                'name'         => 'manage-permissions',
                'display_name' => 'Manage Permissions',
                'description'  => 'Can manage permissions',
            ],
            [
                'name'         => 'view-reports',
                'display_name' => 'View Reports',
                'description'  => 'Can view reports',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
