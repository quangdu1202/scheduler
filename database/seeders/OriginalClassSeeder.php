<?php

namespace Database\Seeders;

use App\Models\OriginalClass\OriginalClass;
use Illuminate\Database\Seeder;

class OriginalClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OriginalClass::create([
            'original_class_code' => '2020DHCNTT01',
            'original_class_name' => '2020DHCNTT01',
        ]);

        OriginalClass::create([
            'original_class_code' => '2020DHCNTT04',
            'original_class_name' => '2020DHCNTT04',
        ]);
    }
}
