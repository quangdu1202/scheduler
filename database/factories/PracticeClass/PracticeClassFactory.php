<?php

declare(strict_types=1);

namespace Database\Factories\PracticeClass;

use App\Models\Module\Module;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PracticeClass>
 */
final class PracticeClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PracticeClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'practice_class_name' => fake()->word,
            'schedule_date' => fake()->optional()->date(),
            'session' => fake()->randomNumber(),
            'module_id' => Module::factory(),
            'practice_room_id' => PracticeRoom::factory(),
            'teacher_id' => Teacher::factory(),
            'recurring_id' => fake()->optional()->randomNumber(),
            'registered_qty' => fake()->randomNumber(),
        ];
    }
}
