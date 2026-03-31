<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'        => 'Safvan CT',
            'phone'       => '7560838394',
            'pin'         => '1234',
        ]);

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            StateSeeder::class,
            DistrictSeeder::class,
            CitySeeder::class,
            StationSeeder::class,
            TransitRouteSeeder::class,
            OperatorSeeder::class,
            BusSeeder::class,
            TripSeeder::class,
        ]);
    }
}
