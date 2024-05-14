<?php

declare(strict_types=1);

namespace Database\Factories\PracticeClass;

use App\Helper\Helper;
use App\Models\Module\Module;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Teacher\Teacher;
use Exception;
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
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'practice_class_name' => $this->faker->word(),
            'schedule_date' => null,
            'session' => $this->faker->numberBetween(1, 3),
            'module_id' => Module::inRandomOrder()->value('id'),
            'practice_room_id' => PracticeRoom::inRandomOrder()->value('id'),
            'teacher_id' => Teacher::inRandomOrder()->value('id'),
            'recurring_id' => Helper::uniqidReal(),
            'recurring_interval' => 0,
            'recurring_order' => 1,
            'registered_qty' => $this->faker->numberBetween(20, 35),
            'max_qty' => null,
            'status' => 0,
        ];
    }
}
