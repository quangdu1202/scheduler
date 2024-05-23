<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OriginalClassSeeder::class,
            ModuleSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            ModuleClassSeeder::class,
            PracticeRoomSeeder::class,
            StudentModuleClassSeeder::class,
            PracticeClassSeeder::class,
        ]);
    }
}
