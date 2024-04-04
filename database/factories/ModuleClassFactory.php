<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Module\Module;
use App\Models\ModuleClass\ModuleClass;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModuleClass>
 */
final class ModuleClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'module_class_name' => fake()->word,
            'module_id' => Module::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
