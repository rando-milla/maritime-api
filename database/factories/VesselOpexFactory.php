<?php


namespace Database\Factories;

use App\Models\VesselOpex;
use App\Models\Vessel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VesselOpex>
 */
class VesselOpexFactory extends Factory
{
    protected $model = VesselOpex::class;

    public function definition(): array
    {
        return [
            'vessel_id' => Vessel::factory(),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'expenses' => $this->faker->randomFloat(2, 1000, 5000),
        ];
    }
}
