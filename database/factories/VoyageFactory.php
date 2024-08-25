<?php


namespace Database\Factories;

use App\Models\Voyage;
use App\Models\Vessel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voyage>
 */
class VoyageFactory extends Factory
{
    protected $model = Voyage::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify('+' . rand(1, 30) . ' days');
        $revenues = $this->faker->randomFloat(2, 10000, 100000);
        $expenses = $this->faker->randomFloat(2, 5000, 9000);
        $profit = $revenues - $expenses;

        return [
            'vessel_id' => Vessel::factory(),
            'start' => $startDate,
            'end' => $endDate,
            'status' => $this->faker->randomElement(['pending', 'ongoing', 'submitted']),
            'revenues' => $revenues,
            'expenses' => $expenses,
            'profit' => $profit,
        ];
    }

    /**
     * Indicate that the voyage is pending.
     */
    public function pending(): Factory|VoyageFactory
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the voyage is ongoing.
     */
    public function ongoing(): Factory|VoyageFactory
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'ongoing',
        ]);
    }

    /**
     * Indicate that the voyage is submitted.
     */
    public function submitted(): Factory|VoyageFactory
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'submitted',
        ]);
    }
}
