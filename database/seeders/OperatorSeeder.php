<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $operators = [
            [
                'name'       => 'KSRTC',
                'type'       => 'Government',
                'phone'      => '0471-2463799',
                'email'      => 'info@ksrtc.in',
                'address'    => 'Transport Bhavan, Thiruvananthapuram',
                'is_public'  => true,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Kallada Travels',
                'type'       => 'Private',
                'phone'      => '0484-1234567',
                'email'      => 'support@kalladatravels.com',
                'address'    => 'Ernakulam, Kerala',
                'is_public'  => false,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Private',
                'type'       => 'Private',
                'phone'      => null,
                'email'      => null,
                'address'    => null,
                'is_public'  => true,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('operators')->insert($operators);
    }
}
