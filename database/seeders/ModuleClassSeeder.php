<?php

namespace Database\Seeders;

use App\Models\ModuleClass\ModuleClass;
use Illuminate\Database\Seeder;

class ModuleClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModuleClass::create([
            'module_id' => 1,
            'teacher_id' => '1',
            'module_class_code' => '202410503195001',
            'module_class_name' => 'Lập trình Java 001',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 1,
            'teacher_id' => '2',
            'module_class_code' => '202410503195002',
            'module_class_name' => 'Lập trình Java 002',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 1,
            'teacher_id' => '3',
            'module_class_code' => '202410503195003',
            'module_class_name' => 'Lập trình Java 003',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 2,
            'teacher_id' => '1',
            'module_class_code' => '202410503184001',
            'module_class_name' => 'Lập trình Web bằng PHP 001',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 2,
            'teacher_id' => '2',
            'module_class_code' => '202410503184002',
            'module_class_name' => 'Lập trình Web bằng PHP 002',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 2,
            'teacher_id' => '3',
            'module_class_code' => '202410503184003',
            'module_class_name' => 'Lập trình Web bằng PHP 003',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 3,
            'teacher_id' => '1',
            'module_class_code' => '202410503130001',
            'module_class_name' => 'Lập trình hướng đối tượng 001',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 3,
            'teacher_id' => '2',
            'module_class_code' => '202410503130002',
            'module_class_name' => 'Lập trình hướng đối tượng 002',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);

        ModuleClass::create([
            'module_id' => 3,
            'teacher_id' => '3',
            'module_class_code' => '202410503130003',
            'module_class_name' => 'Lập trình hướng đối tượng 003',
            'start_date' => '2024-05-13',
            'end_date' => '2024-09-13',
            'student_qty' => '65',
            'status' => '1',
        ]);
    }
}
