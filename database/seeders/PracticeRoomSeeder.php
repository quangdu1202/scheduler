<?php

namespace Database\Seeders;

use App\Models\PracticeRoom\PracticeRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PracticeRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PracticeRoom::create([
            'name' => 'Phòng thực hành 1',
            'location' => '701A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 2',
            'location' => '702A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 3',
            'location' => '703A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 4',
            'location' => '704A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 5',
            'location' => '801A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 6',
            'location' => '802A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 7',
            'location' => '803A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 8',
            'location' => '804A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 9',
            'location' => '901A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 10',
            'location' => '902A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 11',
            'location' => '903A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);

        PracticeRoom::create([
            'name' => 'Phòng thực hành 12',
            'location' => '904A1',
            'pc_qty' => 30,
            'status' => 1,
        ]);
    }
}
