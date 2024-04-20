<?php

namespace Database\Factories;

use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
//        return [
//            'name' => $this->faker->name(),
//            'email' => $this->faker->unique()->safeEmail(),
//            'email_verified_at' => Carbon::now(),
//            'password' => bcrypt($this->faker->password()),
//            'remember_token' => Str::random(10),
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now(),
//            'userable_id' => $this->faker->randomNumber(),
//            'userable_type' => $this->faker->word(),
//        ];
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Replace 'password' with the desired default password
            'userable_id' => function () {
                $role = $this->faker->randomElement(['teacher', 'student']);
                if ($role === 'teacher') {
                    return Teacher::factory()->create()->id;
                } else {
                    return Student::factory()->create()->id;
                }
            },
            'userable_type' => function (array $attributes) {
                if (isset($attributes['userable_id'])) {
                    if (Teacher::find($attributes['userable_id'])) {
                        return Teacher::class;
                    } elseif (Student::find($attributes['userable_id'])) {
                        return Student::class;
                    }
                }
                return null;
            },
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
