<?php

namespace Database\Factories;

use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $role = $this->faker->randomElement(['teacher', 'student']); // Decide the role at the start to avoid multiple checks

        $userable = $role === 'teacher' ?
            Teacher::factory()->create() :
            Student::factory()->create();

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Consider using config or .env file for default password
            'userable_id' => $userable->id,
            'userable_type' => get_class($userable),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
