<?php

namespace Database\Factories;

use App\Models\StudentModuleClass;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentModuleClassFactory extends Factory
{
    protected $model = StudentModuleClass::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
