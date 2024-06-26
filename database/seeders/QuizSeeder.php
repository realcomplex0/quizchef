<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\User;
use Faker\Factory as Faker;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userID = User::pluck('id');
        for($i = 0; $i < 10; $i ++ ) {
            Quiz::create([
                'title' => $faker->words(2,true),
                'user_id' => $userID->random(),
                'plays' => rand(0,10000)
            ]);
        }
    }
}
