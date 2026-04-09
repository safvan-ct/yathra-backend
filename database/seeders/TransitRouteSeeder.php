<?php
namespace Database\Seeders;

use App\Services\TransitRouteService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TransitRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routeService = App::make(TransitRouteService::class);

        $routes = [
            [
                'origin_id'      => 85,  // MKD
                'destination_id' => 162, // TVK
                'variant'        => 'DIRECT',
                'distance'       => 17,
                'nodes'          => [
                    ['station_id' => 85, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 88, 'stop_sequence' => 2, 'distance_from_origin' => 10],
                    ['station_id' => 162, 'stop_sequence' => 3, 'distance_from_origin' => 17],
                ],
            ],
            [
                'origin_id'      => 162, // TVK
                'destination_id' => 85,  // MKD
                'variant'        => 'DIRECT',
                'distance'       => 17,
                'nodes'          => [
                    ['station_id' => 162, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 88, 'stop_sequence' => 2, 'distance_from_origin' => 7],
                    ['station_id' => 85, 'stop_sequence' => 3, 'distance_from_origin' => 17],
                ],
            ],
            [
                'origin_id'      => 85,  // MKD
                'destination_id' => 164, // AMB
                'variant'        => 'DIRECT',
                'distance'       => 22,
                'nodes'          => [
                    ['station_id' => 85, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 88, 'stop_sequence' => 2, 'distance_from_origin' => 10],
                    ['station_id' => 162, 'stop_sequence' => 3, 'distance_from_origin' => 17],
                    ['station_id' => 164, 'stop_sequence' => 4, 'distance_from_origin' => 22],
                ],
            ],
            [
                'origin_id'      => 164, // AMB
                'destination_id' => 85,  // MKD
                'variant'        => 'DIRECT',
                'distance'       => 22,
                'nodes'          => [
                    ['station_id' => 164, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 162, 'stop_sequence' => 2, 'distance_from_origin' => 5],
                    ['station_id' => 88, 'stop_sequence' => 3, 'distance_from_origin' => 12],
                    ['station_id' => 85, 'stop_sequence' => 4, 'distance_from_origin' => 22],
                ],
            ],
            [
                'origin_id'      => 107, // PMNA
                'destination_id' => 162, // TVK
                'variant'        => 'DIRECT',
                'distance'       => 33,
                'nodes'          => [
                    ['station_id' => 107, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 87, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => 88, 'stop_sequence' => 3, 'distance_from_origin' => 26],
                    ['station_id' => 162, 'stop_sequence' => 4, 'distance_from_origin' => 33],
                ],
            ],
            [
                'origin_id'      => 162, // TVK
                'destination_id' => 107, // PMNA
                'variant'        => 'DIRECT',
                'distance'       => 33,
                'nodes'          => [
                    ['station_id' => 162, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 88, 'stop_sequence' => 2, 'distance_from_origin' => 7],
                    ['station_id' => 87, 'stop_sequence' => 3, 'distance_from_origin' => 13],
                    ['station_id' => 107, 'stop_sequence' => 4, 'distance_from_origin' => 33],
                ],
            ],
            [
                'origin_id'      => 107, // PMNA
                'destination_id' => 162, // TVK
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 26,
                'nodes'          => [
                    ['station_id' => 107, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 87, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => 165, 'stop_sequence' => 3, 'distance_from_origin' => 23],
                    ['station_id' => 162, 'stop_sequence' => 4, 'distance_from_origin' => 26],
                ],
            ],
            [
                'origin_id'      => 162, // TVK
                'destination_id' => 107, // PMNA
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 26,
                'nodes'          => [
                    ['station_id' => 162, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 165, 'stop_sequence' => 2, 'distance_from_origin' => 3],
                    ['station_id' => 87, 'stop_sequence' => 3, 'distance_from_origin' => 6],
                    ['station_id' => 107, 'stop_sequence' => 4, 'distance_from_origin' => 26],
                ],
            ],
            [
                'origin_id'      => 107, // PMNA
                'destination_id' => 164, // AMB
                'variant'        => 'DIRECT',
                'distance'       => 38,
                'nodes'          => [
                    ['station_id' => 107, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 87, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => 88, 'stop_sequence' => 3, 'distance_from_origin' => 26],
                    ['station_id' => 162, 'stop_sequence' => 4, 'distance_from_origin' => 33],
                    ['station_id' => 164, 'stop_sequence' => 5, 'distance_from_origin' => 38],
                ],
            ],
            [
                'origin_id'      => 164, // AMB
                'destination_id' => 107, // PMNA
                'variant'        => 'DIRECT',
                'distance'       => 38,
                'nodes'          => [
                    ['station_id' => 164, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 162, 'stop_sequence' => 2, 'distance_from_origin' => 5],
                    ['station_id' => 88, 'stop_sequence' => 3, 'distance_from_origin' => 12],
                    ['station_id' => 87, 'stop_sequence' => 4, 'distance_from_origin' => 18],
                    ['station_id' => 107, 'stop_sequence' => 5, 'distance_from_origin' => 38],
                ],
            ],
            [
                'origin_id'      => 107, // PMNA
                'destination_id' => 164, // AMB
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 31,
                'nodes'          => [
                    ['station_id' => 107, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 87, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => 165, 'stop_sequence' => 3, 'distance_from_origin' => 23],
                    ['station_id' => 162, 'stop_sequence' => 4, 'distance_from_origin' => 26],
                    ['station_id' => 164, 'stop_sequence' => 5, 'distance_from_origin' => 31],
                ],
            ],
            [
                'origin_id'      => 164, // AMB
                'destination_id' => 107, // PMNA
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 31,
                'nodes'          => [
                    ['station_id' => 164, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => 162, 'stop_sequence' => 2, 'distance_from_origin' => 5],
                    ['station_id' => 165, 'stop_sequence' => 3, 'distance_from_origin' => 8],
                    ['station_id' => 87, 'stop_sequence' => 4, 'distance_from_origin' => 11],
                    ['station_id' => 107, 'stop_sequence' => 5, 'distance_from_origin' => 31],
                ],
            ],
        ];

        foreach ($routes as $route) {
            try {
                $routeService->create($route);
            } catch (\Throwable $e) {
                logger()->warning('Route seeder skipped: ' . $e->getMessage());
            }
        }
    }
}
