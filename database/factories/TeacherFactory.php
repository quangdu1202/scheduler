<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'phong_ban' => $this->faker->word, // Use appropriate faker method for department name
            'ten_giang_vien' => $this->faker->name, // Generates a random name
        ];
    }
}
