<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Faker\Factory as Faker;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $quizID = Quiz::pluck('id');
        foreach($quizID as $id) {
            $question_count = rand(2, 7);
            for($i = 0; $i < $question_count; $i ++ ){
                Question::create([
                    'title' => $faker->sentence . '?',
                    'index' => $i,
                    'quiz_id' => $id
                ]);
            }
        }
        $questionID = Question::pluck('id');
        foreach($questionID as $id) {
            $option_count = rand(2, 6);
            for($i = 0; $i < $option_count; $i ++ ){
                Option::create([
                    'title' => $faker->words(1, true),
                    'question_id' => $id,
                    'index' => $i
                ]);
            }
        }
    }
}
