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
                'name'         => 'developer',
                'display_name' => 'Developer',
                'description'  => 'Developer role with full access',
            ],
            [
                'name'         => 'admin',
                'display_name' => 'Admin',
                'description'  => 'Administrator role with full access',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
