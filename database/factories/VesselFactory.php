<?php

namespace Database\Factories;

use App\Models\Vessel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vessel>
 */
class VesselFactory extends Factory
{
    protected $model = Vessel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'imo_number' => $this->faker->unique()->numerify('#########'), // 9-digit IMO number
        ];
    }
}
