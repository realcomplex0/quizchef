<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\QuizSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Redis::flushDB();
        User::create([
            'username' => 'JohnDoe123',
            'email' => 'test@test.com',
            'password' => Hash::make('test')
        ]);
        User::create([
            'username' => 'Test',
            'email' => 'test@fake.com',
            'password' => Hash::make('test')
        ]);
        $this->call([QuizSeeder::class, QuestionSeeder::class]);
    }
}
