<?php

declare(strict_types=1);

namespace Database\Factories\PracticeRoom;

use App\Models\PracticeRoom\PracticeRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PracticeRoom>
 */
final class PracticeRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PracticeRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'location' => $this->faker->unique()->word(),
            'pc_qty' => $this->faker->numberBetween(25, 35),
            'status' => $this->faker->randomElement([1, 2 ,3])
        ];
    }
}
