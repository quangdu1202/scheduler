<?php

declare(strict_types=1);

namespace Database\Factories\StudentModuleClass;

use App\Models\Module\Module;
use App\Models\Student\Student;
use App\Models\StudentModuleClass\StudentModuleClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentModuleClass>
 */
final class StudentModuleClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentModuleClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'module_class_id' => fake()->randomNumber(),
            'module_id' => Module::factory(),
        ];
    }
}
