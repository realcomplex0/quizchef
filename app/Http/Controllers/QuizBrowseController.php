<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;

class QuizBrowseController extends Controller
{
    public function view($id = null){
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $quizzes = Quiz::where('public', true)->withCount('questions')->get();
        Log::info($quizzes);
        return Inertia::render('Dashboard/QuizBrowse', ['quizzes' => $quizzes]);
    }
}
