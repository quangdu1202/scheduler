<?php

namespace Database\Factories;

use App\Models\PracticeRoom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PracticeRoomFactory extends Factory
{
    protected $model = PracticeRoom::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
