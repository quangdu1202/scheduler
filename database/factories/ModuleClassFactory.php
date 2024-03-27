<?php

namespace Database\Factories;

use App\Models\ModuleClass;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ModuleClassFactory extends Factory
{
    protected $model = ModuleClass::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
