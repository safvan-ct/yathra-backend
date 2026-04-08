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

        foreach (getKeralaDistricts() as $district => $code) {
            District::updateOrCreate(
                ['code' => $code, 'state_id' => $kerala->id],
                ['name' => $district]
            );
        }
    }
}
