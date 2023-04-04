<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\exam::create([
            'id' => 1,
            'exam' => "7",
            'lesson_id' => 1,
            'uni_code' => 'das',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 2,
            'exam' => "6",
            'lesson_id' => 1,
            'uni_code' => 'ss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 3,
            'exam' => "5",
            'lesson_id' => 1,
            'uni_code' => 'aa',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 4,
            'exam' => "4",
            'lesson_id' => 1,
            'uni_code' => 'dd',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 5,
            'exam' => "3",
            'lesson_id' => 1,
            'uni_code' => 'dasdas',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 6,
            'exam' => "2",
            'lesson_id' => 1,
            'uni_code' => 'dsa',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 7,
            'exam' => "1",
            'lesson_id' => 1,
            'uni_code' => 'ssa',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 8,
            'exam' => "8",
            'lesson_id' => 3,
            'uni_code' => 'sssssdddddddddddddds',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 9,
            'exam' => "9",
            'lesson_id' => 4,
            'uni_code' => 'sssssssssssssssssssssssss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 10,
            'exam' => "10",
            'lesson_id' => 5,
            'uni_code' => 'sassssadasdasdasds',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 11,
            'exam' => "11",
            'lesson_id' => 5,
            'uni_code' => 'ssssdsadasasdaasdasdss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 12,
            'exam' => "12",
            'lesson_id' => 4,
            'uni_code' => 'sdssasdasdasdsss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);

        \App\Models\exam::create([
            'id' => 13,
            'exam' => "13",
            'lesson_id' => 4,
            'uni_code' => 'ssssasdasdasadss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 14,
            'exam' => "14",
            'lesson_id' => 5,
            'uni_code' => 'sddsssss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 15,
            'exam' => "15",
            'lesson_id' => 4,
            'uni_code' => 'sdddsssss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 16,
            'exam' => "16",
            'lesson_id' => 2,
            'uni_code' => 'ssdadassss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
        \App\Models\exam::create([
            'id' => 17,
            'exam' => "17",
            'lesson_id' => 2,
            'uni_code' => 'sadasdsadsssss',
            'user_id' => 2,
            'description'=>"asdasdasdasdasd",
        ]);
    }
}
