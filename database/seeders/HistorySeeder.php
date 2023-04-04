<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\History::create([
            'id' => 1,
            'exam_id' => 1,
            'user_id' => 1
        ]);
        \App\Models\History::create([
            'id' => 2,
            'exam_id' => 2,
            'user_id' => 1
        ]);
        \App\Models\History::create([
            'id' => 3,
            'exam_id' => 3,
            'user_id' => 1
        ]);
        \App\Models\History::create([
            'id' => 4,
            'exam_id' => 4,
            'user_id' => 1
        ]);
                \App\Models\History::create([
            'id' => 5,
            'exam_id' => 8,
            'user_id' => 1
        ]);
    }
}
