<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'admin',
                'display_name' => 'Admin',
                'description'  => 'Administrator role with full access',
            ],
            [
                'name'         => 'manager',
                'display_name' => 'Manager',
                'description'  => 'Manager role with management access',
            ],
            [
                'name'         => 'staff',
                'display_name' => 'Staff',
                'description'  => 'Staff role with limited access',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
