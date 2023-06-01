<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'id' => 1,
            'birth' => 1684164038127,
            'name' => 'Rizky Julian ricaldy',
            'point' => 1000,
            'username' => 'qwe',
            'password' => bcrypt('qweqweqwe')
        ]);
        \App\Models\User::create([
            'id' => 2,
            'birth' => 1684164038127,
            'point' => 1000,
            'name' => 'Rizky',
            'username' => 'qwee',
            'password' => bcrypt('qweqweqwe')
        ]);
    }
}
