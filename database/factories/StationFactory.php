<?php
namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_id'     => \App\Models\City::inRandomOrder()->first()->id ?? 1,
            'name'        => $this->faker->city . ' Station',
            'code'        => strtoupper($this->faker->unique()->lexify('???')),
            'locale_name' => $this->faker->optional()->city,
            'lat'         => $this->faker->latitude,
            'long'        => $this->faker->longitude,
            'type'        => $this->faker->randomElement(['Hub', 'Stop', 'Terminal']),
        ];
    }
}
