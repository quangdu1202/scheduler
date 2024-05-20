<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MarkType\MarkType;
use App\Models\Module\Module;
use App\Models\ModuleClass\ModuleClass;
use App\Models\OriginalClass\OriginalClass;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Registration\Registration;
use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        OriginalClass::factory(5)->create();
        Module::factory(5)->create();
        PracticeRoom::factory(15)->create();
        User::factory(10)->create();
        ModuleClass::factory(25)->create();
//        PracticeClass::factory(5)->create();
//        Registration::factory(50)->create();

        foreach (['TX1', 'TX2', 'GK', 'CK'] as $type) {
            MarkType::factory()->create(['type' => $type]);
        }

        $teacher = Teacher::factory()->create();
        User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'gva@gmail.com',
            'password' => Hash::make('123'),
            'userable_id' => $teacher->id,
            'userable_type' => get_class($teacher),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $student = Student::factory()->create();
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
