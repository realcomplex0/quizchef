<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\QuizListController;
use App\Http\Controllers\LobbyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('/');

Route::get('/dashboard', [QuizListController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/lobby', [LobbyController::class, 'host'])->name('lobby.host');

Route::post('/create-lobby', [LobbyController::class, 'create'])->name('create-lobby');

Route::post('/join-lobby', [LobbyController::class, 'join'])->name('join-lobby');

Route::post('/lobby', [LobbyController::class, 'play'])->name('lobby.play');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
