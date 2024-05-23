<?php

namespace Database\Seeders;

use App\Models\StudentModuleClass\StudentModuleClass;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentModuleClassSeeder extends Seeder
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
        for ($i = 1; $i <= 9; $i++) {
            for ($j = 1; $j <= 60; $j++) {
                StudentModuleClass::create([
                    'student_id' => $j,
                    'module_class_id' => $i,
                ]);
            }

            StudentModuleClass::create([
                'student_id' => 100,
                'module_class_id' => $i,
            ]);
        }
    }
}
