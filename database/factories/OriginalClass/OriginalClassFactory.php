<?php

declare(strict_types=1);

namespace Database\Factories\OriginalClass;

use App\Models\OriginalClass\OriginalClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OriginalClass>
 */
final class OriginalClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OriginalClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'original_class_name' => fake()->word,
        ];
    }
}
