<?php

declare(strict_types=1);

namespace Database\Factories\Student;

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
            'student_code' => $this->faker->unique()->numberBetween(2020604000, 2020604594),
            'original_class_id' => OriginalClass::inRandomOrder()->value('id'),
        ];
    }
}
