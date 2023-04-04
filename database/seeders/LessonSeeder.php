<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\lesson::create([
            'id' => 1,
            'lesson' => "Matematika",
        ]);
        \App\Models\lesson::create([
            'id' => 2,
            'lesson' => "Bahasa Indonesia",
        ]);
        \App\Models\lesson::create([
            'id' => 3,
            'lesson' => "IPA",
        ]);
        \App\Models\lesson::create([
            'id' => 4,
            'lesson' => "IPS",
        ]);
        \App\Models\lesson::create([
            'id' => 5,
            'lesson' => "KUA",
        ]);
        \App\Models\lesson::create([
            'id' => 6,
            'lesson' => "Lainnya",
        ]);
    }
}
