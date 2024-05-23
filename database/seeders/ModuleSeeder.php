<?php

namespace Database\Seeders;

use App\Models\Module\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'module_code' => '0503195',
            'module_name' => 'Lập trình Java',
        ]);

        Module::create([
            'module_code' => '0503184',
            'module_name' => 'Lập trình Web bằng PHP',
        ]);

        Module::create([
            'module_code' => '0503130',
            'module_name' => 'Lập trình hướng đối tượng',
        ]);
    }
}
