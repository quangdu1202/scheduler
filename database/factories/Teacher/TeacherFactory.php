<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Teacher>
 */
final class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'teacher_name' => fake()->word,
            'department' => fake()->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
