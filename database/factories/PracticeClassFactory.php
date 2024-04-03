<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\PracticeClass;
use App\Models\PracticeRoom;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class PracticeClassFactory extends Factory
{
    protected $model = PracticeClass::class;

    public function definition(): array
    {
        return [
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now(),
            'practice_class_name' => $this->faker->word,
            'schedule_date' => $this->faker->date(),
            'session' => $this->faker->randomElement([1, 2, 3]),
            // Assume we have Room, Teacher, and Module factories already created.
            'practice_room_id' => PracticeRoom::factory(), // Replace with the actual model reference
            'teacher_id' => Teacher::factory(), // Replace with the actual model reference
            'module_id' => Module::factory(),
        ];
    }
}
