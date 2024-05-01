<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Module\Module;
use App\Models\ModuleClass\ModuleClass;
use App\Models\OriginalClass\OriginalClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

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
        ModuleClass::factory(5)->create();
    }
}
