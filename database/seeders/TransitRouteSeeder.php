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
                'origin_id'      => 1,  // MKD
                'destination_id' => 10, // TVK
                'variant'        => 'DIRECT',
                'distance'       => 18,
                'nodes'          => [
                    [
                        'station_id'           => 1,
                        'stop_sequence'        => 1,
                        'distance_from_origin' => 0,
                    ],
                    [
                        'station_id'           => 6,
                        'stop_sequence'        => 2,
                        'distance_from_origin' => 11,
                    ],
                    [
                        'station_id'           => 10,
                        'stop_sequence'        => 3,
                        'distance_from_origin' => 18,
                    ],
                ],
            ],
            [
                'origin_id'      => 1, // MKD
                'destination_id' => 9, // AMB
                'variant'        => 'DIRECT',
                'distance'       => 24,
                'nodes'          => [
                    [
                        'station_id'           => 1,
                        'stop_sequence'        => 1,
                        'distance_from_origin' => 0,
                    ],
                    [
                        'station_id'           => 6,
                        'stop_sequence'        => 2,
                        'distance_from_origin' => 11,
                    ],
                    [
                        'station_id'           => 10,
                        'stop_sequence'        => 3,
                        'distance_from_origin' => 18,
                    ],
                    [
                        'station_id'           => 9,
                        'stop_sequence'        => 4,
                        'distance_from_origin' => 24,
                    ],
                ],
            ],
            [
                'origin_id'      => 1,  // MKD
                'destination_id' => 13, // PYD
                'variant'        => 'DIRECT',
                'distance'       => 7,
                'nodes'          => [
                    [
                        'station_id'           => 1,
                        'stop_sequence'        => 1,
                        'distance_from_origin' => 0,
                    ],
                    [
                        'station_id'           => 13,
                        'stop_sequence'        => 2,
                        'distance_from_origin' => 7,
                    ],
                ],
            ],
            [
                'origin_id'      => 13, // PYD
                'destination_id' => 1,  // MKD
                'variant'        => 'DIRECT',
                'distance'       => 7,
                'nodes'          => [
                    [
                        'station_id'           => 13,
                        'stop_sequence'        => 1,
                        'distance_from_origin' => 0,
                    ],
                    [
                        'station_id'           => 1,
                        'stop_sequence'        => 2,
                        'distance_from_origin' => 7,
                    ],
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
