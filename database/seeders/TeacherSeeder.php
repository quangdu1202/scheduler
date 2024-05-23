<?php

namespace Database\Seeders;

use App\Models\Teacher\Teacher;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
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
        for ($i = 0; $i < 3; $i++) {

            $teacher = Teacher::create([
                'department' => 'Khoa CNTT',
            ]);

            User::create([
                'name' => $this->faker->name(),
                'email' => 'gv' . $i + 1 . '@gmail.com',
                'password' => Hash::make('123'),
                'userable_id' => $teacher->id,
                'userable_type' => get_class($teacher),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $teacher = Teacher::create([
            'department' => 'Khoa CNTT',
        ]);

        User::create([
            'name' => 'Giảng Viên Test',
            'email' => 'gva@gmail.com',
            'password' => Hash::make('123'),
            'userable_id' => $teacher->id,
            'userable_type' => get_class($teacher),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
