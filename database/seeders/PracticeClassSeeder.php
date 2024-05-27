<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Module\Module;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Teacher\Teacher;
use Exception;
use Faker\Generator;
use Illuminate\Database\Seeder;

class PracticeClassSeeder extends Seeder
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
     * @throws Exception
     */
    public function run(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                $pClassCode = '';
                $pClassName = '';

                if ($j == 1) {
                    $pClassCode = 202410503195 . 'TH00' . $i;
                    $pClassName = 'Lập trình Java TH00' . $i . 'TH';
                }

                if ($j == 2) {
                    $pClassCode = 202410503184 . 'TH00' . $i;
                    $pClassName = 'Lập trình Web bằng PHP TH00' . $i . 'TH';
                }

                if ($j == 3) {
                    $pClassCode = 202410503130 . 'TH00' . $i;
                    $pClassName = 'Lập trình hướng đối tượng TH00' . $i;
                }

                PracticeClass::create([
                    'module_id' => $j,
                    'teacher_id' => null,
                    'practice_class_code' => $pClassCode,
                    'practice_class_name' => $pClassName,
                    'registered_qty' => 0,
                    'shift_qty' => 2,
                    'max_qty' => 65,
                    'status' => 0,
                ]);
            }
        }
    }
}
