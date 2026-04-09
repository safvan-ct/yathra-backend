<?php
namespace Database\Seeders;

use App\Models\Station;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $stations = [
            ['parent_station' => 'Kottoppadam', 'name' => 'Thiruvizhamkunnu'],
            ['parent_station' => 'Kottoppadam', 'name' => 'Edathanattukara'],
            ['parent_station' => 'Kottoppadam', 'name' => 'Ambalappara'],
            ['parent_station' => 'Alanallur', 'name' => 'Koomanchira'],
            ['parent_station' => 'Kumaramputhur', 'name' => 'Payyanadam'],
        ];

        foreach ($stations as $station) {
            $code = generateUniqueCode($station['name'], \App\Models\Station::class);

            if ($code) {
                $parent = Station::where('name', $station['parent_station'])->first();
                Station::create(['city_id' => $parent->city_id, 'parent_id' => $parent->id, 'name' => $station['name'], 'code' => $code]);
            }
        }
    }
}
