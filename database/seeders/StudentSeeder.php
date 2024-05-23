<?php

namespace Database\Seeders;

use App\Models\OriginalClass\OriginalClass;
use App\Models\Student\Student;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    protected Generator $faker;

    public function __construct(
        Generator $faker
    )
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 99; $i++) {
            $studentCode = $this->faker->numberBetween(2020604000, 2020604594);

            $student = Student::create([
                'student_code' => $studentCode,
                'original_class_id' => OriginalClass::inRandomOrder()->value('id'),
            ]);

            User::create([
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => Hash::make('123'),
                'userable_id' => $student->id,
                'userable_type' => get_class($student),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $student = Student::create([
            'student_code' => '2020604595',
            'original_class_id' => OriginalClass::inRandomOrder()->value('id'),
        ]);

        User::create([
            'name' => 'Dư Đăng Quang',
            'email' => 'hsa@gmail.com',
            'password' => Hash::make('123'),
            'userable_id' => $student->id,
            'userable_type' => get_class($student),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
