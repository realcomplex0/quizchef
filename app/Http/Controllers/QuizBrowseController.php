<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizBrowseController extends Controller
{
    public function view($id = null){
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return Inertia::render('Dashboard/QuizBrowse');
    }
}
