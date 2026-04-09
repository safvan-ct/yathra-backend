<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $days = json_encode([1, 1, 1, 1, 1, 1, 1]);

        // KSRTC NF 15 1001
        $trips = [
            ['bus_id' => 1, 'route_id' => 2, 'departure_time' => '05:50', 'arrival_time' => '06:35', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 1, 'route_id' => 3, 'departure_time' => '16:15', 'arrival_time' => '17:15', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 1, 'route_id' => 4, 'departure_time' => '17:15', 'arrival_time' => '18:15', 'days_of_week' => $days], // AMB - MKD
            ['bus_id' => 1, 'route_id' => 1, 'departure_time' => '21:00', 'arrival_time' => '21:45', 'days_of_week' => $days], // MKD - TVK
        ];

        // Rosario
        $trips[] = [
            ['bus_id' => 5, 'route_id' => 2, 'departure_time' => '06:20', 'arrival_time' => '07:05', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 5, 'route_id' => 1, 'departure_time' => '10:45', 'arrival_time' => '11:30', 'days_of_week' => $days], // MKD - TVK
            ['bus_id' => 5, 'route_id' => 2, 'departure_time' => '11:40', 'arrival_time' => '12:25', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 5, 'route_id' => 1, 'departure_time' => '17:15', 'arrival_time' => '06:00', 'days_of_week' => $days], // MKD - TVK
        ];

        // Madeena NF 50 2002
        $trips[] = [
            ['bus_id' => 6, 'route_id' => 2, 'departure_time' => '07:05', 'arrival_time' => '07:50', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 6, 'route_id' => 3, 'departure_time' => '09:30', 'arrival_time' => '10:30', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 6, 'route_id' => 4, 'departure_time' => '10:45', 'arrival_time' => '11:45', 'days_of_week' => $days], // AMB - MKD
            ['bus_id' => 6, 'route_id' => 3, 'departure_time' => '13:35', 'arrival_time' => '14:35', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 6, 'route_id' => 4, 'departure_time' => '14:45', 'arrival_time' => '15:45', 'days_of_week' => $days], // AMB - MKD
            ['bus_id' => 6, 'route_id' => 1, 'departure_time' => '19:40', 'arrival_time' => '08:25', 'days_of_week' => $days], // MKD - TVK
        ];

        // FATHIMA
        $trips[] = [
            ['bus_id' => 7, 'route_id' => 3, 'departure_time' => '06:30', 'arrival_time' => '07:30', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 7, 'route_id' => 4, 'departure_time' => '07:35', 'arrival_time' => '08:35', 'days_of_week' => $days], // AMB - MKD
            ['bus_id' => 7, 'route_id' => 3, 'departure_time' => '10:35', 'arrival_time' => '11:35', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 7, 'route_id' => 4, 'departure_time' => '11:50', 'arrival_time' => '12:50', 'days_of_week' => $days], // AMB - MKD
        ];

        // MRL
        $trips[] = [
            ['bus_id' => 8, 'route_id' => 2, 'departure_time' => '08:15', 'arrival_time' => '09:00', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 8, 'route_id' => 1, 'departure_time' => '13:00', 'arrival_time' => '13:45', 'days_of_week' => $days], // MKD - TVK
            ['bus_id' => 8, 'route_id' => 2, 'departure_time' => '13:45', 'arrival_time' => '14:30', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 8, 'route_id' => 1, 'departure_time' => '14:40', 'arrival_time' => '15:25', 'days_of_week' => $days], // MKD - TVK
            ['bus_id' => 8, 'route_id' => 2, 'departure_time' => '15:25', 'arrival_time' => '16:10', 'days_of_week' => $days], // TVK - MKD
            ['bus_id' => 8, 'route_id' => 1, 'departure_time' => '17:50', 'arrival_time' => '18:35', 'days_of_week' => $days], // MKD - TVK
        ];

        // SHASTHA
        $trips[] = [
            ['bus_id' => 9, 'route_id' => 3, 'departure_time' => '06:55', 'arrival_time' => '07:55', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 9, 'route_id' => 4, 'departure_time' => '08:55', 'arrival_time' => '09:55', 'days_of_week' => $days], // AMB - MKD
            ['bus_id' => 9, 'route_id' => 3, 'departure_time' => '15:25', 'arrival_time' => '16:25', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 9, 'route_id' => 4, 'departure_time' => '16:35', 'arrival_time' => '17:35', 'days_of_week' => $days], // AMB - MKD
        ];

        // PUNYAALAN
        $trips[] = [
            ['bus_id' => 10, 'route_id' => 3, 'departure_time' => '08:15', 'arrival_time' => '09:15', 'days_of_week' => $days], // MKD - AMB
            ['bus_id' => 10, 'route_id' => 4, 'departure_time' => '09:15', 'arrival_time' => '10:15', 'days_of_week' => $days], // AMB - MKD
        ];

        // ALAMEEN
        $trips[] = [
            ['bus_id' => 11, 'route_id' => 1, 'departure_time' => '12:15', 'arrival_time' => '13:00', 'days_of_week' => $days], // MKD - TVK
            ['bus_id' => 11, 'route_id' => 2, 'departure_time' => '13:20', 'arrival_time' => '14:05', 'days_of_week' => $days], // TVK - MKD
        ];

        // SUNDARIKKUTTY
        $trips[] = [
            ['bus_id' => 12, 'route_id' => 3, 'departure_time' => '12:25', 'arrival_time' => '13:25', 'days_of_week' => $days], // MKD - AMB
        ];

        // MADEENA NF 50 2008
        $trips[] = [
            ['bus_id' => 13, 'route_id' => 6, 'departure_time' => '07:20', 'arrival_time' => '08:35', 'days_of_week' => $days], // TVK - PMNA
            ['bus_id' => 13, 'route_id' => 5, 'departure_time' => '19:15', 'arrival_time' => '20:30', 'days_of_week' => $days], // PMNA - TVK
        ];

        // MADEENA NF 50 2009
        $trips[] = [
            ['bus_id' => 14, 'route_id' => 10, 'departure_time' => '07:20', 'arrival_time' => '08:50', 'days_of_week' => $days], // AMB - PMNA
            ['bus_id' => 14, 'route_id' => 9, 'departure_time' => '19:00', 'arrival_time' => '20:30', 'days_of_week' => $days],  // PMNA - AMB
        ];

        // NISSAN
        $trips[] = [
            ['bus_id' => 15, 'route_id' => 8, 'departure_time' => '08:15', 'arrival_time' => '09:15', 'days_of_week' => $days],  // TVK - PMNA
            ['bus_id' => 15, 'route_id' => 11, 'departure_time' => '10:00', 'arrival_time' => '11:15', 'days_of_week' => $days], // PMNA - AMB
            ['bus_id' => 15, 'route_id' => 12, 'departure_time' => '11:20', 'arrival_time' => '12:35', 'days_of_week' => $days], // AMB - PMNA
            ['bus_id' => 15, 'route_id' => 11, 'departure_time' => '14:40', 'arrival_time' => '16:10', 'days_of_week' => $days], // PMNA - AMB
            ['bus_id' => 15, 'route_id' => 12, 'departure_time' => '16:45', 'arrival_time' => '18:00', 'days_of_week' => $days], // AMB - PMNA
            ['bus_id' => 15, 'route_id' => 7, 'departure_time' => '18:45', 'arrival_time' => '19:45', 'days_of_week' => $days],  // PMNA - TVK
        ];

        // YATHRA
        $trips[] = [
            ['bus_id' => 16, 'route_id' => 5, 'departure_time' => '08:15', 'arrival_time' => '09:30', 'days_of_week' => $days], // PMNA - TVK
            ['bus_id' => 16, 'route_id' => 6, 'departure_time' => '10:10', 'arrival_time' => '11:25', 'days_of_week' => $days], // TVK - PMNA
            ['bus_id' => 16, 'route_id' => 5, 'departure_time' => '14:45', 'arrival_time' => '16:00', 'days_of_week' => $days], // PMNA - TVK
            ['bus_id' => 16, 'route_id' => 6, 'departure_time' => '16:20', 'arrival_time' => '17:35', 'days_of_week' => $days], // TVK - PMNA
            ['bus_id' => 16, 'route_id' => 5, 'departure_time' => '17:45', 'arrival_time' => '19:00', 'days_of_week' => $days], // PMNA - TVK
            ['bus_id' => 16, 'route_id' => 6, 'departure_time' => '19:20', 'arrival_time' => '18:35', 'days_of_week' => $days], // TVK - PMNA
        ];

        // ARANGODAN
        $trips[] = [
            ['bus_id' => 17, 'route_id' => 5, 'departure_time' => '12:00', 'arrival_time' => '13:15', 'days_of_week' => $days], // PMNA - TVK
            ['bus_id' => 17, 'route_id' => 6, 'departure_time' => '13:30', 'arrival_time' => '14:45', 'days_of_week' => $days], // TVK - PMNA
        ];

        // PTB
        $trips[] = [
            ['bus_id' => 18, 'route_id' => 7, 'departure_time' => '12:20', 'arrival_time' => '13:20', 'days_of_week' => $days], // PMNA - TVK
            ['bus_id' => 18, 'route_id' => 8, 'departure_time' => '13:35', 'arrival_time' => '14:35', 'days_of_week' => $days], // TVK - PMNA
        ];

        foreach ($trips as $trip) {
            DB::table('trips')->insert($trip);
        }
    }
}
