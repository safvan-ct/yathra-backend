<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $days = json_encode([1, 1, 1, 1, 1, 1, 1]);

        // Arafa Mkd -> Pyd
        $trips = [
            ['bus_id' => 1, 'route_id' => 3, 'departure_time' => '06:30', 'arrival_time' => '07:00', 'days_of_week' => $days],
            ['bus_id' => 1, 'route_id' => 4, 'departure_time' => '13:40', 'arrival_time' => '14:10', 'days_of_week' => $days],
            ['bus_id' => 1, 'route_id' => 3, 'departure_time' => '14:20', 'arrival_time' => '14:50', 'days_of_week' => $days],
            ['bus_id' => 1, 'route_id' => 4, 'departure_time' => '17:20', 'arrival_time' => '17:50', 'days_of_week' => $days],
            ['bus_id' => 1, 'route_id' => 3, 'departure_time' => '18:30', 'arrival_time' => '19:00', 'days_of_week' => $days],
            ['bus_id' => 1, 'route_id' => 4, 'departure_time' => '20:15', 'arrival_time' => '20:45', 'days_of_week' => $days],

            ['bus_id' => 1, 'route_id' => 3, 'departure_time' => '09:40', 'arrival_time' => '10:10', 'days_of_week' => $days],
        ];

        // Ambadath Mkd -> Pyd
        $trips[] = [
            ['bus_id' => 2, 'route_id' => 3, 'departure_time' => '07:20', 'arrival_time' => '07:50', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 4, 'departure_time' => '08:05', 'arrival_time' => '08:35', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 3, 'departure_time' => '08:45', 'arrival_time' => '09:15', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 4, 'departure_time' => '10:10', 'arrival_time' => '10:40', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 3, 'departure_time' => '10:45', 'arrival_time' => '11:15', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 4, 'departure_time' => '11:20', 'arrival_time' => '11:50', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 3, 'departure_time' => '12:00', 'arrival_time' => '12:30', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 4, 'departure_time' => '12:30', 'arrival_time' => '13:00', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 3, 'departure_time' => '13:20', 'arrival_time' => '13:50', 'days_of_week' => $days],
            ['bus_id' => 2, 'route_id' => 4, 'departure_time' => '20:15', 'arrival_time' => '20:45', 'days_of_week' => $days],

            ['bus_id' => 13, 'route_id' => 4, 'departure_time' => '13:10', 'arrival_time' => '13:40', 'days_of_week' => $days],
            ['bus_id' => 13, 'route_id' => 3, 'departure_time' => '13:55', 'arrival_time' => '14:25', 'days_of_week' => $days],
            ['bus_id' => 13, 'route_id' => 4, 'departure_time' => '14:45', 'arrival_time' => '15:15', 'days_of_week' => $days],
            ['bus_id' => 13, 'route_id' => 3, 'departure_time' => '15:20', 'arrival_time' => '15:50', 'days_of_week' => $days],
            ['bus_id' => 13, 'route_id' => 4, 'departure_time' => '15:55', 'arrival_time' => '16:25', 'days_of_week' => $days],
            ['bus_id' => 13, 'route_id' => 3, 'departure_time' => '16:45', 'arrival_time' => '17:15', 'days_of_week' => $days],
        ];

        // Madeena Mkd -> Pyd
        $trips[] = [
            ['bus_id' => 11, 'route_id' => 3, 'departure_time' => '07:00', 'arrival_time' => '07:30', 'days_of_week' => $days],
            ['bus_id' => 11, 'route_id' => 4, 'departure_time' => '09:10', 'arrival_time' => '09:40', 'days_of_week' => $days],
            ['bus_id' => 11, 'route_id' => 3, 'departure_time' => '10:10', 'arrival_time' => '10:40', 'days_of_week' => $days],
            ['bus_id' => 11, 'route_id' => 4, 'departure_time' => '15:10', 'arrival_time' => '15:40', 'days_of_week' => $days],
            ['bus_id' => 11, 'route_id' => 3, 'departure_time' => '15:50', 'arrival_time' => '16:20', 'days_of_week' => $days],

            ['bus_id' => 14, 'route_id' => 4, 'departure_time' => '10:10', 'arrival_time' => '10:40', 'days_of_week' => $days],
            ['bus_id' => 14, 'route_id' => 3, 'departure_time' => '11:20', 'arrival_time' => '11:15', 'days_of_week' => $days],
        ];

        // Fifa Mkd -> Pyd
        $trips[] = [
            ['bus_id' => 7, 'route_id' => 4, 'departure_time' => '07:10', 'arrival_time' => '07:40', 'days_of_week' => $days],
            ['bus_id' => 7, 'route_id' => 3, 'departure_time' => '08:10', 'arrival_time' => '08:40', 'days_of_week' => $days],
            ['bus_id' => 7, 'route_id' => 4, 'departure_time' => '16:20', 'arrival_time' => '16:50', 'days_of_week' => $days],
            ['bus_id' => 7, 'route_id' => 3, 'departure_time' => '18:30', 'arrival_time' => '10:40', 'days_of_week' => $days],
            ['bus_id' => 7, 'route_id' => 4, 'departure_time' => '19:15', 'arrival_time' => '19:45', 'days_of_week' => $days],
        ];

        foreach ($trips as $trip) {
            DB::table('trips')->insert($trip);
        }
    }
}
