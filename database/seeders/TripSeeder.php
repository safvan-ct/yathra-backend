<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $buses = DB::table('buses')->pluck('id');

        // Assume routes exist (adjust IDs if needed)
        $routes = DB::table('routes')->pluck('id')->take(3);

        if ($buses->count() < 2 || $routes->count() < 2) {
            $this->command->warn('⚠️ Need at least 2 buses and 2 routes');
            return;
        }

        $days = json_encode([0, 1, 1, 1, 1, 1, 1]); // Weekdays

        $trips = [];

        foreach ($buses as $busIndex => $busId) {
            foreach ($routes as $routeIndex => $routeId) {

                $departure = match ($routeIndex) {
                    0 => '06:00',
                    1 => '12:00',
                    2 => '18:00',
                };

                $arrival = match ($routeIndex) {
                    0 => '10:00',
                    1 => '16:00',
                    2 => '22:00',
                };

                $trips[] = [
                    'bus_id'         => $busId,
                    'route_id'       => $routeId,
                    'departure_time' => $departure,
                    'arrival_time'   => $arrival,
                    'days_of_week'   => $days,
                    'status'         => 'Active',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            }
        }

        DB::table('trips')->insert($trips);
    }
}
