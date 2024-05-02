<?php

declare(strict_types=1);

namespace Database\Factories\Registration;

use App\Models\ModuleClass\ModuleClass;
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
        // Get a random ModuleClass
        $moduleClass = ModuleClass::inRandomOrder()->first();

        // Get a random PracticeClass associated with the module of the ModuleClass
        $practiceClass = PracticeClass::where('module_id', $moduleClass->module_id)->inRandomOrder()->first();

        return [
            'student_id' => Student::inRandomOrder()->first()->id,
            'module_class_id' => $moduleClass->id,
            'practice_class_id' => $practiceClass->id,
        ];
    }
}
