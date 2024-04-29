<?php

declare(strict_types=1);

namespace Database\Factories\Registration;

use App\Models\PracticeClass\PracticeClass;
use App\Models\Registration\Registration;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
final class RegistrationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Registration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'practice_class_id' => PracticeClass::factory(),
        ];
    }
}
