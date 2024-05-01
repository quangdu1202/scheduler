<?php

declare(strict_types=1);

namespace Database\Factories\ModuleClass;

use App\Models\Module\Module;
use App\Models\ModuleClass\ModuleClass;
use App\Models\Teacher\Teacher;
use Carbon\Carbon;
use Exception;
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
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'module_class_code' => $this->faker->word(),
            'module_class_name' => $this->faker->word(),
            'module_id' => Module::inRandomOrder()->value('id'),
            'teacher_id' => Teacher::inRandomOrder()->value('id'),
            'start_date' => Carbon::now(),
            'end_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'student_qty' => $this->faker->numberBetween(0, 75),
            'status' => 0,
        ];
    }
}
