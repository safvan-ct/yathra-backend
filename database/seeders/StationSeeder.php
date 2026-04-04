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
            ['city_id' => 6, 'name' => 'Mannarkkad', 'code' => 'MKD'],
            ['city_id' => 2, 'name' => 'Ottapalam', 'code' => 'OTP'],
            ['city_id' => 3, 'name' => 'Shoranur', 'code' => 'SRR'],
            ['city_id' => 6, 'name' => 'Melattur', 'code' => 'MLR'],
            ['city_id' => 6, 'name' => 'Alanallur', 'code' => 'ALR'],
            ['city_id' => 6, 'name' => 'Kottoppadam', 'code' => 'KPM'],
            ['city_id' => 1, 'name' => 'Palakkad', 'code' => 'PKD'],
            ['city_id' => 6, 'name' => 'Edathanattukara', 'code' => 'EDK'],
            ['city_id' => 6, 'name' => 'Ambalappara', 'code' => 'AMB'],
            ['city_id' => 6, 'name' => 'Thiruvizhamkunnu', 'code' => 'TVK'],
            ['city_id' => 6, 'name' => 'Anakkatti', 'code' => 'AKT'],
            ['city_id' => 16, 'name' => 'Perinthalmanna', 'code' => 'PMNA'],
            ['city_id' => 6, 'name' => 'Payyanadam', 'code' => 'PYD'],
        ];

        foreach ($stations as $station) {
            Station::create([
                'city_id'    => $station['city_id'],
                'name'       => $station['name'],
                'code'       => $station['code'],
                'local_name' => null,
                'lat'        => $faker->latitude(10, 12),
                'long'       => $faker->longitude(75, 77),
                'type'       => $faker->randomElement(['Hub', 'Stop', 'Terminal']),
            ]);
        }
    }
}
