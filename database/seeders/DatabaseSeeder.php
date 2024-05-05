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
        User::factory(50)->create();
        ModuleClass::factory(5)->create();
//        PracticeClass::factory(5)->create();
//        Registration::factory(50)->create();

        foreach (['TX1', 'TX2', 'GK', 'CK'] as $type) {
            MarkType::factory()->create(['type' => $type]);
        }
    }
}
