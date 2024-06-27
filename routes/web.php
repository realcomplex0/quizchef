<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\QuizListController;
use App\Http\Controllers\QuizEditorController;
use App\Http\Controllers\QuizBrowseController;
use App\Http\Controllers\LobbyController;
use App\Models\Lobby;
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

Route::get('/dashboard/browse', [QuizBrowseController::class, 'view'])->name('quiz_browse.view');

Route::patch('/quiz/{quiz}', [QuizListController::class, 'patch'])->name('quiz.patch');
Route::delete('/quiz/{id}', [QuizListController::class, 'destroy'])->name('quiz.destroy');
Route::get('/quiz/{id?}', [QuizEditorController::class, 'view'])->name('quiz.view');
Route::post('/quiz/{id?}', [QuizEditorController::class, 'update'])->name('quiz.update');
Route::delete('/quiz/{id}/question/{question_id}', [QuizEditorController::class, 'destroy_question'])->name('question.destroy');
Route::delete('/quiz/{id}/option/{option_id}', [QuizEditorController::class, 'destroy_option'])->name('option.destroy');

Route::post('/img/quiz/{id}/question/{question_id}', [QuizEditorController::class, 'upload_image'])->name('image.upload');
Route::delete('/img/quiz/{id}/question/{question_id}', [QuizEditorController::class, 'destroy_image'])->name('image.destroy');
Route::post('/create-lobby', [LobbyController::class, 'create'])->name('create-lobby');
Route::post('/join-lobby', [LobbyController::class, 'join'])->name('join-lobby');
Route::post('/leave-lobby', [LobbyController::class, 'leave'])->name('leave-lobby');

Route::get('/lobby', [LobbyController::class, 'playerView'])->name('lobby.play');

Route::post('/start-game', [LobbyController::class, 'startGame'])->name('lobby.start');
Route::post('/submit-answer', [LobbyController::class, 'submitAnswer'])->name('lobby.submit');
Route::post('/end-question', [LobbyController::class, 'endQuestion'])->name('lobby.end_question');
Route::post('/go-scoreboard', [LobbyController::class, 'goScoreboard'])->name('lobby.go_scoreboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
