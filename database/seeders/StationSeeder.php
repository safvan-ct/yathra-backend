<?php
namespace Database\Seeders;

use App\Models\City;
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
            ['city_code' => 'KL-50', 'name' => 'Mannarkkad', 'code' => 'MKD'],
            ['city_code' => 'KL-51', 'name' => 'Ottapalam', 'code' => 'OTP'],
            ['city_code' => 'KL-51', 'name' => 'Shoranur', 'code' => 'SRR'],
            ['city_code' => 'KL-50', 'name' => 'Melattur', 'code' => 'MLR'],
            ['city_code' => 'KL-50', 'name' => 'Alanallur', 'code' => 'ALR'],
            ['city_code' => 'KL-50', 'name' => 'Kottoppadam', 'code' => 'KPM'],
            ['city_code' => 'KL-09', 'name' => 'Palakkad', 'code' => 'PKD'],
            ['city_code' => 'KL-50', 'name' => 'Edathanattukara', 'code' => 'EDK'],
            ['city_code' => 'KL-50', 'name' => 'Ambalappara', 'code' => 'AMB'],
            ['city_code' => 'KL-50', 'name' => 'Thiruvizhamkunnu', 'code' => 'TVK'],
            ['city_code' => 'KL-50', 'name' => 'Anakkatti', 'code' => 'AKT'],
            ['city_code' => 'KL-53', 'name' => 'Perinthalmanna', 'code' => 'PMNA'],
            ['city_code' => 'KL-50', 'name' => 'Payyanadam', 'code' => 'PYD'],
        ];

        foreach ($stations as $station) {
            Station::create([
                'city_id'    => City::where('code', $station['city_code'])->first()->id,
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
