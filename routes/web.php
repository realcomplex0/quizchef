<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\QuizListController;
use App\Http\Controllers\QuizEditorController;
use App\Http\Controllers\LobbyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'isLoggedIn' => Auth::check(),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('/');

Route::get('/dashboard', [QuizListController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::delete('/quiz/{id}', [QuizListController::class, 'destroy'])->name('quiz.destroy');
Route::get('/quiz/{id?}', [QuizEditorController::class, 'view'])->name('quiz.view');
Route::post('/quiz/{id?}', [QuizEditorController::class, 'update'])->name('quiz.update');
Route::delete('/quiz/{id}/question/{question_id}', [QuizEditorController::class, 'destroy_question'])->name('question.destroy');
Route::delete('/quiz/{id}/option/{option_id}', [QuizEditorController::class, 'destroy_option'])->name('option.destroy');

Route::post('/create-lobby', [LobbyController::class, 'create'])->name('create-lobby');
Route::post('/join-lobby', [LobbyController::class, 'join'])->name('join-lobby');

Route::get('/lobby', [LobbyController::class, 'hostView'])->name('lobby.host');
Route::post('/lobby', [LobbyController::class, 'playerView'])->name('lobby.play');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
