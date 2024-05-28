<?php

namespace Database\Seeders;

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

        User::create([
            'name' => 'Administrator 1',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'is_admin' => 1,
            'userable_id' => null,
            'userable_type' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
