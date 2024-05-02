<?php

declare(strict_types=1);

namespace Database\Factories\Module;

use App\Models\Module\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Module>
 */
final class ModuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Module::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'module_code' => $this->faker->unique()->word(),
            'module_name' => $this->faker->word(),
        ];
    }
}
