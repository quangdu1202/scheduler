<?php

declare(strict_types=1);

namespace Database\Factories;

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
            'name' => fake()->name,
            'location' => fake()->word,
            'pc_qty' => fake()->randomNumber(),
            'status' => fake()->boolean,
        ];
    }
}
