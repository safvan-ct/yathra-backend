<?php
namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Bus::insert([
            [
                'operator_id'     => 1,
                'bus_name'        => 'KSRTC',
                'bus_number'      => 'KL 15 A 1010',
                'bus_number_code' => 'KL15A1010',
                'category'        => 'Ordinary',
                'bus_color'       => 'Blue',
                'total_seats'     => 40,
                'is_active'       => true,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'operator_id'     => 3,
                'bus_name'        => 'Private',
                'bus_number'      => 'KL 09 B 2020',
                'bus_number_code' => 'KL09B2020',
                'category'        => 'Ordinary',
                'bus_color'       => 'Red',
                'total_seats'     => 36,
                'is_active'       => true,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}
