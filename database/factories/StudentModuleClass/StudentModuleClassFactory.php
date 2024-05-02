<?php

declare(strict_types=1);

namespace Database\Factories\StudentModuleClass;

use App\Models\Module\Module;
use App\Models\ModuleClass\ModuleClass;
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
        $module = Module::inRandomOrder()->first();

        $moduleClass = ModuleClass::where('module_id', $module->id)->inRandomOrder()->first();

        return [
            'student_id' => Student::factory(),
            'module_id' => $module->id,
            'module_class_id' => $moduleClass->id,
        ];
    }
}
