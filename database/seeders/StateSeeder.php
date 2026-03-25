<?php
namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $states = [
            ['name' => 'Andhra Pradesh', 'code' => 'AP'],
            ['name' => 'Keralam', 'code' => 'KL'],
            ['name' => 'Karnataka', 'code' => 'KA'],
            ['name' => 'Tamil Nadu', 'code' => 'TN'],
            ['name' => 'Telangana', 'code' => 'TG'],
        ];

        foreach ($states as $state) {
            State::updateOrCreate(['code' => $state['code']], ['name' => $state['name'], 'is_active' => true]);
        }
    }
}
