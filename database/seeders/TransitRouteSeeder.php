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

        $mkd         = 376;
        $tvk         = 1399;
        $kpm         = 379;
        $amb         = 1402;
        $pmna        = 264;
        $koomanchira = 1403;
        $alr         = 378;

        $routes = [
            [
                'origin_id'      => $mkd, // MKD
                'destination_id' => $tvk, // TVK
                'variant'        => 'DIRECT',
                'distance'       => 17,
                'nodes'          => [
                    ['station_id' => $mkd, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $kpm, 'stop_sequence' => 2, 'distance_from_origin' => 10],
                    ['station_id' => $tvk, 'stop_sequence' => 3, 'distance_from_origin' => 17],
                ],
            ],
            [
                'origin_id'      => $tvk, // TVK
                'destination_id' => $mkd, // MKD
                'variant'        => 'DIRECT',
                'distance'       => 17,
                'nodes'          => [
                    ['station_id' => $tvk, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $kpm, 'stop_sequence' => 2, 'distance_from_origin' => 7],
                    ['station_id' => $mkd, 'stop_sequence' => 3, 'distance_from_origin' => 17],
                ],
            ],
            [
                'origin_id'      => $mkd, // MKD
                'destination_id' => $amb, // AMB
                'variant'        => 'DIRECT',
                'distance'       => 22,
                'nodes'          => [
                    ['station_id' => $mkd, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $kpm, 'stop_sequence' => 2, 'distance_from_origin' => 10],
                    ['station_id' => $tvk, 'stop_sequence' => 3, 'distance_from_origin' => 17],
                    ['station_id' => $amb, 'stop_sequence' => 4, 'distance_from_origin' => 22],
                ],
            ],
            [
                'origin_id'      => $amb, // AMB
                'destination_id' => $mkd, // MKD
                'variant'        => 'DIRECT',
                'distance'       => 22,
                'nodes'          => [
                    ['station_id' => $amb, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $tvk, 'stop_sequence' => 2, 'distance_from_origin' => 5],
                    ['station_id' => $kpm, 'stop_sequence' => 3, 'distance_from_origin' => 12],
                    ['station_id' => $mkd, 'stop_sequence' => 4, 'distance_from_origin' => 22],
                ],
            ],
            [
                'origin_id'      => $pmna, // PMNA
                'destination_id' => $tvk,  // TVK
                'variant'        => 'DIRECT',
                'distance'       => 33,
                'nodes'          => [
                    ['station_id' => $pmna, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $alr, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => $kpm, 'stop_sequence' => 3, 'distance_from_origin' => 26],
                    ['station_id' => $tvk, 'stop_sequence' => 4, 'distance_from_origin' => 33],
                ],
            ],
            [
                'origin_id'      => $tvk,  // TVK
                'destination_id' => $pmna, // PMNA
                'variant'        => 'DIRECT',
                'distance'       => 33,
                'nodes'          => [
                    ['station_id' => $tvk, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $kpm, 'stop_sequence' => 2, 'distance_from_origin' => 7],
                    ['station_id' => $alr, 'stop_sequence' => 3, 'distance_from_origin' => 13],
                    ['station_id' => $pmna, 'stop_sequence' => 4, 'distance_from_origin' => 33],
                ],
            ],
            [
                'origin_id'      => $pmna, // PMNA
                'destination_id' => $tvk,  // TVK
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 26,
                'nodes'          => [
                    ['station_id' => $pmna, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $alr, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => $koomanchira, 'stop_sequence' => 3, 'distance_from_origin' => 23],
                    ['station_id' => $tvk, 'stop_sequence' => 4, 'distance_from_origin' => 26],
                ],
            ],
            [
                'origin_id'      => $tvk,  // TVK
                'destination_id' => $pmna, // PMNA
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 26,
                'nodes'          => [
                    ['station_id' => $tvk, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $koomanchira, 'stop_sequence' => 2, 'distance_from_origin' => 3],
                    ['station_id' => $alr, 'stop_sequence' => 3, 'distance_from_origin' => 6],
                    ['station_id' => $pmna, 'stop_sequence' => 4, 'distance_from_origin' => 26],
                ],
            ],
            [
                'origin_id'      => $pmna, // PMNA
                'destination_id' => $amb,  // AMB
                'variant'        => 'DIRECT',
                'distance'       => 38,
                'nodes'          => [
                    ['station_id' => $pmna, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $alr, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => $kpm, 'stop_sequence' => 3, 'distance_from_origin' => 26],
                    ['station_id' => $tvk, 'stop_sequence' => 4, 'distance_from_origin' => 33],
                    ['station_id' => $amb, 'stop_sequence' => 5, 'distance_from_origin' => 38],
                ],
            ],
            [
                'origin_id'      => $amb,  // AMB
                'destination_id' => $pmna, // PMNA
                'variant'        => 'DIRECT',
                'distance'       => 38,
                'nodes'          => [
                    ['station_id' => $amb, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $tvk, 'stop_sequence' => 2, 'distance_from_origin' => 5],
                    ['station_id' => $kpm, 'stop_sequence' => 3, 'distance_from_origin' => 12],
                    ['station_id' => $alr, 'stop_sequence' => 4, 'distance_from_origin' => 18],
                    ['station_id' => $pmna, 'stop_sequence' => 5, 'distance_from_origin' => 38],
                ],
            ],
            [
                'origin_id'      => $pmna, // PMNA
                'destination_id' => $amb,  // AMB
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 31,
                'nodes'          => [
                    ['station_id' => $pmna, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $alr, 'stop_sequence' => 2, 'distance_from_origin' => 20],
                    ['station_id' => $koomanchira, 'stop_sequence' => 3, 'distance_from_origin' => 23],
                    ['station_id' => $tvk, 'stop_sequence' => 4, 'distance_from_origin' => 26],
                    ['station_id' => $amb, 'stop_sequence' => 5, 'distance_from_origin' => 31],
                ],
            ],
            [
                'origin_id'      => $amb,  // AMB
                'destination_id' => $pmna, // PMNA
                'variant'        => 'VIA KOOMANCHIRA',
                'distance'       => 31,
                'nodes'          => [
                    ['station_id' => $amb, 'stop_sequence' => 1, 'distance_from_origin' => 0],
                    ['station_id' => $tvk, 'stop_sequence' => 2, 'distance_from_origin' => 5],
                    ['station_id' => $koomanchira, 'stop_sequence' => 3, 'distance_from_origin' => 8],
                    ['station_id' => $alr, 'stop_sequence' => 4, 'distance_from_origin' => 11],
                    ['station_id' => $pmna, 'stop_sequence' => 5, 'distance_from_origin' => 31],
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
