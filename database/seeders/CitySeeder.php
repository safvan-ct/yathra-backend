<?php
namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Fetch Kerala state
        $state = State::where('code', 'KL')->first();

        if (! $state) {
            $this->command->error('Kerala state not found. Run StateSeeder first.');
            return;
        }

        // Fetch Palakkad district
        $district = District::where('code', 'PKD')
            ->where('state_id', $state->id)
            ->first();

        if (! $district) {
            $this->command->error('Palakkad district not found. Run KeralaDistrictSeeder first.');
            return;
        }

        $cities = [
            ['name' => 'Palakkad', 'code' => 'PKD_TOWN'],
            ['name' => 'Ottapalam', 'code' => 'OTP'],
            ['name' => 'Shoranur', 'code' => 'SRN'],
            ['name' => 'Pattambi', 'code' => 'PTB'],
            ['name' => 'Cherpulassery', 'code' => 'CPS'],
            ['name' => 'Mannarkkad', 'code' => 'MNK'],
            ['name' => 'Chittur-Thathamangalam', 'code' => 'CTM'],
            ['name' => 'Alathur', 'code' => 'ALT'],
            ['name' => 'Kollengode', 'code' => 'KLD'],
            ['name' => 'Nenmara', 'code' => 'NMR'],
            ['name' => 'Koduvayur', 'code' => 'KDV'],
            ['name' => 'Kuzhalmannam', 'code' => 'KZM'],
            ['name' => 'Vadakkencherry', 'code' => 'VDC'],
            ['name' => 'Parali', 'code' => 'PRL'],
            ['name' => 'Malampuzha', 'code' => 'MLPZ'],
            ['district_id' => 10, 'name' => 'Perinthalmanna', 'code' => 'PMNA'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                [
                    'code'        => $city['code'],
                    'district_id' => $city['district_id'] ?? $district->id,
                ],
                [
                    'name'      => $city['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
