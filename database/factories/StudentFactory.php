<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OriginalClass\OriginalClass;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
final class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'student_id' => fake()->word,
            'student_name' => fake()->word,
            'original_class_id' => OriginalClass::factory(),
        ];
    }
}
