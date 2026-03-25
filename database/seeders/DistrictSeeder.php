<?php
namespace Database\Seeders;

use App\Models\District;
use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Get Kerala state
        $kerala = State::where('code', 'KL')->first();

        if (! $kerala) {
            $this->command->error('Kerala state not found. Run StateSeeder first.');
            return;
        }

        $districts = [
            ['name' => 'Thiruvananthapuram', 'code' => 'TVM'],
            ['name' => 'Kollam', 'code' => 'KLM'],
            ['name' => 'Pathanamthitta', 'code' => 'PTA'],
            ['name' => 'Alappuzha', 'code' => 'ALP'],
            ['name' => 'Kottayam', 'code' => 'KTM'],
            ['name' => 'Idukki', 'code' => 'IDK'],
            ['name' => 'Ernakulam', 'code' => 'EKM'],
            ['name' => 'Thrissur', 'code' => 'TSR'],
            ['name' => 'Palakkad', 'code' => 'PKD'],
            ['name' => 'Malappuram', 'code' => 'MLP'],
            ['name' => 'Kozhikode', 'code' => 'KKD'],
            ['name' => 'Wayanad', 'code' => 'WYD'],
            ['name' => 'Kannur', 'code' => 'KNR'],
            ['name' => 'Kasaragod', 'code' => 'KSD'],
        ];

        foreach ($districts as $district) {
            District::updateOrCreate(
                [
                    'code'     => $district['code'],
                    'state_id' => $kerala->id,
                ],
                [
                    'name'      => $district['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
