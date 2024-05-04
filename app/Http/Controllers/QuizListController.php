<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Quiz;
use Inertia\Inertia;

class QuizListController extends Controller
{
    public function index() 
    {
        $user = Auth::user();
        
        $quizzes = Quiz::where('user_id', $user->id)->get();
        Log::Info($quizzes);
        return Inertia::render('Dashboard/QuizList', ['quizzes' => $quizzes]);
    }
}
